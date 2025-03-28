<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\SplashScreenCollection;
use App\Models\App\SplashScreens;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\Offers;
use App\Utility\CategoryUtility;
use App\Models\Frontend\Banner;
use App\Models\Frontend\HomeSlider;
use App\Models\Subscriber;
use App\Models\HeaderMenus;
use App\Models\Shops;
use App\Models\Page;
use App\Models\Faqs;
use App\Models\Contacts;
use App\Http\Resources\V2\WebHomeCategoryCollection;
use App\Http\Resources\V2\WebHomeBrandCollection;
use App\Http\Resources\V2\WebHomeOffersCollection;
use App\Http\Resources\V2\WebHomeProductsCollection;
use Illuminate\Http\Request;
use App\Mail\ContactEnquiry;
use Cache;
use Mail;

class WebsiteController extends Controller
{
    public function websiteHeader(){
        $data = [];
        $data['menus'] =  Cache::remember('header_menus', 3600, function () {
                            $menus = HeaderMenus::with(['category'])->orderBy('id','asc')->get();
                            $data['menus'] = $menus;
                            $details = [];
                            if(!empty($menus[0])){
                                foreach($menus as $mn){
                                    $details[] = [
                                        'id' => $mn->category_id ?? '',
                                        'name'=>$mn['category']->name ?? '',
                                        'slug'=>$mn['category']->slug ?? '',
                                        'sub_categories' => getImmediateSubCategories($mn->category_id),
                                        'brands' =>getHeaderCategoryBrands($mn->brands)
                                    ];
                                }
                            }
                            return $details;
                        });
        $data['brands'] =  Cache::remember('header_brands', 3600, function () { 
            $header_brands = get_setting('header_brands');
            $brands = Brand::whereIn('id', json_decode($header_brands))->where('is_active', 1)->get();
            return new WebHomeBrandCollection($brands);
        });
        $data['header'] = [
            'show_top_offer' => get_setting('show_top_header_offer'),
            'top_offer_content' => get_setting('top_header_offer_title'),
            'header_phone' =>  get_setting('header_phone') 
        ];

        $data['footer'] = $this->websiteFooter();
        return response()->json(['success' => true,"message"=>"Success","data" => $data],200);
    } 

    public function websiteFooter(){
        $data = [];

        $pageData['facebook']   = get_setting('facebook_link');
        $pageData['instagram']  = get_setting('instagram_link');
        $pageData['twitter']    = get_setting('twitter_link');
        $pageData['youtube']    = get_setting('youtube_link');
        $pageData['linkedin']   = get_setting('linkedin_link');
        $pageData['whatsapp']   = get_setting('whatsapp_link');
        $pageData['dribbble']   = get_setting('dribbble_link');

        
        $data['newsletter_title'] = get_setting('newsletter_title');
        $data['app_section_title'] = get_setting('app_title');
        $data['play_store_link'] = get_setting('play_store_link');
        $data['app_store_link'] = get_setting('app_store_link');
        $data['social_title'] = get_setting('social_title');
        $data['social_links'] = $pageData;
        $data['address'] = get_setting('contact_address');
        $data['phone1'] = get_setting('contact_phone');
        $data['phone2'] = get_setting('contact_phone2');
        $data['email'] = get_setting('contact_email');
        $data['copyright'] = get_setting('frontend_copyright_text');

        $payments = explode(',',get_setting('payment_method_images'));
        $images = [];
        if(!empty($payments)){
            foreach($payments as $pay){
                $images[] = uploaded_asset($pay);
            }
        }
        $data['payment_methods'] = $images;

        return $data;
    }

    public function websiteHome(){
        $data['slider'] = Cache::rememberForever('homeSlider', function () {
                        $slider = [];
                        $sliders = HomeSlider::whereStatus(1)->with(['mainImage'])->orderBy('sort_order')->get();
                        if ($sliders) {
                            foreach ($sliders as $slid) {
                                $slider[] = [
                                    'id' => $slid->id,
                                    'name' => $slid->name,
                                    'type' => $slid->link_type,
                                    'link' => $slid->getBannerLink(),
                                    'type_id' => $slid->link_ref_id,
                                    'sort_order' => $slid->sort_order,
                                    'status' => $slid->status,
                                    'image' => api_upload_asset($slid->image)
                                ];
                            }
                            return $slider;
                        }
        });

        $data['top_categories'] = Cache::rememberForever('top_categories', function () {
            $categories = get_setting('home_categories');
            if ($categories) {
                $details = Category::whereIn('id', json_decode($categories))->where('is_active', 1)
                    ->with(['icon'])
                    ->get();
                return new WebHomeCategoryCollection($details);
            }
        });

        $data['top_brands'] = Cache::rememberForever('top_brands', function () {
            $brands = get_setting('home_brands');
            if ($brands) {
                $details = Brand::whereIn('id', json_decode($brands))->where('is_active', 1)->get();
                return new WebHomeBrandCollection($details);
            }
        });

        $data['best_selling'] = Cache::remember('best_selling_products', 3600, function () {
            $product_ids = get_setting('best_selling');
            if ($product_ids) {
                $products =  Product::where('published', 1)->whereIn('id', json_decode($product_ids))->with('brand')->get();
                return new WebHomeProductsCollection($products);
            }
        });

        $data['offers'] = Cache::rememberForever('home_offers', function () {
            $offers = get_setting('home_offers');
            if ($offers) {
                $details = Offers::whereIn('id', json_decode($offers))->whereRaw('(now() between start_date and end_date)')->get();
                return new WebHomeOffersCollection($details);
            }
        });

        $home_banners = BusinessSetting::whereIn('type', array('home_banner_1', 'home_banner_2', 'home_banner_3'))->get()->keyBy('type');
        $banners = [];
        $all_banners = Banner::with(['mainImage','mobileImage'])->where('status', true)->get();
        foreach($home_banners as $key => $hb){
            $bannerid = json_decode($hb->value);
            if(!empty($bannerid)){
                $bannerid = $bannerid[0];
            }
            $bannerData = $all_banners->where('id', $bannerid)->first();
            if(!empty($bannerData)){
                $banners[$key] = array(
                    'type' => $bannerData->link_type ?? '',
                    'link' => $bannerData->link_type == 'external' ? $bannerData->link : $bannerData->getBannerLink(),
                    'type_id' => $bannerData->link_ref_id,
                    'image' => ($bannerData->mainImage) ? storage_asset($bannerData->mainImage->file_name) : '',
                    'mob_image' => ($bannerData->mobileImage) ? storage_asset($bannerData->mobileImage->file_name) : '',
                );
            }else{
                $banners[$key] = array();
            }
        }
       
        $data['banners'] = $banners;

        $data['meta'] =  Page::where('type', 'home_page')->select('meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image')->first();
        if($data['meta']){
            $data['meta']->meta_image       = ($data['meta']->meta_image != NULL) ? uploaded_asset($data['meta']->meta_image) : '';
        }

        return response()->json(['success' => true,"message"=>"Success","data" => $data],200);
    }

    public function footer()
    {
        return response()->json([
            'result' => true,
            'app_links' => array([
                'play_store' => array([
                    'link' => get_setting('play_store_link'),
                    'image' => api_asset(get_setting('play_store_image')),
                ]),
                'app_store' => array([
                    'link' => get_setting('app_store_link'),
                    'image' => api_asset(get_setting('app_store_image')),
                ]),
            ]),
            'social_links' => array([
                'facebook' => get_setting('facebook_link'),
                'twitter' => get_setting('twitter_link'),
                'instagram' => get_setting('instagram_link'),
                'youtube' => get_setting('youtube_link'),
                'linkedin' => get_setting('linkedin_link'),
            ]),
            'copyright_text' => get_setting('frontend_copyright_text'),
            'contact_phone' => get_setting('contact_phone'),
            'contact_email' => get_setting('contact_email'),
            'contact_address' => get_setting('contact_address'),
        ]);
    }

    public function offerDetails(Request $request){
        $offerid = $request->offer_id;
        $limit = $request->has('limit') ? $request->limit : '';
        $offset = $request->has('offset') ? $request->offset : 0;
        if($offerid != ''){
            $Offer = Offers::where('status',1)->find($offerid);
            if(!$Offer){
                return response()->json(['success' => false,"message"=>"No Data Found!","data" => []],400);
            }else {
                $temp = array();
                $temp['id'] = $Offer->id;
                $temp['name'] = $Offer->name;
                $temp['type'] = $Offer->link_type;
    
                if ($Offer->link_type == 'product') {
                    $result = array();
                    $product_query  = Product::whereIn('id', json_decode($Offer->link_id))->wherePublished(1);
                    if($limit != ''){
                        $product_query->skip($offset)->take($limit);
                    }
                    $products = $product_query->get();

                    foreach ($products as $prod) {
                        $tempProducts = array();
                        $tempProducts['id'] = $prod->id;
                        $tempProducts['name'] = $prod->name;
                        $tempProducts['image'] = get_product_image($prod->thumbnail_img,'300');
                        $tempProducts['sku'] = $prod->sku;
                        $tempProducts['main_price'] = home_discounted_base_price_wo_currency($prod);
                        $tempProducts['min_qty'] = $prod->min_qty;
                        $tempProducts['slug'] = $prod->slug;
                        
                        $result[] = $tempProducts;
                    }
                }elseif ($Offer->link_type == 'brand') {
                    $brandQuery =  Brand::with(['logoImage'])->where('is_active', 1)->whereIn('id', json_decode($Offer->link_id));
                    if($limit != ''){
                        $brandQuery->skip($offset)->take($limit);
                    }
                    $brands = $brandQuery->get();
                    $result = array();
                    foreach ($brands as $brand) {
                        $tempBrands = array();
                        $tempBrands['id'] = $brand->id;
                        $tempBrands['name'] = $brand->name;
                        $tempBrands['image'] = storage_asset($brand->logoImage->file_name);
                        $result[] = $tempBrands;
                    }
                }elseif ($Offer->link_type == 'category') {
                    $categoriesQuery =  Category::whereIn('id', json_decode($Offer->link_id));
                    if($limit != ''){
                        $categoriesQuery->skip($offset)->take($limit);
                    }
                    $categories = $categoriesQuery->where('is_active', 1)->get();
                    $result = array();
                    foreach ($categories as $category) {
                        $tempCats = array();
                        $tempCats['id'] = $category->id;
                        $tempCats['name'] = $category->name;
                        $tempCats['image'] = api_upload_asset($category->icon);
                        $result[] = $tempCats;
                    }
                }
                $temp['list'] = $result;
                $temp['next_offset'] = $offset + $limit;
                return response()->json(['success' => true,"message"=>"Data fetched successfully!","data" => $temp],200);
            }
        }else{
            return response()->json(['success' => false,"message"=>"No Data Found!","data" => []],400);
        }
    }

    public function homeAdBanners()
    {
        $all_banners = Banner::with(['mainImage'])->where('status', true)->get();

        $banner_id = BusinessSetting::whereIn('type', [
            'app_banner_1',
            'app_banner_2',
            'app_banner_3',
            'app_banner_4',
            'app_banner_5',
            'app_banner_6',
        ])->get();

        $banners = array();

        foreach ($banner_id as $banner) {
            $ids = json_decode($banner->value);
            if ($ids) {
                foreach ($ids as $id) {
                    $c_banner = $all_banners->where('id', $id)->first();
                    $banners[$banner->type][] = array(
                        // 'image1' => $c_banner,
                        'link_type' => $c_banner->link_type ?? '',
                        'link_id' => $c_banner->link_type == 'external' ? $c_banner->link : $c_banner->link_ref_id,
                        'image' => storage_asset($c_banner->mainImage->file_name)
                    );
                }
            }
        }

        return response()->json([
            "result" => true,
            "data" => $banners,
        ]);
    }

    public function websiteCategories(){
        $categories =  Cache::remember('category_filter', 3600, function () { 
            return $categories =  getSidebarCategoryTree();
        });
        return response()->json(['success' => true,"message"=>"Success","data" => $categories],200);
    }

    public function categoryOffers(){
        $offers= Offers::with(['category'])->where('link_type','category')->where('status',1)->whereRaw('(now() between start_date and end_date)')->get();
       
        $result = [];
        if($offers){
            foreach($offers as $off){
                $brandIds = json_decode($off->link_id);
                $brands = Brand::whereIn('id', $brandIds)->where('is_active', 1)->get();
                
                $result[$off->category->name]['offer'] = [
                                                        'id' => $off->id,
                                                        'name' => $off->name,
                                                        'slug' => $off->slug,
                                                        'category_name' => $off->category->name
                                                    ];
                if($brands){
                    foreach($brands as $brds){
                        $result[$off->category->name]['brands'][] = [
                            'id' => $brds->id,
                            'name' => $brds->name,
                            'slug' => $brds->slug,
                            'logo' => api_upload_asset($brds->logo),
                            'offer_tag' => getOfferTag($off)
                        ];
                        
                    }
                }
            }
        }
        $meta = Page::where('type', 'offers')->select('meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image')->first();
        if($meta){
            $meta->meta_image       = ($meta->meta_image != NULL) ? uploaded_asset($meta->meta_image) : '';
        }
        return response()->json(['success' => true,"message"=>"Success","data" => $result, 'meta'=> $meta],200);
    }

    public function storeLocations(){
        $shops = Shops::where('status',1)->orderBy('name','asc')->get();

        $meta = Page::where('type', 'store_locator')->select('title', 'sub_title', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image')->first();
        // $shops['page_data'] = $query;
        if($meta){
            $meta->meta_image       = ($meta->meta_image != NULL) ? uploaded_asset($meta->meta_image) : '';
        }
        return response()->json(['status' => true,"message"=>"Success","data" => $shops,"page_data" => $meta],200);
    }

    public function pageContents(Request $request){
        $page_type = $request->has('page') ? $request->page : null;
        $faqs = [];
        if($page_type){
            $query = Page::where('type', $page_type);

            if($page_type == 'terms_conditions' || $page_type == 'privacy_policy' || $page_type == 'return_refund' || $page_type == 'shipping_delivery'){
                $query->select('title', 'content', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }

            if($page_type == 'store_locator' || $page_type == 'prescriptions'){
                $query->select('title', 'sub_title', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }
            
            if($page_type == 'faq'){
                $query->select('title', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
                $faqs = Faqs::select('question','answer','sort_order')->orderBy('sort_order','asc')->get();
            }
            if($page_type == 'contact_us'){
                $query->select('title', 'sub_title', 'content as working_hours', 'heading1 as phone', 'heading2 as email', 'heading3 as form_heading', 'heading4 as latitude', 'heading5 as longitude', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }
            if($page_type == 'offers'){
                $query->select('meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image');
            }

            $pageData = $query->first();

            if($pageData){
                $pageData->meta_image       = ($pageData->meta_image != NULL) ? uploaded_asset($pageData->meta_image) : '';
            }

            if($page_type == 'faq'){
                $pageData['faqs'] = $faqs;
            }
            if($page_type == 'contact_us'){
                $pageData['facebook']   = get_setting('facebook_link');
                $pageData['instagram']  = get_setting('instagram_link');
                $pageData['twitter']    = get_setting('twitter_link');
                $pageData['youtube']    = get_setting('youtube_link');
                $pageData['linkedin']   = get_setting('linkedin_link');
                $pageData['whatsapp']   = get_setting('whatsapp_link');
                $pageData['dribbble']   = get_setting('dribbble_link');
            }
            return response()->json(['status' => true,"message"=>"Success","data" => $pageData],200);
        }else{
            return response()->json(['status' => false,"message"=>"No data found","data" => []],200);
        }
    }

    public function contactUs(Request $request){
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'message' => 'required'
        ], [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'phone.required' => 'Please enter your phone',
            'message.required' => 'Please enter your message'
        ]);

        $con                = new Contacts;
        $con->name          = $request->name;
        $con->email         = $request->email;
        $con->phone  = $request->phone;
        $con->message       = $request->message;
        $con->save();

        Mail::to(env('MAIL_ADMIN'))->queue(new ContactEnquiry($con));

        return response()->json(['status' => true,"message"=>"Thank you for getting in touch. Our team will contact you shortly.","data" => []],200);
    }
}

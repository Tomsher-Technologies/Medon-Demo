<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ProductCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\ProductFilterCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\FlashDealCollection;
use App\Models\FlashDeal;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Color;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Utility\CategoryUtility;
use App\Utility\SearchUtility;
use Cache;
use DB;
use Auth;

class ProductController extends Controller
{

    public function relatedProducts(Request $request){
        $limit = $request->limit ? $request->limit : 10;
        $offset = $request->offset ? $request->offset : 0;
        $category_slug = $request->category_slug ?? '';
        $product_slug = $request->product_slug ?? '';

       
        $product_query  = Product::wherePublished(1);

        if ($category_slug) {
            $category_ids = Category::where('slug',$category_slug)->pluck('id')->toArray();
            $product_query->whereIn('category_id', $category_ids);
        }
        $product_query->where('slug','!=', $product_slug)->latest();

        $products = $product_query->skip($offset)->take($limit)->get();
        
        $response = new ProductFilterCollection($products);
      
        return response()->json(['success' => true,"message"=>"Success","data" => $response],200);
    }
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;
        $offset = $request->offset ? $request->offset : 0;
        $min_price = $request->min_price ? $request->min_price : '';
        $max_price = $request->max_price ? $request->max_price : '';
        $category = $request->category ? explode(',', $request->category)  : false;
        $brand = $request->brand ? explode(',', $request->brand)  : false;
        $offer = $request->offer ? explode(',', $request->offer)  : false;

        $category_slug = $request->category_slug ? explode(',', $request->category_slug)  : false;
        $brand_slug = $request->brand_slug ? explode(',', $request->brand_slug)  : false;

        $offer_slug = $request->offer_slug ? explode(',', $request->offer_slug)  : false;
// DB::enableQueryLog();
        $product_query  = Product::wherePublished(1);
        
        $metaData = Page::where('type', 'product_listing')->select('image1','meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image')->first();
        if($metaData){
            $metaData->image1           = ($metaData->image1 != NULL) ? uploaded_asset($metaData->image1) : '';
            $metaData->meta_image       = ($metaData->meta_image != NULL) ? uploaded_asset($metaData->meta_image) : '';
            $metaData->footer_title     = '';
            $metaData->footer_content   = '';
        }
        
        $meta = NULL;
        if($offer){
            $product_ids = getOffersProductIds($offer,1);
            $product_query->whereIn('id', $product_ids);
        }

        if($offer_slug){
            $product_ids = getOffersProductIds($offer_slug,0);
            $product_query->whereIn('id', $product_ids);
        }

        if ($category) {
            $childIds = [];
            $childIds[] = $category;
            
            if(!empty($category)){
                foreach($category as $cId){
                    $childIds[] = getChildCategoryIds($cId);
                }
            }
            
            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            
            $product_query->whereIn('category_id', $childIds);
            if(!empty($category)){
                $meta = Category::select('meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_keyword', 'footer_title', 'footer_content')->find($category[0]);
            }
        }

        if ($category_slug) {
            $catids = implode(',', $category_slug);
            $childIds = [];
            $category_ids = Category::whereIn('slug',$category_slug)->pluck('id')->toArray();
            $childIds[] = $category_ids;
            if(!empty($category_ids)){
                foreach($category_ids as $cId){
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            // print_r($childIds);
            // die;
            $product_query->whereIn('category_id', $childIds);
            if(!empty($category_ids)){
                $meta = Category::select('meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_keyword', 'footer_title', 'footer_content')->find($category_ids[0]);
            }
        }   

        if ($brand) {
            $product_query->whereIn('brand_id', $brand);
        }
        if ($brand_slug) {
            $brand_ids = Brand::whereIn('slug',$brand_slug)->where('is_active', 1)->pluck('id')->toArray();
            $product_query->whereIn('brand_id', $brand_ids);
        }

        if ($request->order_by) {
            switch ($request->order_by) {
                case 'latest':
                    $product_query->latest();
                    break;
                case 'oldest':
                    $product_query->oldest();
                    break;
                case 'name_asc':
                    $product_query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $product_query->orderBy('name', 'desc');
                    break;
                case 'price_high':
                    $product_query->select('*', DB::raw("(SELECT MAX(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    $product_query->orderBy('sort_price', 'desc');
                    break;
                case 'price_low':
                    $product_query->select('*', DB::raw("(SELECT MIN(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    $product_query->orderBy('sort_price', 'asc');
                    break;
                default:
                    # code...
                    break;
            }
        }

        if ($request->search) {
            $sort_search = $request->search;
            
            $products = $product_query->where(function ($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%')
                ->orWhere('tags', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });

            });
            
            
            // $products = $product_query
            //     ->where('name', 'like', '%' . $sort_search . '%')
            //     ->orWhere('tags', 'like', '%' . $sort_search . '%')
            //     ->orWhereHas('stocks', function ($q) use ($sort_search) {
            //         $q->where('sku', 'like', '%' . $sort_search . '%');
            //     });

            SearchUtility::store($sort_search, $request);
        }
        if ($min_price != null && $min_price != "" && is_numeric($min_price)) {
            $product_query->where('unit_price', '>=', $min_price);
        }

        if ($max_price != null && $max_price != "" && is_numeric($max_price)) {
            $product_query->where('unit_price', '<=', $max_price);
        }

        $total_count = $product_query->count();
        $products = $product_query->skip($offset)->take($limit)->get();
        // dd(DB::getQueryLog());
        $next_offset = $offset + $limit;
      
        $response = new ProductFilterCollection($products);
        
        // print_r($metaData);
        if($meta != NULL){
            $metaData->meta_title           = ($meta->meta_title != '') ? $meta->meta_title : $metaData->meta_title ;
            $metaData->meta_description     = ($meta->meta_description != '') ? $meta->meta_description : $metaData->meta_description ;
            $metaData->og_title             = ($meta->og_title != '') ? $meta->og_title : $metaData->og_title ;
            $metaData->og_description       = ($meta->og_description != '') ? $meta->og_description : $metaData->og_description ;
            $metaData->twitter_title        = ($meta->twitter_title != '') ? $meta->twitter_title : $metaData->twitter_title ;
            $metaData->twitter_description  = ($meta->twitter_description != '') ? $meta->twitter_description : $metaData->twitter_description ;
            $metaData->keywords             = ($meta->meta_keyword != '') ? $meta->meta_keyword : $metaData->keywords ;
            $metaData->footer_title         = $meta->footer_title;
            $metaData->footer_content       = $meta->footer_content;
            
        }
  
        return response()->json(['success' => true,"message"=>"Success","data" => $response, "total_count" => $total_count, "next_offset" => $next_offset, 'meta' => $metaData ],200);

    }

    public function show(Request $request)
    {
        $product_id = $request->has('product_id') ? $request->product_id : '';
        $product_slug = $request->has('product_slug') ? $request->product_slug : '';

        $product = [];
        if($product_id != ''){
            $product = Product::with(['stocks','tabs','reviews','category','seo'])->where('published',1)->findOrFail($product_id);
        }

        if($product_slug != ''){
            $product = Product::with(['stocks','tabs','reviews','category','seo'])->where('slug',$product_slug)->where('published',1)->first();
        }
        if(!empty($product)){
            $product->user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        }
        
        //    echo '<pre>';
        //    print_r($product->stocks);
        // //    die;
        // // // echo auth('sanctum')->user()->id;
        // // // print_r($request->user());
        // die;
        if(!empty($product)){
            return new ProductDetailCollection($product);
        }
        
        return response()->json(['status' => false,"message"=>"No data found","data" => []],200);
    }

    public function admin()
    {
        return new ProductCollection(Product::where('added_by', 'admin')->latest()->paginate(10));
    }

    public function seller($id, Request $request)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::where('added_by', 'seller')->where('user_id', $shop->user_id);
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection($products->latest()->paginate(10));
    }

    public function category($id, Request $request)
    {
        $category_ids = CategoryUtility::children_ids($id);
        $category_ids[] = $id;

        $products = Product::whereIn('category_id', $category_ids)->physical();

        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }


    public function brand($id, Request $request)
    {
        $products = Product::where('brand_id', $id)->physical();
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }

        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function todaysDeal()
    {
        return Cache::remember('app.todays_deal', 0, function () {
            $products = Product::where('todays_deal', 1)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->latest()->get());
        });
    }

    public function flashDeal()
    {
        return Cache::remember('app.flash_deals', 0, function () {
            $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
            return new FlashDealCollection($flash_deals);
        });
    }

    public function featured()
    {
        $products = Product::where('featured', 1)->physical();
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function bestSeller()
    {
        return Cache::remember('app.best_selling_products', 0, function () {
            $products = Product::orderBy('num_of_sale', 'desc')->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }

    public function related($id)
    {
        return Cache::remember("app.related_products-$id", 0, function () use ($id) {
            $product = Product::find($id);
            $products = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }

    public function topFromSeller($id)
    {
        return Cache::remember("app.top_from_this_seller_products-$id", 0, function () use ($id) {
            $product = Product::find($id);
            $products = Product::where('user_id', $product->user_id)->orderBy('num_of_sale', 'desc')->physical();

            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }


    public function search(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;


        $products = Product::query();

        $products->where('published', 1)->physical();

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($query) use ($word) {
                        $query->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
            SearchUtility::store($name);
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }

        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        return new ProductMiniCollection(filter_products($products)->paginate(10));
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color') && $request->color != "") {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }

        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);

        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }


        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        $stockQuantity = $product_stock->qty;


        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }



        return response()->json([
            'product_id' => $product->id,
            'variant' => $str,
            'price' => (float)convert_price($price),
            'price_string' => format_price(convert_price($price)),
            'stock' => intval($stockQuantity),
            'image' => $product_stock->image == null ? "" : api_asset($product_stock->image)
        ]);
    }

    public function home()
    {
        return new ProductCollection(Product::inRandomOrder()->physical()->take(50)->get());
    }
}

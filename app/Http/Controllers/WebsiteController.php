<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\HeaderMenus;
use App\Models\Popup;
use App\Models\Prescriptions;
use App\Models\Frontend\HomeSlider;
use Cache;
use Harimayco\Menu\Models\MenuItems;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{

	public function popup(){
		$popup =  Popup::latest()->first();
		
		return view('backend.app.popup', compact('popup'));
	}

	public function popupUpdate(Request $request){
		$request->validate([
            'image' => 'required',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ], [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

		
		$popup = Popup::find($request->popup_id);

		$popup->image = $request->image;
		$popup->link_type = $request->link_type;
		$popup->link_ref = $request->link_ref_id;
		$popup->link = $request->link;
		$popup->status = $request->status;
		$popup->save();
	

        flash(translate('Popup content updated successfully'))->success();
        return redirect()->route('popup.index');
	}
	public function header(Request $request)
	{
		$menus = HeaderMenus::orderBy('id','asc')->get();
		$categories = Category::select('id','name')->where('parent_id',0)->where('is_active', 1)->orderBy('name', 'ASC')->get();
		$brands =  Brand::select('id','name')->orderBy('name','asc')->where('is_active', 1)->get();
		return view('backend.website_settings.web_header',compact('categories','brands','menus'));
	}

	public function storeHeader(Request $request)
	{
		$categories = $request->category;
		$brands = $request->brands;
		$data = [];
		HeaderMenus::truncate();
		foreach($categories as $key => $categ){
			if($categ != ''){
				$data[] = array(
					'category_id' => $categ,
					'brands' => json_encode($brands[$key]),
					'created_at' => date('Y-m-d H:i:s')
					);
			}
		}
		HeaderMenus::insert($data);
		Cache::forget('header_menus');
		Cache::forget('header_brands');
		flash(translate('Header menus updated successfully'))->success();
        return back();
	}

	public function footer(Request $request)
	{
		$lang = $request->lang;
		return view('backend.website_settings.footer', compact('lang'));
	}
	public function pages(Request $request)
	{
		return view('backend.website_settings.pages.index');
	}
	public function appearance(Request $request)
	{
		return view('backend.website_settings.appearance');
	}
	public function menu(Request $request)
	{
		return view('backend.website_settings.menu');
	}

	public function menuUpdate(Request $request)
	{
		// return response()->json(  , 200);

		$brands = NULL;
		if ($request->brands) {
			$brands = implode(',', $request->brands);
		}

		MenuItems::where('id', $request->id)->update([
			'img_1' => $request->img_1,
			'img_2' => $request->img_2,
			'img_3' => $request->img_3,

			'img_1_link' => $request->img_1_link,
			'img_2_link' => $request->img_2_link,
			'img_3_link' => $request->img_3_link,

			'brands' => $brands
		]);


		Cache::forget('menu_' . $request->menu_id);

		return response()->json('completed', 200);
	}

	public function prescriptions(){
		
		$prescription = Prescriptions::with(['user'])->orderBy('id','desc')->paginate(15);
		return view('backend.website_settings.prescriptions',compact('prescription'));
	}
}

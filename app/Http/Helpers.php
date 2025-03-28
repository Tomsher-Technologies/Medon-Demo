<?php

use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\CommissionController;
use App\Models\Currency;
use App\Models\BusinessSetting;
use App\Models\ProductStock;
use App\Models\Address;
use App\Models\State;
use App\Models\Country;
use App\Models\CustomerPackage;
use App\Models\Upload;
use App\Models\Translation;
use App\Models\City;
use App\Utility\CategoryUtility;
use App\Models\Wallet;
use App\Models\CombinedOrder;
use App\Models\User;
use App\Models\Order;
use App\Models\Offers;
use App\Models\Addon;
use App\Models\Shops;
use App\Models\OrderDeliveryBoys;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Products\ProductEnquiries;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Wishlist;
use App\Models\RefundRequest;
use App\Utility\SendSMSUtility;
use App\Utility\NotificationUtility;
use App\Http\Resources\V2\WebHomeBrandCollection;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\RawMessageFromArray;
use Kreait\Firebase\Contract\Messaging;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Google\Client as Google_Client;
// use DB;

use Harimayco\Menu\Facades\Menu;

//sensSMS function for OTP
if (!function_exists('sendSMS')) {
    function sendSMS($to, $from, $text, $template_id)
    {
        return SendSMSUtility::sendSMS($to, $from, $text, $template_id);
    }
}

//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        return in_array(Route::currentRouteName(), $routes) ? $output : '';
        // foreach ($routes as $route) {
        //     return Route::currentRouteName() == $route ? $output : '';
        // }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('default_language')) {
    function default_language()
    {
        return env("DEFAULT_LANGUAGE");
    }
}

/**
 * Save JSON File
 * @return Response
 */
if (!function_exists('convert_to_usd')) {
    function convert_to_usd($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'USD')->first()->exchange_rate;
    }
}

if (!function_exists('convert_to_kes')) {
    function convert_to_kes($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'KES')->first()->exchange_rate;
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            return $products->where('approved', '1')->where('published', '1')->where('auction_product', 0)->orderBy('created_at', 'desc')->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('published', '1')->where('auction_product', 0)->where('added_by', 'admin');
        }
    }
}

//cache products based on category
if (!function_exists('get_cached_products')) {
    function get_cached_products($category_id = null)
    {
        $products = \App\Models\Product::where('published', 1)->where('approved', '1')->where('auction_product', 0);
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            $products = $products->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            $products = $products->where('added_by', 'admin');
        }

        if ($category_id != null) {
            return Cache::remember('products-category-' . $category_id, 86400, function () use ($category_id, $products) {
                $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;
                return $products->whereIn('category_id', $category_ids)->latest()->take(12)->get();
            });
        } else {
            return Cache::remember('products', 86400, function () use ($products) {
                return $products->latest()->take(12)->get();
            });
        }
    }
}

if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return Cache::rememberForever('verified_sellers_id', function () {
            return App\Models\Seller::where('verification_status', 1)->pluck('user_id')->toArray();
        });
    }
}

if (!function_exists('get_system_default_currency')) {
    function get_system_default_currency()
    {
        return Cache::remember('system_default_currency', 86400, function () {
            return Currency::findOrFail(get_setting('system_default_currency'));
        });
    }
}

//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        if (Session::has('currency_code') && (Session::get('currency_code') != get_system_default_currency()->code)) {
            $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
            $price = floatval($price) * floatval(Session::get('currency_exchange_rate'));
        }
        return $price;
    }
}

//gets currency symbol
if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        if (Session::has('currency_symbol')) {
            return Session::get('currency_symbol');
        }
        return get_system_default_currency()->symbol;
    }
}

//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        if (get_setting('decimal_separator') == 1) {
            $fomated_price = number_format($price, get_setting('no_of_decimals'));
        } else {
            $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', ' ');
        }

        if (get_setting('symbol_format') == 1) {
            return currency_symbol() . $fomated_price;
        } else if (get_setting('symbol_format') == 3) {
            return currency_symbol() . ' ' . $fomated_price;
        } else if (get_setting('symbol_format') == 4) {
            return $fomated_price . ' ' . currency_symbol();
        }
        return $fomated_price . currency_symbol();
    }
}

//formats currency
if (!function_exists('format_price_wo_currency')) {
    function format_price_wo_currency($price)
    {
        if (get_setting('decimal_separator') == 1) {
            $fomated_price = number_format($price, get_setting('no_of_decimals'));
        } else {
            $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', ' ');
        }

        return $fomated_price;
    }
}

//formats price to home default price with convertion
if (!function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}

if (!function_exists('discount_in_percentage')) {
    function discount_in_percentage($product)
    {
        try {
            $base = home_base_price($product, false);
            $reduced = home_discounted_base_price($product, false);
            $discount = $base - $reduced;

            if ($base > 0) {
                $dp = ($discount * 100) / $base;
                return round($dp);
            }
        } catch (Exception $e) {
            return 0;
        }
        return 0;
    }
}

//Shows Price on page based on low to high
if (!function_exists('home_price')) {
    function home_price($product, $formatted = true)
    {
        $lowest_price = 0;
        $highest_price = 0;

        if ($product->variant_product) {

            $lowest_price = $product->stocks->max('price');
            $highest_price = $product->stocks->max('price');

            foreach ($product->stocks as $key => $stock) {
                if ($stock->price > 0 && $lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//Shows Price on page based on low to high with discount
if (!function_exists('home_discounted_price')) {
    function home_discounted_price($product, $formatted = true)
    {
        $lowest_price = $product->stocks->min('price');
        $highest_price = $product->stocks->max('price');

        // if ($product->variant_product) {
        //     foreach ($product->stocks as $key => $stock) {
        //         if ($lowest_price > $stock->price) {
        //             $lowest_price = $stock->price;
        //         }
        //         if ($highest_price < $stock->price) {
        //             $highest_price = $stock->price;
        //         }
        //     }
        // }

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
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//Shows Base Price
if (!function_exists('home_base_price_by_stock_id')) {
    function home_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $price = $product_stock->price;
        $tax = 0;

        foreach ($product_stock->product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return format_price(convert_price($price));
    }
}
if (!function_exists('home_base_price')) {
    function home_base_price($product, $formatted = true)
    {
        $price = $product->stocks->min('price');
        return $formatted ? format_price(convert_price($price)) : $price;
    }
}

if (!function_exists('home_base_price_wo_currency')) {
    function home_base_price_wo_currency($product, $formatted = true)
    {
        $price = $product->stocks->min('price');
        return $formatted ? format_price_wo_currency(convert_price($price)) : $price;
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price_by_stock_id')) {
    function home_discounted_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $product = $product_stock->product;
        $price = $product_stock->price;
        $tax = 0;

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

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;

        return format_price(convert_price($price));
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price_wo_currency')) {
    function home_discounted_base_price_wo_currency($product, $formatted = true)
    {
        $price = $product->stocks->min('price');
        $tax = 0;

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

        // foreach ($product->taxes as $product_tax) {
        //     if ($product_tax->tax_type == 'percent') {
        //         $tax += ($price * $product_tax->tax) / 100;
        //     } elseif ($product_tax->tax_type == 'amount') {
        //         $tax += $product_tax->tax;
        //     }
        // }
        // $price += $tax;

        return $formatted ? format_price_wo_currency(convert_price($price)) : $price;
    }
}


//Shows Base Price with discount
if (!function_exists('home_discounted_base_price')) {
    function home_discounted_base_price($product, $formatted = true)
    {
        $price = $product->stocks->min('price');
        $tax = 0;

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

        // foreach ($product->taxes as $product_tax) {
        //     if ($product_tax->tax_type == 'percent') {
        //         $tax += ($price * $product_tax->tax) / 100;
        //     } elseif ($product_tax->tax_type == 'amount') {
        //         $tax += $product_tax->tax;
        //     }
        // }
        // $price += $tax;

        return $formatted ? format_price(convert_price($price)) : $price;
    }
}

if (!function_exists('renderStarRating')) {
    function renderStarRating($rating)
    {
        if ($rating == 0) {
            return null;
        }

        $html = '<div class="ps-product__rating"><select class="ps-rating" data-read-only="true">';
        for ($i = 1; $i <= 5; $i++) {
            $value = $i <= $rating ? 1 : 2;
            $html .= '<option value="' . $value . '">' . $i . '</option>';
        }
        $html .=  '</select><span>' . $rating . '</span></div>';
        echo $html;
    }
}

function translate($key, $lang = null, $addslashes = false)
{
    return $key;
    if ($lang == null) {
        $lang = App::getLocale();
    }

    $lang_key = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($key)));

    $translations_en = Cache::rememberForever('translations-en', function () {
        return Translation::where('lang', 'en')->pluck('lang_value', 'lang_key')->toArray();
    });

    if (!isset($translations_en[$lang_key])) {
        $translation_def = new Translation;
        $translation_def->lang = 'en';
        $translation_def->lang_key = $lang_key;
        $translation_def->lang_value = str_replace(array("\r", "\n", "\r\n"), "", $key);
        $translation_def->save();
        Cache::forget('translations-en');
    }

    // return user session lang
    $translation_locale = Cache::rememberForever("translations-{$lang}", function () use ($lang) {
        return Translation::where('lang', $lang)->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translation_locale[$lang_key])) {
        return trim($translation_locale[$lang_key]);
    }

    // return default lang if session lang not found
    $translations_default = Cache::rememberForever('translations-' . env('DEFAULT_LANGUAGE', 'en'), function () {
        return Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translations_default[$lang_key])) {
        return trim($translations_default[$lang_key]);
    }

    // fallback to en lang
    if (!isset($translations_en[$lang_key])) {
        return trim($key);
    }
    return trim($translations_en[$lang_key]);
}

function remove_invalid_charcaters($str)
{
    $str = str_ireplace(array("\\"), '', $str);
    return str_ireplace(array('"'), '\"', $str);
}

function getShippingCost2($carts, $index)
{
    $admin_products = array();
    $seller_products = array();

    $cartItem = $carts[$index];
    $product = Product::find($cartItem['product_id']);

    if ($product->digital == 1) {
        return 0;
    }

    foreach ($carts as $key => $cart_item) {
        $item_product = Product::find($cart_item['product_id']);
        if ($item_product->added_by == 'admin') {
            array_push($admin_products, $cart_item['product_id']);
        } else {
            $product_ids = array();
            if (isset($seller_products[$item_product->user_id])) {
                $product_ids = $seller_products[$item_product->user_id];
            }
            array_push($product_ids, $cart_item['product_id']);
            $seller_products[$item_product->user_id] = $product_ids;
        }
    }

    if (get_setting('shipping_type') == 'flat_rate') {
        return get_setting('flat_rate_shipping_cost') / count($carts);
    } elseif (get_setting('shipping_type') == 'seller_wise_shipping') {
        if ($product->added_by == 'admin') {
            return get_setting('shipping_cost_admin') / count($admin_products);
        } else {
            return Shop::where('user_id', $product->user_id)
                ->first()
                ->shipping_cost / count($seller_products[$product->user_id]);
        }
    } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
        $shippingInfo = Address::where('id', $carts[0]['address_id'])->first();
        $city = City::where('id', $shippingInfo->city_id)->first();
        if ($city != null) {
            if ($product->added_by == 'admin') {
                return $city->cost / count($admin_products);
            } else {
                return $city->cost / count($seller_products[$product->user_id]);
            }
        }
        return 0;
    } else {
        if ($product->is_quantity_multiplied && get_setting('shipping_type') == 'product_wise_shipping') {
            return  $product->shipping_cost * $cartItem['quantity'];
        }
        return $product->shipping_cost;
    }
}

function timezones()
{
    return Timezones::timezonesToArray();
}

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}

if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        if (($asset = Upload::find($id)) != null) {
            return 'storage/' . $asset->file_name;
        }
        return "";
    }
}

if (!function_exists('api_upload_asset')) {
    function api_upload_asset($id)
    {
        if (($asset = Upload::find($id)) != null) {
            return app('url')->asset('storage/' . $asset->file_name);
        }
        return app('url')->asset('admin_assets/assets/img/placeholder.jpg');
    }
}

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if ($id && ($asset = \App\Models\Upload::find($id)) != null) {
            return $asset->external_link == null ? storage_asset($asset->file_name) : $asset->external_link;
        }

        return app('url')->asset('admin_assets/assets/img/placeholder.jpg');
    }
}

//return file uploaded via uploader with name
if (!function_exists('uploaded_asset_with_name')) {
    function uploaded_asset_with_name($id)
    {
        if ($id && ($asset = \App\Models\Upload::find($id)) != null) {
            return array(
                'link' => $asset->external_link == null ? storage_asset($asset->file_name) : $asset->external_link,
                'name' => $asset->file_original_name
            );
        }

        return null;
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            return app('url')->asset('public/' . $path, $secure);
        }
    }
}

if (!function_exists('storage_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function storage_asset($path, $secure = null)
    {
        return app('url')->asset('storage/' . $path, $secure);
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        return app('url')->asset('admin_assets/' . $path, $secure);
    }
}

if (!function_exists('frontendAsset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function frontendAsset($path, $secure = null)
    {
        return app('url')->asset('assets/' . $path, $secure);
    }
}


// if (!function_exists('isHttps')) {
//     function isHttps()
//     {
//         return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
//     }
// }

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = '//' . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}


if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } else {
            return app('url')->asset('storage') . '/';
            // return getBaseURL();
        }
    }
}


if (!function_exists('isUnique')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function isUnique($email)
    {
        $user = \App\Models\User::where('email', $email)->first();

        if ($user == null) {
            return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
        } else {
            return '0';
        }
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $settings = Cache::remember('business_settings', 86400, function () {
            return BusinessSetting::select(['type', 'value'])->get()->keyBy('type')->toArray();
        });

        if (isset($settings[$key])) {
            return $settings[$key]['value'];
        }

        return $default;
    }
}

function hex2rgba($color, $opacity = false)
{
    return Colorcodeconverter::convertHexToRgba($color, $opacity);
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isSeller')) {
    function isSeller()
    {
        if (Auth::check() && Auth::user()->user_type == 'seller') {
            return true;
        }
        return false;
    }
}

if (!function_exists('isCustomer')) {
    function isCustomer()
    {
        if (Auth::check() && Auth::user()->user_type == 'customer') {
            return true;
        }
        return false;
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

// duplicates m$ excel's ceiling function
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

if (!function_exists('get_images')) {
    function get_images($given_ids, $with_trashed = false)
    {
        if (is_array($given_ids)) {
            $ids = $given_ids;
        } elseif ($given_ids == null) {
            $ids = [];
        } else {
            $ids = explode(",", $given_ids);
        }


        return $with_trashed
            ? Upload::withTrashed()->whereIn('id', $ids)->get()
            : Upload::whereIn('id', $ids)->get();
    }
}

//for api
if (!function_exists('get_images_path')) {
    function get_images_path($given_ids, $with_trashed = false)
    {
        $paths = [];
        $images = get_images($given_ids, $with_trashed);
        if (!$images->isEmpty()) {
            foreach ($images as $image) {
                $paths[] = !is_null($image) ? $image->file_name : "";
            }
        }

        return $paths;
    }
}

//for api
if (!function_exists('checkout_done')) {
    function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::find($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            try {
                NotificationUtility::sendOrderPlacedNotification($order);
                // calculateCommissionAffilationClubPoint($order);
            } catch (\Exception $e) {
                // Do nothing
            }
        }
    }
}

//for api
if (!function_exists('wallet_payment_done')) {
    function wallet_payment_done($user_id, $amount, $payment_method, $payment_details)
    {
        $user = \App\Models\User::find($user_id);
        $user->balance = $user->balance + $amount;
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $amount;
        $wallet->payment_method = $payment_method;
        $wallet->payment_details = $payment_details;
        $wallet->save();
    }
}

if (!function_exists('purchase_payment_done')) {
    function purchase_payment_done($user_id, $package_id)
    {
        $user = User::findOrFail($user_id);
        $user->customer_package_id = $package_id;
        $customer_package = CustomerPackage::findOrFail($package_id);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();

        return 'success';
    }
}

//Commission Calculation
if (!function_exists('calculateCommissionAffilationClubPoint')) {
    function calculateCommissionAffilationClubPoint($order)
    {
        (new CommissionController)->calculateCommission($order);

        if (addon_is_activated('affiliate_system')) {
            (new AffiliateController)->processAffiliatePoints($order);
        }

        if (addon_is_activated('club_point')) {
            if ($order->user != null) {
                (new ClubPointController)->processClubPoints($order);
            }
        }

        $order->commission_calculated = 1;
        $order->save();
    }
}

// Addon Activation Check
if (!function_exists('addon_is_activated')) {
    function addon_is_activated($identifier, $default = null)
    {
        return false;
    }
}

// Get Image From Uploads
if (!function_exists('get_uploads_image')) {
    function get_uploads_image($relation)
    {
        if ($relation) {
            return storage_asset($relation->file_name);
        }

        return frontendAsset('img/placeholder.webp');
    }
}

// Get Image From Uploads
if (!function_exists('get_product_image')) {
    function get_product_image($path, $size = 'full')
    {
        if ($path) {
            if ($size == 'full') {
                return app('url')->asset($path);
            } else {
                $fileName = pathinfo($path)['filename'];
                $ext   = pathinfo($path)['extension'];
                $dirname   = pathinfo($path)['dirname'];
                $r_path = "{$dirname}/" . $fileName . "_{$size}px" . ".{$ext}";
                return app('url')->asset($r_path);
            }
        }

        return app('url')->asset('admin_assets/assets/img/placeholder.jpg');
    }
}

// Load SEO details
if (!function_exists('load_seo_tags')) {
    function load_seo_tags($seo = null, $image = '', $type = 'articles')
    {
        if ($image == '') {
            $image = frontendAsset('img/logo_new.webp');
        }

        if ($seo) {
            SEOMeta::setTitle($seo->meta_title);
            SEOMeta::setDescription($seo->meta_description);
            SEOMeta::setKeywords($seo->meta_keywords);

            OpenGraph::setTitle($seo->og_title);
            OpenGraph::setDescription($seo->og_title);

            OpenGraph::addProperty('type', $type)
                ->addImage($image)
                ->setTitle($seo->og_title)
                ->setDescription($seo->og_description)
                ->setSiteName(env('APP_NAME', 'Medon'));

            TwitterCard::setType('summary_large_image')
                ->setImage($image)
                ->setTitle($seo->twitter_title)
                ->setDescription($seo->twitter_description)
                ->setSite('@ind');

            JsonLd::setImage($image)
                ->setTitle($seo->meta_title)
                ->setDescription($seo->meta_description)
                ->setSite(env('APP_NAME', 'Medon'));

            JsonLdMulti::setImage($image)
                ->setTitle($seo->meta_title)
                ->setDescription($seo->meta_description)
                ->setSite(env('APP_NAME', 'Medon'));
        }
    }
}

function getTempUserId()
{
    if (Session::has('temp_user_id')) {
        $user_id = Session::get('temp_user_id');
    } else {
        $user_id = bin2hex(random_bytes(10));
        Session::put('temp_user_id', $user_id);
    }
    return $user_id;
}

function getAllCategories()
{
    return Cache::rememberForever('categoriesTree', function () {
        return CategoryUtility::getSidebarCategoryTree();
    });
}

function wishListCount(): int
{
    if (Auth::check()) {
        return Cache::remember('user_wishlist_count_' . Auth::id(), '3600', function () {
            return Wishlist::where('user_id', Auth::user()->id)->count();
        });
    }

    return 0;
}

function userWishlistCount($user_id){
    if($user_id != ''){
        return Wishlist::where('user_id',$user_id)->count();
    }
    return 0;
}

function userOrdersCount($user_id){
    if($user_id != ''){
        return Order::where('order_success', 1)->where('user_id', $user_id)->count();
    }
    return 0;
}

function userPendingOrders($user_id){
    if($user_id != ''){
        return Order::where('order_success', 1)->where('delivery_status','!=','delivered')->where('user_id', $user_id)->count();
    }
    return 0;
}

function cartCount(): int
{
    if (Auth::check()) {
        return Cache::remember('user_cart_count_' . Auth::id(), '3600', function () {
            return Cart::where('user_id', Auth::user()->id)->count();
        });
    } else {
        return Cache::remember('user_cart_count_' . getTempUserId(), '3600', function () {
            return Cart::where('temp_user_id', getTempUserId())->count();
        });
    }
}

function enquiryCount(): int
{
    if (Auth::check()) {
        $user_col = "user_id";
        $user_id = Auth::id();
    } else {
        $user_col = "temp_user_id";
        $user_id = getTempUserId();
    }

    return Cache::remember('user_enquiry_count_' . $user_id, '3600', function () use ($user_col, $user_id) {
        $enquiries = ProductEnquiries::whereStatus(0)->where($user_col, $user_id)->withCount('products')->latest()->first();
        if ($enquiries) {
            return $enquiries->products_count;
        }
        return 0;
    });
}

function formatDate($date): String
{
    if ($date->lessThan(Carbon::now()->subHours(12))) {
        return $date->format('d F, Y');
    }
    return $date->diffForHumans();
}

function deliveryBadge($status)
{
    $html = '';

    switch ($status) {
        case 'pending':
            $html = '<span class="badge badge badge-soft-danger">Pending</span>';
            break;
        case 'confirmed':
            $html = '<span class="badge badge-soft-warning">Confirmed</span>';
            break;
        case 'picked_up':
            $html = '<span class="badge badge-soft-warning">Picked Up</span>';
            break;
        case 'on_the_way':
            $html = '<span class="badge badge-soft-warning">On The Way</span>';
            break;
        case 'delivered':
            $html = '<span class="badge badge-soft-success">Delivered</span>';
            break;
        default:
            $html = '-';
            break;
    }

    return $html;
}

function getDeliveryStatusText($status)
{
    return Str::title(str_replace('_', ' ', $status));
}

function getCurrentCurrency()
{
    if (Session::has('currency_code')) {
        return Currency::where('code', Session::get('currency_code'))->first();
    } else {
        return Currency::find(get_setting('system_default_currency'));
    }
}

function getMenu($id)
{
    // Cache::forget('menu_6');
    return Cache::rememberForever('menu_' . $id,  function () use ($id) {
        $menu = Menu::get($id);
        $menu_real = array();
        foreach ($menu as $key => $m) {
            $menu_real[$key] = $m;
            if ($m['img_1']) {
                $menu_real[$key]['img_1_src'] = uploaded_asset($m['img_1']);
            }
            if ($m['img_2']) {
                $menu_real[$key]['img_2_src'] = uploaded_asset($m['img_2']);
            }
            if ($m['img_3']) {
                $menu_real[$key]['img_3_src'] = uploaded_asset($m['img_3']);
            }

            if ($m['brands'] !== null) {
                $brand_ids = explode(',', $m['brands']);
                $brands = Brand::whereIn('id', $brand_ids)->select(['id', 'name', 'logo', 'slug'])->with('logoImage', function ($query) {
                    return $query->select(['id', 'file_name']);
                })->get();

                $menu_real[$key]['brands'] = $brands;
            }
        }
        return $menu_real;
    });
}

function allProducts()
{
    return Product::wherePublished(1)->latest()->get();
}

function getCurrency()
{
    return Cache::rememberForever('currency', function () {
        return Currency::where('status', 1)->get();
    });
}

function getSeoValues($seo, $name)
{
    if ($name && $seo) {
        return $seo->$name;
    }
    return "";
}

function deleteImage($path)
{
    $fileName = 'public' . Str::remove('/storage', $path);
    if (Storage::exists($fileName)) {
        Storage::delete($fileName);
    }
}

function cleanSKU($sku)
{
    $sku = str_replace(' ', '', $sku);
    $sku = preg_replace('/[^a-zA-Z0-9_-]/', '', $sku);
    return $sku;
}

function userHasPermision($id)
{
    if (Auth::user()->user_type == 'admin' || in_array($id, json_decode(Auth::user()->staff->role->permissions))) {
        return true;
    }
    return false;
}

function getActiveShops(){
    $shops = Shops::where('status',1)->orderBy('name','ASC')->get();
    return $shops;
}

function allAttributes()
{
    return Cache::rememberForever('attributes', function () {
        return Attribute::all();
    });
}

function hasStock($product)
{
    return $product->stocks->max('qty') > 0 ? true : false;
}

// function testView()
// {
//     Cache::forget('awesomeHtml');
//     $html = Cache::remember('awesomeHtml', 3600, function () {
//         return view('frontend.inc.header-part.desktop-header')->render();
//     });

//     return $html;
// }

function canReview($product_id, $user_id)
{
    $res = [
        'can_comment' => false,
        'has_comment' => false,
    ];

    if ($user_id && $product_id) {
        $review_count = Review::where('user_id', $user_id)
            ->where('product_id', $product_id)->count();

        $purchases_count = OrderDetail::where([
            'product_id' => $product_id,
            'delivery_status' => 'delivered',
        ])->whereHas('order', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->count();

        if ($purchases_count > 0) {
            if ($review_count == 0) {
                $res['can_comment'] = true;
            } else {
                $res['can_comment'] = false;
                $res['has_comment'] = true;
            }
        }
    }
    return $res;
}

function getAdminEmail()
{
    $emails = Cache::rememberForever('admin_emails', function () {
        return BusinessSetting::where('type', 'admin_emails')->first();
    });

    if ($emails) {
        return explode(',', $emails->value);
    } else if (env('ADMIN_EMAIL')) {
        return env('ADMIN_EMAIL');
    } else {
        return 'info@tomsher.com';
    }
}


function whishlistHasProduct($product_id)
{
    if (Auth::check()) {
        if (session()->has('whishlist_' . Auth::user()->id)) {
            if (in_array($product_id, session()->get('whishlist_' . Auth::user()->id))) {
                return true;
            }
        }
    }
    return false;
}

function getUser()
{

    $user = array(
        'users_id_type' => 'temp_user_id',
        'users_id' => null
    );

    if (auth('sanctum')->user()) {
        $user = array(
            'users_id_type' => 'user_id',
            'users_id' => auth('sanctum')->user()->id
        );
    } else {
        $user = array(
            'users_id_type' => 'temp_user_id',
            'users_id' => request()->header('UserToken')
        );
    }

    return $user;
}


function checkProductOffer($product){
    // echo '<pre>';
    // print_r($product);
    // die;
    // DB::enableQueryLog();
    $prodOffer = Offers::whereJsonContains('link_id', (string) $product->id)
                        ->whereRaw('(now() between start_date and end_date)')
                        ->where('link_type', 'product')->orderBy('id','desc')->skip(0)->take(1)->get();
    // print_r($prodOffer);
    if(empty($prodOffer[0])){
        // echo 'no product offer';
        $brandOffer = Offers::whereJsonContains('link_id', (string) $product->brand_id)
                        ->whereRaw('(now() between start_date and end_date)')
                        ->where('category_id', $product->main_category)
                        ->where('link_type', 'category')->orderBy('id','desc')->skip(0)->take(1)->get();
    }else{
        // echo 'product offer';
    }

    // die;
    // dd(DB::getQueryLog());
}

function getImmediateSubCategories($id){
    // Cache::forget('header_submenus');
    return Category::select('id','name','slug')->where('parent_id', $id)->where('is_active', 1)->get();
}

function getHeaderCategoryBrands($ids){
    $brands = Brand::whereIn('id', json_decode($ids))->get();
    return new WebHomeBrandCollection($brands);
}

function getOffersProductIds($offerSlugs, $isId = 1){
    if($isId == 1){
        $offers = Offers::whereIn('id',$offerSlugs)->select('category_id','link_type','link_id')->get()->toArray();
    }else{
        $offers = Offers::whereIn('slug',$offerSlugs)->select('category_id','link_type','link_id')->get()->toArray();
    }

    $products = [];
    if($offers){
        foreach($offers as $off){
            $type = $off['link_type'];
            if($type == 'product'){
                $products[] = json_decode($off['link_id']);
            }elseif($type == 'category'){
                $products[] = Product::where('main_category', $off['category_id'])->whereIn('brand_id', json_decode($off['link_id']))->pluck('id')->toArray();
            }
        }
       
        if(!empty($products)){
            $products = array_merge(...$products);
        }
    }
   
    return $products;
}

function getSidebarCategoryTree()
{
    $all_cats = Category::select([
        'id',
        'parent_id',
        'name',
        'level',
        'slug',
        'icon'
    ])->with(['child','iconImage'])->withCount('products')->where('parent_id', 0)->where('is_active', 1)->orderBy('categories.name','ASC')->get();
    // ])->withCount('products')->where('parent_id', 0)->where('is_active', 1)->orderBy('categories.name','ASC')->get();
    foreach( $all_cats as $categ){
        $categ->icon = ($categ->iconImage) ? (($categ->iconImage->file_name) ? storage_asset($categ->iconImage->file_name) : app('url')->asset('admin_assets/assets/img/placeholder.jpg')) : app('url')->asset('admin_assets/assets/img/placeholder.jpg');
        unset($categ->iconImage);
    }

    return $all_cats;
}


function getProductIdFromSlug($slug){
    if($slug != null){
        $product = Product::where('slug', $slug)->pluck('id')->first();
        return $product;
    }
    return null;
}
function getProductIdsFromMultipleSlug($slug){
    if($slug != null){
        $product = Product::whereIn('slug', $slug)->pluck('id')->toArray();
        return $product;
    }
    return null;
}


function getCountryId($countryid){
    $country = Country::where('name','LIKE','%'.$countryid.'%')->pluck('id')->toArray();
    if(!empty($country)){
        return $country[0];
    }else{
        return NULL;
    }
}

function getStateId($stateid){
    $state = State::where('name','LIKE','%'.$stateid.'%')->pluck('id')->toArray();
    if(!empty($state)){
        return $state[0];
    }else{
        return NULL;
    }
}

function userDefaultAddress($user_id){
    if($user_id != ''){
        $data = Address::where('user_id', $user_id)->where('set_default',1)->first();
        $address = [];
        if($data){
            $address = [
                'id'      =>(int) $data->id,
                'user_id' =>(int) $data->user_id,
                'type' => $data->type,
                'name' => $data->name,
                'address' => $data->address,
                'country_id' => (int)  $data->country_id,
                'state_id' =>  (int) $data->state_id,                  
                'country' => ($data->country_id != NULL) ? $data->country->name : $data->country_name,
                'state' => ($data->state_id != NULL) ? $data->state->name : $data->state_name,
                'city' => $data->city,
                'postal_code' => $data->postal_code,
                'phone' => $data->phone,
                'set_default' =>(int) $data->set_default,
                'lat' => $data->latitude,
                'lang' => $data->longitude,
            ];
        }
        return $address;
    }
    return array();
}

function getOfferTag($offer){
    $tag = '';
    $offer_type = $offer->offer_type;
    if($offer_type == 'percentage'){
        $tag = $offer->percentage.'% OFF';
    }elseif($offer_type == 'amount_off'){
        $tag = 'AED '.$offer->offer_amount.' OFF';
    }elseif($offer_type == 'buy_x_get_y'){
        $tag = 'BUY '.$offer->buy_amount.' GET '.$offer->get_amount;
    }
    return  $tag;
}

function getProductOfferPrice($product){

    $data["original_price"] = $product->unit_price + ($product->unit_price * ($product->vat/100));
   

    $discountPrice = $product->unit_price + ($product->unit_price * ($product->vat/100));
    
    // $allOffers = Offers::whereRaw('(now() between start_date and end_date)')->where('status',1)->get();

    // print_r($data);

    $offertag = $offer_type = $offer_id = '';
    $x = $y = 0;

    // die;
    // DB::enableQueryLog();
    $prodOffer = Offers::whereJsonContains('link_id', (string) $product->id)
                        ->whereRaw('(now() between start_date and end_date)')
                        ->where('link_type', 'product')->orderBy('id','desc')->skip(0)->take(1)->get();

    if(empty($prodOffer[0])){
        // echo 'no product offer';
        $brandOffer = Offers::whereJsonContains('link_id', (string) $product->brand_id)
                        ->whereRaw('(now() between start_date and end_date)')
                        ->where('category_id', $product->main_category)
                        ->where('link_type', 'category')->orderBy('id','desc')->skip(0)->take(1)->get();
        // print_r($brandOffer);  die;
        if(empty($brandOffer[0])){
            $tax = 0;
    
            $discount_applicable = false;
            if($product->discount_start_date != NULL && $product->discount_end_date != NULL){
                if(strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                    $discount_applicable = true;
                }
            }
           
            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $discountPrice -= ($discountPrice * $product->discount) / 100;
                    $offertag = $product->discount.'% OFF';
                } elseif ($product->discount_type == 'amount') {
                    $discountPrice -= $product->discount;
                    $offertag = 'AED '.$product->discount.' OFF';
                }
                $offer_id = 0;
            }
            
        }else{
            $offer_id = $brandOffer[0]->id;
            $offer_type = $brandOffer[0]->offer_type;
            if($brandOffer[0]->offer_type == 'amount_off'){
                $discountPrice -= $brandOffer[0]->offer_amount;
                $offertag = 'AED '.$brandOffer[0]->offer_amount.' OFF';
            }elseif($brandOffer[0]->offer_type == 'percentage'){
                $discountPrice -= ($discountPrice * $brandOffer[0]->percentage)/100 ;
                $offertag = $brandOffer[0]->percentage.'% OFF';
            }elseif($brandOffer[0]->offer_type == 'buy_x_get_y'){
                $offertag = 'BUY '.$brandOffer[0]->buy_amount.' GET '.$brandOffer[0]->get_amount;
                $x = $brandOffer[0]->buy_amount;
                $y = $brandOffer[0]->get_amount;
            }
        }            
    }else{
        $offer_id = $prodOffer[0]->id;
        $offer_type = $prodOffer[0]->offer_type;
        if($prodOffer[0]->offer_type == 'amount_off'){
            $discountPrice -= $prodOffer[0]->offer_amount;
            $offertag = 'AED '.$prodOffer[0]->offer_amount.' OFF';
        }elseif($prodOffer[0]->offer_type == 'percentage'){
            $discountPrice -= ($discountPrice * $prodOffer[0]->percentage)/100 ;
            $offertag = $prodOffer[0]->percentage.'% OFF';
        }elseif($prodOffer[0]->offer_type == 'buy_x_get_y'){
            $offertag = 'BUY '.$prodOffer[0]->buy_amount.' GET '.$prodOffer[0]->get_amount;
            $x = $prodOffer[0]->buy_amount;
            $y = $prodOffer[0]->get_amount;
        }
    }
// echo '      Price After Discount = '.$discountPrice;
   
    $data["discounted_price"] = $discountPrice;
    $data["offer_tag"]  = $offertag;
    $data["offer_id"]   = $offer_id;
    $data["offer_type"] = $offer_type;
    $data["x"] = $x;
    $data["y"] = $y;

    // print_r($data);
    // die;
    // dd(DB::getQueryLog());
    return $data;
}

function getActiveBuyXgetYOfferProducts(){
    $offers = Offers::whereRaw('(now() between start_date and end_date)')->where('status',1)
                        ->where('offer_type', 'buy_x_get_y')->get();
    $offerProducts = [];
    if($offers){
        foreach($offers as $off){
            if($off->link_type == 'product'){
                $products = json_decode($off->link_id);
            }else{
                $products = Product::where('main_category', $off->category_id)->whereIn('brand_id', json_decode($off->link_id))->pluck('id')->toArray();
            }
            $offerProducts[$off->id]['products'] = $products;
            $offerProducts[$off->id]['x'] = $off->buy_amount;
            $offerProducts[$off->id]['y'] = $off->get_amount;
        }
    }
    return $offerProducts;
}

    function encryptCC($plainText,$key)
	{
		$key = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		$encryptedText = bin2hex($openMode);
		return $encryptedText;
	}

	function decryptCC($encryptedText,$key)
	{
		$key = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText = hextobin($encryptedText);
		$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		return $decryptedText;
	}

    //*********** Padding Function *********************

	 function pkcs5_pad ($plainText, $blockSize)
     {
         $pad = $blockSize - (strlen($plainText) % $blockSize);
         return $plainText . str_repeat(chr($pad), $pad);
     }
 
     //********** Hexadecimal to Binary function for php 4.0 version ********
 
     function hextobin($hexString) 
    { 
        $length = strlen($hexString); 
        $binString="";   
        $count=0; 
        while($count<$length) 
        {       
            $subString =substr($hexString,$count,2);           
            $packedString = pack("H*",$subString); 
            if ($count==0)
        {
            $binString=$packedString;
        } 
            
        else 
        {
            $binString.=$packedString;
        } 
            
        $count+=2; 
        } 
        return $binString; 
    } 

    function reduceProductQuantity($productQuantities){
        if(!empty($productQuantities)){
            foreach($productQuantities as $key => $value){
                $product_stock = ProductStock::where('product_id', $key)->first();
                $product_stock->qty -= $value;
                $product_stock->save();
            }
        }
    }

    function sendPushNotification($req){
      
        $messaging = app('firebase.messaging');
        $deviceTokens = $req['device_tokens'];
        // echo '<pre>';
        // print_r($deviceTokens);
        // die;
      
        $messages = [];
        $client = new Client();
        if(!empty($deviceTokens)){
            
            foreach ($deviceTokens as $dtoken) {
            
                try {
                    // $client = new Client();
                    $response = $client->post('https://fcm.googleapis.com/v1/projects/medonrider-e328c/messages:send', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . getAccessToken(),
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'message' => [
                                'token' => $dtoken,
                                'notification' => [
                                    'title' => $req['title'],
                                    'body' => $req['body'],
                                ],
                                'data' => [
                                    'key' => 'Value',
                                ]
                            ],
                        ],
                    ]);
                    // print_r( ['status' => 'success', 'message' => 'Notification sent successfully']);
                    // return ['status' => 'success', 'message' => 'Notification sent successfully'];
                } catch (\Exception $e) {
                    // print_r(['status' => 'error', 'message' => 'Failed to send notification: ' . $e->getMessage()]);
                    // return ['status' => 'error', 'message' => 'Failed to send notification: ' . $e->getMessage()];
                }
            
            }
            // die;
        }
        
        
        
        
    //   $messaging = app('firebase.messaging');
    //     $deviceTokens = $req['device_tokens'];
    //     $message = new RawMessageFromArray([
    //                     'notification' => [
    //                         // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#notification
    //                         'title' => $req['title'],
    //                         'body' => $req['body'],
    //                     ],
    //                     'data' => [
    //                         'key' => 'Value',
    //                     ]
    //                 ]);

    //     $sendReport = $messaging->sendMulticast($message, $deviceTokens);
        // echo 'Successful sends: '.$sendReport->successes()->count().PHP_EOL;
        // echo '<br>Failed sends: '.$sendReport->failures()->count().PHP_EOL;

        // if ($sendReport->hasFailures()) {
        //     foreach ($sendReport->failures()->getItems() as $failure) {
        //         echo '<br>'. $failure->error()->getMessage().PHP_EOL;
        //     }
        // 
    }

    function getAccessToken()
    {
        $credentialsPath = storage_path('app/medonrider-e328c-firebase-adminsdk-zf60y-67e038ed77.json'); // Path to your service account file
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
    
    function distanceCalculator($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
            
            if ($unit == "KM") {                                     // Kilometer - km
                return ($miles * 1.609344);
            } else if ($unit == "NM") {                              // Nautical Mile - nm
                return ($miles * 0.8684);
            } else if ($unit == "M") {                               // Meter - m 
                return ($miles * 1609.34);
            } else {                                                 // Mile - mi
                return $miles;
            }
        }
    }

    function checkDeliveryAssigned($order_id, $user_id){
        $count = OrderDeliveryBoys::where('order_id',$order_id)->where('delivery_boy_id', $user_id)->where('status',0)->count();
        return $count;
    }

    function checkReturnDeliveryAssigned($return_id, $user_id){
        $count = RefundRequest::where('id',$return_id)->where('delivery_boy', $user_id)->where('delivery_status',0)->count();
        return $count;
    }

    function getAssignedDeliveryBoy($order_id){
        $boy = OrderDeliveryBoys::with(['deliveryBoy'])->where('order_id',$order_id)->where('status',0)->first();
        return $boy->deliveryBoy->name ?? '';
    }

    function getDeliveryBoy($order_id){
        $boys = OrderDeliveryBoys::with(['deliveryBoy'])->where('order_id',$order_id)->where('status',1)->groupBy('delivery_boy_id')->get();
        return $boys;
    }

    function getOrderDeliveryDetails($order_id){
        $boys = OrderDeliveryBoys::with(['deliveryBoy'])->where('order_id',$order_id)->orderBy('id','asc')->get();
        return $boys;
    }

    function getDatePlusXDays($date, $days){
        $result = date("Y-m-d H:i:s", strtotime($date . "+".$days." days"));
        return $result;
    }

    function getShopDeliveryAgents($shop_id){
        $agents = [];
        if($shop_id){
            $agents = User::where('user_type', 'delivery_boy')->where('shop_id', $shop_id)->select('id','name')
                            ->orderBy('name','ASC')->get()->toArray();
        }
    
        return $agents;
    }

    function getOrderStatusMessage($user, $code){
        return [
            'order_placed' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been placed successfully.",

            'confirmed' => "Hi ".$user.",  Greetings from Medon Pharmacy! Order Is Confirmed. Order ID is #".$code."",
            
            'picked_up' => "Hi ".$user.", Greetings from Medon Pharmacy! Your Order is out for Delivery . Order ID is #.".$code."",
            
            'partial_pick_up' => "Hi ".$user.", Greetings from Medon Pharmacy! Your Order is out for Delivery . Order ID is #.".$code."",

            'cancelled' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been cancelled.",

            'cancel_reject' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") cancel request is rejected by admin.",

            'partial_delivery' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been delivered.",

            'delivered' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been delivered.",

            'order_assign' => "Hi ".$user.", Greetings from Medon Pharmacy! New order (".$code.") delivery has been assigned to you.",

            'return_assign' => "Hi ".$user.", Greetings from Medon Pharmacy! New return order (".$code.") delivery has been assigned to you.",
        ];

    }

    function getOrderStatusMessageTest($user, $code){
        return [
            'order_placed' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been placed successfully.",

            'confirmed' => "Hi ".$user.",  Greetings from Medon Pharmacy! Order Is Confirmed. Order ID is #".$code."",

            'picked_up' => "Hi ".$user.", Greetings from Medon Pharmacy! Your Order is out for Delivery . Order ID is #.".$code."",

            'partial_pick_up' => "Hi ".$user.", Greetings from Medon Pharmacy! Your Order is out for Delivery . Order ID is #.".$code."",

            'cancelled' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been cancelled.",

            'cancel_reject' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") cancel request is rejected by admin.",

            'partial_delivery' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been delivered.",

            'delivered' => "Hi ".$user.", Greetings from Medon Pharmacy! Your order (".$code.") has been delivered.",

            'order_assign' => "Hi ".$user.", Greetings from Medon Pharmacy! New order (".$code.") delivery has been assigned to you.",

            'return_assign' => "Hi ".$user.", Greetings from Medon Pharmacy! New return order (".$code.") delivery has been assigned to you.",
        ];

    }

    function calculateFreeItems($N, $X, $Y) {
        $floorDivision = floor($N / ($X + $Y));
        $remainder = $N % ($X + $Y);
    
        $totalX = $floorDivision * $X + min($remainder, $X);
        $totalY = $floorDivision * $Y + min($remainder - min($remainder, $X), $Y);

        return $totalY;
    }

    function getChildCategoryIds($parentId)
    {
        // Get the parent category
        $parentCategory = Category::find($parentId);

        // If the parent category doesn't exist, return an empty array or handle as needed
        if (!$parentCategory) {
            return [];
        }

        // Recursively get all child category IDs
        $childIds = getChildCategoryIdsRecursive($parentCategory);

        return $childIds;
    }

    function getChildCategoryIdsRecursive($category)
    {
        $childIds = [];

        foreach ($category->child as $child) {
            $childIds[] = $child->id;

            // Recursively get child category IDs for the current child
            $childIds = array_merge($childIds, getChildCategoryIdsRecursive($child));
        }

        return $childIds;
    }
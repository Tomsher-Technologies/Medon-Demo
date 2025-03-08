<?php

namespace App\Services;

use App\Models\Shops;
use App\Models\ShopNotifications;
use App\Models\Order;
use App\Models\User;
use App\Models\ShopAssignHistory;
use App\Mail\Admin\OrderAssign;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Http;
use Mail;

class NearestShopService
{
    public function assignNearestShop(Order $order, float $userLatitude, float $userLongitude, float $radiusInKm = 10): ?Shops
    {
        // 1. Pre-filter shops within the radius (10 km)
        $shops = Shops::nearby($userLatitude, $userLongitude, $radiusInKm)->get();

        if ($shops->isEmpty()) {
            return null; // No nearby shops
        }

        // Build destinations for Routes API (list of pharmacy lat/long pairs)
        $destinations = $shops->map(function ($shop) {
            return "{$shop->delivery_pickup_latitude},{$shop->delivery_pickup_longitude}";
        })->implode('|');

        // 3. Call Google Distance Matrix API
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => "{$userLatitude},{$userLongitude}",
            'destinations' => $destinations,
            'key' => env('GOOGLE_DISTANCE_MATRIX_KEY'),
        ]);

        // Google Routes API call
        // $response = Http::get('https://routes.googleapis.com/maps/api/routes/v1:computeRouteMatrix', [
        //     'origins' => "{$userLatitude},{$userLongitude}",
        //     'destinations' => $destinations,
        //     'key' => env('GOOGLE_DISTANCE_MATRIX_KEY'),
        // ]);

        if ($response->failed()) {
            return null; // Handle API failure gracefully
        }

        $data = $response->json();
        $nearestShop = null;

        // echo '<pre>';
        // print_r($data);
        // die;
        if(isset($data['rows'][0]['elements'])){
            $distances = $data['rows'][0]['elements'];

            // 4. Find the nearest shop by actual road distance
            
            $shortestDistance = PHP_INT_MAX;
            foreach ($distances as $index => $distanceInfo) {
                if ($distanceInfo['status'] === 'OK') {
                    $distanceValue = $distanceInfo['distance']['value']; // in meters
                    if ($distanceValue < $shortestDistance) {
                        $shortestDistance = $distanceValue;
                        $nearestShop = $shops[$index];
                    }
                }
            }
        }
       
        // 5. Assign the nearest shop to the order
        if ($nearestShop) {
            $shop_id = $nearestShop->id;
            $order->shop_id = $shop_id;
            $order->shop_assigned_date = date('Y-m-d');
            $order->save();

            ShopAssignHistory::create([
                'order_id' => $order->id,
                'from_shop_id' => NULL,
                'to_shop_id' => $shop_id
            ]);

            $shop = Shops::find($shop_id);
    
            $not = new ShopNotifications;
            $not->shop_id = $shop_id;
            $not->order_id = $order->id;
            $not->is_read = 0;
            $not->message ="A new order has been assigned. Order code : <b>".$order->code ?? ''."</b>";
            $not->type = 'order_assign';
            $not->save();

            Mail::to($shop->email)->queue(new OrderAssign($order));

            // Notify the staffs
            $staffsNot = User::where('user_type', 'staff')->where('shop_id', $shop_id)->get();  // or however you identify the staffs
            $staffsNot->each(function ($staffsNot) use ($order) {
                $staffsNot->notify(new NewOrderNotification($order));
            });
        }

        return $nearestShop;
    }
}

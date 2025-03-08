<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shops extends Model
{

  protected $fillable = [
    'name', 'address', 'phone', 'email', 'working_hours', 'delivery_pickup_latitude', 'delivery_pickup_longitude', 'status'
  ];

  public static function nearby(float $latitude, float $longitude, float $radiusInKm = 10)
  {
      $haversine = "(6371 * acos(cos(radians($latitude)) 
                  * cos(radians(delivery_pickup_latitude)) 
                  * cos(radians(delivery_pickup_longitude) - radians($longitude)) 
                  + sin(radians($latitude)) 
                  * sin(radians(delivery_pickup_latitude))))";

      return self::select('*')
          ->selectRaw("$haversine AS distance")
          ->where('status', 1)
          ->having('distance', '<=', $radiusInKm)
          ->orderBy('distance')->limit(10);
  }
  
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Promotions extends Model
{
   protected $table = 'promotions';

   protected $fillable = ['title', 'message', 'count'];
   
}

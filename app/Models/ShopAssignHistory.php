<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAssignHistory extends Model
{
    use HasFactory;

    protected $table = 'shop_assign_histories';

    protected $fillable = [
        'order_id',
        'from_shop_id',
        'to_shop_id',
        'reason',
        'transferred_by'
    ];

    /**
     * Get the order related to this assignment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the shop that transferred the order.
     * Null if assigned by admin.
     */
    public function fromShop()
    {
        return $this->belongsTo(Shops::class, 'from_shop_id');
    }

    /**
     * Get the shop that received the assignment.
     */
    public function toShop()
    {
        return $this->belongsTo(Shops::class, 'to_shop_id');
    }

    /**
     * Check if the assignment was made by admin.
     */
    public function isAssignedByAdmin()
    {
        return is_null($this->from_shop_id);
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

}

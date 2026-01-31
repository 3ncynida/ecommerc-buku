<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'item_id',
        'quantity',
        'total_price',
        'item_status',
        'payment_status'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'item_id',
        'shipping_address_id',
        'quantity',
        'total_price',
        'item_status',
        'payment_status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_number');
    }

    /**
     * Relasi ke model Address
     */
    public function shippingAddress()
    {
        // Pastikan nama model alamat Anda adalah 'Address'
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }
}

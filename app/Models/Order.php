<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'courier_id',
        'item_id',
        'shipping_address_id',
        'quantity',
        'total_price',
        'note',
        'item_status',
        'payment_status',
        'courier_note',
        'delivery_proof_path',
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

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
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

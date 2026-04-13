<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    public const FIXED_ADMIN_FEE = 1000;

    protected $fillable = [
        'order_number',
        'user_id',
        'courier_id',
        'shipping_address_id',
        'total_price',
        'shipping_fee',
        'note',
        'item_status',
        'payment_status',
        'courier_note',
        'delivery_proof_path',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_number', 'order_number')->latestOfMany();
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

    public function getAdminFeeAttribute(): int
    {
        $payment = $this->relationLoaded('payment')
            ? $this->payment
            : $this->payment()->latest()->first();

        $adminFee = (int) data_get($payment?->raw_response, 'admin_fee', 0);
        if ($adminFee > 0) {
            return $adminFee;
        }

        $items = $this->relationLoaded('items')
            ? $this->items
            : $this->items()->get();

        $itemSubtotal = (int) round($items->sum(fn ($item) => $item->price * $item->quantity));
        $shippingFee = (int) round($this->shipping_fee ?? 0);
        $derivedAdminFee = (int) round(($this->total_price ?? 0) - $itemSubtotal - $shippingFee);

        return max(0, $derivedAdminFee);
    }
}

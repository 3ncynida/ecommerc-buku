<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'payment_type',
        'status',
        'snap_token',
        'checkout_url',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'quantity_added',
        'previous_stock',
        'new_stock',
        'notes',
    ];

    // Relasi ke Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

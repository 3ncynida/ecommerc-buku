<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Order;

class Item extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'publisher',
        'publication_year',
        'isbn',
        'pages',
        'language',
        'author_id',
        'price',
        'image',
        'stok',
        'description'
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            // Mengubah "Belajar Laravel 11" menjadi "belajar-laravel-11"
            $item->slug = Str::slug($item->name);
        });

        static::updating(function ($item) {
            // (Opsional) Update slug jika nama buku diubah
            $item->slug = Str::slug($item->name);
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }

    public function isFavorited()
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return false;
        }

        // Cek apakah ada data di tabel wishlists yang menghubungkan user ini dengan item ini
        return \App\Models\Wishlist::where('user_id', auth()->id())
            ->where('item_id', $this->id)
            ->exists();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function wasPurchasedBy(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return Order::where('user_id', $userId)
            ->whereHas('items', function ($query) {
                $query->where('item_id', $this->id);
            })
            ->where('payment_status', 'success')
            ->whereNotIn('item_status', ['dibatalkan', 'gagal', 'pembayaran_gagal'])
            ->exists();
    }
}

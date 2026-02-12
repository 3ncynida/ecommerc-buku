<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
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
}

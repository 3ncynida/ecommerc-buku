<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

        protected static function booted()
    {
        static::creating(function ($category) {
            // Mengubah "Belajar Laravel 11" menjadi "belajar-laravel-11"
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            // (Opsional) Update slug jika nama buku diubah
            $category->slug = Str::slug($category->name);
        });
    }
}

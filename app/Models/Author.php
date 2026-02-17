<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Author extends Model
{
    protected $fillable = ['name', 'bio'];

    public function books()
    {
        return $this->hasMany(Item::class);
    }

    public function items()
    {
        // Author has many Items (Satu penulis memiliki banyak buku)
        return $this->hasMany(Item::class);
    }

    protected static function booted()
    {
        static::creating(function ($author) {
            // Mengubah "Belajar Laravel 11" menjadi "belajar-laravel-11"
            $author->slug = Str::slug($author->name);
        });

        static::updating(function ($author) {
            // (Opsional) Update slug jika nama buku diubah
            $author->slug = Str::slug($author->name);
        });
    }
}

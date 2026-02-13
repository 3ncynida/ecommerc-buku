<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;

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
}

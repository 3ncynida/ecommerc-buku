<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $author = Author::where("name","Tere Liye")->first();
        $category = Category::where('name', 'Manga')->first();

        Item::firstOrCreate([
            'name'        => 'One Piece',
            'price'       => 250000,
            'category_id' => $category->id,
            'author_id' => $author->id,
        ]);
    }
}

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
        $author = Author::where("name", "Odading")->first();
        $category = Category::where('name', 'Manga')->first();

        Item::firstOrCreate([
            'name' => 'One Piece',
            'slug' => 'one-piece',
            'publisher' => 'Shueisha',
            'publication_year' => 1997,
            'isbn' => '978-4-08-872871-0',
            'pages' => 105,
            'language' => 'Indonesia',
            'description' => 'One Piece (Jepang: ワンピース, Hepburn: Wan Pīsu) adalah sebuah seri manga Jepang yang ditulis dan diilustrasikan oleh Eiichiro Oda. Manga ini telah dimuat di majalah Weekly Shōnen Jump milik Shueisha sejak tanggal 22 Juli 1997, dan telah dibundel menjadi 105 volume tankōbon hingga Maret 2023. Ceritanya mengisahkan petualangan Monkey D. Luffy, seorang anak laki-laki yang memiliki kemampuan tubuh elastis seperti karet setelah memakan Buah Iblis secara tidak disengaja. Luffy bersama kru bajak lautnya, yang dinamakan Bajak Laut Topi Jerami, menjelajahi Grand Line untuk mencari harta karun terbesar di dunia yang dikenal sebagai "One Piece" dalam rangka untuk menjadi Raja Bajak Laut yang berikutnya.',
            'stok' => 10,
            'price' => 250000,
            'category_id' => $category->id,
            'author_id' => $author->id,
        ]);
    }
}

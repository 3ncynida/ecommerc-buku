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
        // Mengambil data Author dan Category yang sudah ada
        $author = Author::where("name", "Odading")->first();
        $category = Category::where('name', 'Manga')->first();

        // 1. Buat data Item tanpa menyertakan 'category_id' secara langsung
        $item = Item::firstOrCreate(
            ['slug' => 'one-piece'], // Cek berdasarkan slug agar tidak duplikat
            [
                'name' => 'One Piece',
                'publisher' => 'Shueisha',
                'image' => 'items/one-piece.jpg',
                'publication_year' => 1997,
                'isbn' => '978-4-08-872871-0',
                'pages' => 105,
                'language' => 'Indonesia',
                'description' => 'One Piece adalah sebuah seri manga Jepang yang ditulis dan diilustrasikan oleh Eiichiro Oda. Ceritanya mengisahkan petualangan Monkey D. Luffy bersama kru bajak lautnya untuk mencari harta karun terbesar di dunia yang dikenal sebagai "One Piece".',
                'stok' => 10,
                'price' => 30000,
                'author_id' => $author->id,
            ]
        );

        // 2. Hubungkan Item dengan Category melalui tabel pivot 'category_item'
        // Gunakan sync() agar tidak terjadi duplikasi jika seeder dijalankan berkali-kali
        if ($category) {
            $item->categories()->sync([$category->id]);
        }
    }
}
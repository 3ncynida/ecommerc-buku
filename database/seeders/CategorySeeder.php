<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Novel',
            'Komik',
            'Manga',
            'Bisnis',
            'Teknologi',
            'Self-Help',
            'Sejarah',
            'Biografi',
            'Fiksi Ilmiah',
            'Fantasi',
            'Romance',
            'Thriller',
            'Horror',
            'Psikologi',
            'Kesehatan',
            'Agama',
            'Filsafat',
            'Seni & Desain',
            'Fotografi',
            'Memasak',
            'Travel',
            'Anak-anak',
            'Pendidikan',
            'Sastra',
            'Puisi',
            'Drama',
            'Ensiklopedia',
            'Majalah',
            'Komputer & Internet',
            'Hobi & Kerajinan',
            'Olahraga',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category],
                ['slug' => Str::slug($category)]
            );
        }
    }
}

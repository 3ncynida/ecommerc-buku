<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            'Odading',
            'Andrea Hirata',
            'Pramoedya Ananta Toer',
        ];

        foreach ($authors as $author) {
            Author::firstOrCreate(
                ['name' => $author],
                ['slug' => Str::slug($author)]
            );
        }
    }
}

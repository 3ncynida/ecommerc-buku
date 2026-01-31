<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            'Tere Liye',
            'Andrea Hirata',
            'Pramoedya Ananta Toer',
        ];

        foreach ($authors as $author) {
            Author::firstOrCreate(['name' => $author]);
        }
    }
}

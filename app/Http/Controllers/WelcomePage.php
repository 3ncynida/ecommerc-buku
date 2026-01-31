<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Item;

class WelcomePage extends Controller
{
    // app/Http/Controllers/BookController.php
    public function index()
    {
        // Mengambil buku beserta data author-nya sekaligus agar ringan
        $featuredBooks = Item::with('author')->latest()->get();

        return view('welcome', compact('featuredBooks'));
    }
}

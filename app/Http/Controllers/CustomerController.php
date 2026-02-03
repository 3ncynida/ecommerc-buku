<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Item;

class CustomerController extends Controller
{
    // app/Http/Controllers/BookController.php
    public function index()
    {
        // Mengambil buku beserta data author-nya sekaligus agar ringan
        $featuredBooks = Item::with('author')->latest()->get();

        return view('welcome', compact('featuredBooks'));
    }

        public function show(Item $item)
    {
        // Ambil buku lain dengan kategori yang sama sebagai rekomendasi
        $relatedBooks = Item::where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->take(4)
            ->get();

        return view('customer.bookShow', compact('item', 'relatedBooks'));
    }
}

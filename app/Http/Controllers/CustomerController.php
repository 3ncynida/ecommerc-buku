<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Item;
use App\Models\Category;
use App\Models\Order;

class CustomerController extends Controller
{
    // app/Http/Controllers/BookController.php
    public function home()
    {
        // Mengambil buku beserta data author-nya sekaligus agar ringan
        $featuredBooks = Item::with('author')->latest()->get();
        $categories = Category::all()->take(4);

        return view('welcome', compact('featuredBooks', 'categories'));
    }

    public function show(Item $item)
    {
        // Ambil buku lain dengan kategori yang sama sebagai rekomendasi
        $relatedBooks = Item::where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->take(4)
            ->get();

        return view('customer.indexShow', compact('item', 'relatedBooks'));
    }

    public function category()
    {
        // Kita hapus bagian where('is_active', true)
        $categories = Category::has('items')
            ->with([
                'items' => function ($query) {
                    $query->take(5); // Ambil 5 buku saja per kategori untuk preview di home
                }
            ])
            ->get();

        return view('customer.categories.index', compact('categories'));
    }

    public function categoryList()
    {
        $categories = Category::withCount('items')->get();

        return view('customer.categories.list', compact('categories'));
    }

    public function categoryShow(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Mulai query buku berdasarkan kategori
        $query = Item::where('category_id', $id)->with('author');

        // Filter: Hanya stok yang tersedia
        if ($request->has('filter') && $request->filter == 'stok') {
            $query->where('stok', '>', 0);
        }

        // Logic Sorting
        if ($request->sort == 'termurah') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'termahal') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest(); // Default: Terbaru
        }

        $items = $query->get();

        return view('customer.categories.show', compact('category', 'items'));
    }

    public function orderIndex()
    {
        // Mengambil semua pesanan milik user yang login, diurutkan dari yang terbaru
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.order.index', compact('orders'));
    }
}

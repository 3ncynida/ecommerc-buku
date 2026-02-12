<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Item;
use App\Models\Category;
use App\Models\Order;
use App\Models\Wishlist;

class CustomerController extends Controller
{
    // home controller
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

    // category controller
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

    // Order controller
    public function orderIndex()
    {
        // Mengambil semua pesanan milik user yang login, diurutkan dari yang terbaru
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.order.index', compact('orders'));
    }

    // wishlist controller
    public function wishlistIndex()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->whereHas('item') // Hanya ambil jika relasi 'item' ada
            ->with('item.author') // Eager load agar cepat
            ->get();

        return view('customer.wishlist.index', compact('wishlists'));
    }

    public function toggleWishlist(Request $request)
    {
        // 1. Pastikan User Login
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 2. Ambil item_id (sesuai dengan body JSON di JS Anda)
        $itemId = $request->item_id;

        // 3. Logika Toggle
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('item_id', $itemId) // Pastikan nama kolom di DB adalah item_id
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'item_id' => $itemId
        ]);

        return response()->json(['status' => 'added']);
    }
}

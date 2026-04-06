<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Wishlist;
use App\Services\DeliveryEstimator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // home controller
    public function home()
    {
        // Mengambil buku beserta data author-nya sekaligus agar ringan
        $featuredBooks = Item::with('author')->latest()->take(8)->get();
        $categories = Category::all()->take(4);
        $bestSellerIds = \App\Models\OrderItem::select('item_id', DB::raw('SUM(quantity) as sold_qty'))
            ->groupBy('item_id')
            ->orderByDesc('sold_qty')
            ->limit(4)
            ->pluck('item_id');

        $bestSellers = collect();
        if ($bestSellerIds->isNotEmpty()) {
            $bestSellers = Item::with('author')
                ->whereIn('id', $bestSellerIds)
                ->get()
                ->sortBy(function ($item) use ($bestSellerIds) {
                    return array_search($item->id, $bestSellerIds->all(), true);
                })
                ->values();
        }

        return view('welcome', compact('featuredBooks', 'categories', 'bestSellers'));
    }

    public function show(Item $item)
    {
        // Muat relasi author dan ulasan beserta pengguna yang mengulas
        $item->load(['author', 'reviews.user']);

        // Ambil ID kategori yang dimiliki buku ini
        $item->load(['author', 'reviews.user']);
        $categoryIds = $item->categories->pluck('id');

        // Cari buku lain yang memiliki setidaknya satu kategori yang sama
        $relatedBooks = Item::whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        })
            ->where('id', '!=', $item->id)
            ->take(5)
            ->get();

        $canReview = $item->wasPurchasedBy(auth()->id());
        $existingReview = $item->reviews->where('user_id', auth()->id())->first();

        return view('customer.indexShow', compact('item', 'relatedBooks', 'canReview', 'existingReview'));
    }

    // category controller
    public function category()
    {
        // Kita hapus bagian where('is_active', true)
        $categories = Category::has('items')
            ->with([
                'items' => function ($query) {
                    $query->take(5); // Ambil 5 buku saja per kategori untuk preview di home
                },
            ])
            ->get();

        return view('customer.categories.index', compact('categories'));
    }

    public function categoryList()
    {
        $categories = Category::withCount('items')->get();

        return view('customer.categories.list', compact('categories'));
    }

    public function categoryShow(Category $category, Request $request)
    {
        // Gunakan relasi items() dari model Category (Many-to-Many)
        $query = $category->items()->with('author');

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
            $query->latest();
        }

        $items = $query->get();

        return view('customer.categories.show', compact('category', 'items'));
    }

    // Order controller
    public function orderIndex()
    {
        // Mengambil semua pesanan milik user yang login, diurutkan dari yang terbaru
        $orders = Order::where('user_id', auth()->id())
            ->with('payment')
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
            'item_id' => $itemId,
        ]);

        return response()->json(['status' => 'added']);
    }

    public function authorShow(Author $author)
    {
        // Menggunakan 'items' (jamak) dan memuat jumlah buku secara otomatis
        $author->load(['items'])->loadCount('items');

        return view('customer.author.index', [
            'author' => $author,
            'books' => $author->items, // Mengirim koleksi buku penulis
        ]);
    }

    // order controller
    public function orderShow(Order $order, DeliveryEstimator $estimator)
    {
        // Mengambil order milik user yang login dengan relasi buku, penulis, dan alamat
        $order = \App\Models\Order::with([
            'items.item.author',
            'shippingAddress.province',
            'shippingAddress.city',
            'shippingAddress.district',
            'courier',
            'payment',
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($order->id);

        $deliveryEstimate = $estimator->estimate($order->shippingAddress);

        return view('customer.order.show', compact('order', 'deliveryEstimate'));
    }

    public function invoice(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['user', 'items.item', 'shippingAddress.province', 'shippingAddress.city', 'shippingAddress.district', 'payment']);
        
        return view('customer.order.invoice', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($order->payment_status, ['pending', 'failed'])) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan setelah dibayar atau diproses.');
        }

        if (! in_array($order->item_status, ['menunggu_pembayaran', 'pembayaran_gagal'])) {
            return back()->with('error', 'Pesanan ini sudah dalam proses pengiriman.');
        }

        $order->update([
            'payment_status' => 'cancelled',
            'item_status' => 'dibatalkan',
        ]);

        return back()->with('status', 'Pesanan berhasil dibatalkan.');
    }

    public function confirmDelivery(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->item_status !== 'sampai' || ! $order->delivery_proof_path) {
            return back()->with('error', 'Bukti pengiriman belum lengkap atau sudah dikonfirmasi.');
        }

        $order->update(['item_status' => 'selesai']);

        return back()->with('status', 'Terima kasih atas konfirmasinya. Pesanan telah selesai.');
    }
}

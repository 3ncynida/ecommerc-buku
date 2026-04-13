<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Services\ShippingCalculator;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('customer.cart.index', compact('cart', 'total'));
    }

    // Menambah buku ke keranjang
    public function add($id)
    {
        $product = Item::findOrFail($id);

        // jangan biarkan user menambahkan jika stok habis
        if ($product->stok <= 0) {
            if (request()->wantsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak tersedia',
                ], 400);
            }

            return redirect()->back()->with('error', 'Stok buku habis');
        }

        $cart = session()->get('cart', []);
        $quantity = (int) request('quantity', 1);

        // jika user minta lebih dari stok, beri tahu atau batasi
        if ($quantity > $product->stok) {
            $quantity = $product->stok;
        }

        // Jika produk sudah ada di keranjang, tambah quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
            // jangan melebihi stok total
            if ($cart[$id]['quantity'] > $product->stok) {
                $cart[$id]['quantity'] = $product->stok;
            }
        } else {
            // Jika belum ada, tambahkan data baru
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        // Jika request AJAX (fetch/JS) kirimkan JSON agar tidak perlu refresh
        if (request()->wantsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambah ke keranjang!',
                'cart_count' => array_sum(array_map(fn($i) => $i['quantity'], $cart)),
                'cart' => $cart,
            ]);
        }

        return redirect()->back()->with('success', 'Buku berhasil ditambah ke keranjang!');
    }

    // Menghapus item dari keranjang
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return back()->with('success', 'Buku dihapus dari keranjang');
        }
    }

    public function checkout()
    {
        $user = auth()->user();

        // Pastikan user sudah login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil alamat utama user
        $address = $user->addresses()->where('is_default', true)->first();

        // Atau ambil semua alamat untuk pilihan
        $allAddresses = $user->addresses;

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong!');
        }

        // Verifikasi stok untuk setiap item sebelum checkout
        foreach ($cart as $id => $details) {
            $product = Item::find($id);
            if (!$product || $product->stok < $details['quantity']) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok untuk {$details['name']} tidak mencukupi. Silakan periksa keranjang Anda.");
            }
        }

        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        $shippingCalculator = app(ShippingCalculator::class);
        $shippingDetails = $shippingCalculator->forAddress($address);
        $shippingCost = $shippingDetails['cost'];
        $shippingEstimate = $shippingDetails['estimate'];
        $adminFee = Order::FIXED_ADMIN_FEE;
        $grandTotal = $subtotal + $shippingCost + $adminFee;

        return view('customer.cart.checkout', compact('cart', 'subtotal', 'shippingCost', 'shippingEstimate', 'adminFee', 'grandTotal'));
    }
}

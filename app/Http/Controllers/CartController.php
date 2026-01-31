<?php

namespace App\Http\Controllers;

use App\Models\Item;
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
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambah quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Jika belum ada, tambahkan data baru
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
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
        $cart = session()->get('cart', []);
        if (empty($cart))
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong!');

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        return view('cart.checkout', compact('cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $cart = session()->get('cart');
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // 1. Susun Pesanan dalam Teks
        $message = "Halo Admin Libris, saya ingin memesan:\n\n";
        foreach ($cart as $details) {
            $message .= "- " . $details['name'] . " (" . $details['quantity'] . "x)\n";
        }
        $message .= "\n*Total: Rp " . number_format($total, 0, ',', '.') . "*\n\n";
        $message .= "*Data Pengiriman:*\n";
        $message .= "Nama: " . $request->name . "\n";
        $message .= "No. HP: " . $request->phone . "\n";
        $message .= "Alamat: " . $request->address;

        // 2. Encode Pesanan ke URL WhatsApp
        $whatsappNumber = "628123456789"; // Ganti dengan nomor WA Anda (gunakan kode negara 62)
        $url = "https://wa.me/" . $whatsappNumber . "?text=" . urlencode($message);

        // 3. Kosongkan Keranjang setelah pesan
        session()->forget('cart');

        return redirect()->away($url);
    }
}
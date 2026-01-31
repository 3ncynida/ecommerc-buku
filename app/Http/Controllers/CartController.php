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
        foreach($cart as $details) {
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
        if(isset($cart[$id])) {
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
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return back()->with('success', 'Buku dihapus dari keranjang');
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Mengambil data order beserta data itemnya (Eager Loading)
        $orders = Order::with('user', 'item.author')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Cari order berdasarkan ID, termasuk relasi user dan item
        $order = Order::with('user', 'item.author')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }
}

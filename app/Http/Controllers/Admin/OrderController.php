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
        $orders = Order::with('user', 'item.author', 'payment', 'courier')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Cari order berdasarkan ID, termasuk relasi user dan item
        $order = Order::with('user', 'item.author', 'payment', 'courier', 'shippingAddress.province', 'shippingAddress.city', 'shippingAddress.district')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function reassign(Order $order)
    {
        if ($order->payment_status !== 'success' || $order->item_status !== 'gagal') {
            return back()->with('error', 'Pesanan ini tidak dapat ditugaskan ulang.');
        }

        $order->update([
            'courier_id' => null,
            'item_status' => 'menunggu_kurir',
        ]);

        return back()->with('success', "Pesanan #{$order->order_number} dikembalikan ke antrian kurir.");
    }
}

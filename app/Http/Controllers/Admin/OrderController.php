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

    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input status
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai',
        ]);

        // 2. Cari pesanan atau gagalkan jika tidak ada
        $order = \App\Models\Order::findOrFail($id);

        // 3. Update status
        $order->update([
            'item_status' => $request->status,
        ]);

        // 4. Kembali ke halaman detail dengan notifikasi sukses
        return redirect()->back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diperbarui menjadi ' . strtoupper($request->status));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Mengambil data order beserta data itemnya (Eager Loading)
        $orders = Order::with('item')->latest()->get();
        
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['item_status' => $request->item_status]);

        return back()->with('success', 'Status item berhasil diperbarui!');
    }
}
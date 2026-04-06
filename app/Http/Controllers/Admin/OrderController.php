<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Services\DeliveryEstimator;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data order beserta data itemnya (Eager Loading)
        $orders = Order::with('user', 'items.item.author', 'payment', 'courier')
            ->when($request->search, function ($query) use ($request) {
                $query->where('order_number', 'like', "%{$request->search}%")
                      ->orWhereHas('user', function ($q) use ($request) {
                          $q->where('name', 'like', "%{$request->search}%");
                      });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id, DeliveryEstimator $estimator)
    {
        // Cari order berdasarkan ID, termasuk relasi user dan item
        $order = Order::with('user', 'items.item.author', 'payment', 'courier', 'shippingAddress.province', 'shippingAddress.city', 'shippingAddress.district')->findOrFail($id);

        $deliveryEstimate = $estimator->estimate($order->shippingAddress);

        return view('admin.orders.show', compact('order', 'deliveryEstimate'));
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items.item', 'shippingAddress.province', 'shippingAddress.city', 'shippingAddress.district', 'payment']);
        return view('admin.orders.invoice', compact('order'));
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

    public function prepareForCourier(Order $order)
    {
        if ($order->payment_status !== 'success' || $order->item_status !== 'sedang_dikemas') {
            return back()->with('error', 'Pesanan belum selesai dikemas.');
        }

        $order->update([
            'item_status' => 'menunggu_kurir',
        ]);

        return back()->with('success', "Pesanan #{$order->order_number} siap menunggu kurir.");
    }

    public function clearNotifications()
    {
        cache()->put('admin_cleared_notifications_at_' . auth()->id(), now());
        return back();
    }
}

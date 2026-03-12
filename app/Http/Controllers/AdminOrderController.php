<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Statistik Dasar
        $totalRevenue = Order::where('payment_status', 'success')->sum('total_price');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('payment_status', 'pending')->count();
        $shippingOrders = Order::whereIn('item_status', ['menunggu_kurir', 'diproses_kurir', 'dikirim', 'sampai'])->count();

        // Data Tambahan
        $totalItems = \App\Models\Item::count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();

        // Data untuk Chart (7 hari terakhir)
        $salesData = Order::where('payment_status', 'success')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($row) {
                $row->total = (float) $row->total;
                return $row;
            });

        // Mengambil transaksi terbaru (limit 10)
        $orders = Order::with(['item', 'user'])->latest()->limit(10)->get();

        return view('admin.dashboard.index', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'shippingOrders',
            'totalItems',
            'totalCustomers',
            'salesData'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['item_status' => $request->item_status]);

        return back()->with('success', 'Status item berhasil diperbarui!');
    }
}

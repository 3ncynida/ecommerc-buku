<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourierController extends Controller
{
    private function transitionMap(): array
    {
        return [
            'diproses_kurir' => 'dikirim',
            'sampai' => 'selesai',
        ];
    }

    private function courierStatuses(): array
    {
        return ['menunggu_kurir', 'diproses_kurir', 'dikirim', 'sampai', 'selesai', 'gagal'];
    }

    public function dashboard()
    {
        $availableOrders = Order::with([
            'user',
            'items.item',
            'shippingAddress.province',
            'shippingAddress.city',
            'shippingAddress.district',
        ])
            ->whereNull('courier_id')
            ->where('payment_status', 'success')
            ->where('item_status', 'menunggu_kurir')
            ->orderByDesc('created_at')
            ->get();

        $myTasks = Order::with([
            'user',
            'items.item',
            'shippingAddress.province',
            'shippingAddress.city',
            'shippingAddress.district',
        ])
            ->where('courier_id', Auth::id())
            ->where('payment_status', 'success')
            ->whereIn('item_status', ['diproses_kurir', 'dikirim', 'sampai', 'gagal'])
            ->orderByDesc('updated_at')
            ->get();

        return view('courier.dashboard', compact('availableOrders', 'myTasks'));
    }

    public function claim(Order $order)
    {
        if ($order->payment_status !== 'success' || $order->courier_id || $order->item_status !== 'menunggu_kurir') {
            return back()->with('error', 'Pesanan ini sudah ditangani.');
        }

        $order->update([
            'courier_id' => Auth::id(),
            'item_status' => 'diproses_kurir',
        ]);

        return back()->with('status', "Pesanan #{$order->order_number} berhasil diklaim.");
    }

    public function uploadProof(Request $request, Order $order)
    {
        if ($order->courier_id !== Auth::id() || $order->item_status !== 'dikirim') {
            abort(403);
        }

        $data = $request->validate([
            'proof_image' => ['required', 'image', 'max:2048'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($order->delivery_proof_path) {
            Storage::disk('public')->delete($order->delivery_proof_path);
        }

        $path = $request->file('proof_image')->store('courier-proofs', 'public');

        $order->update([
            'delivery_proof_path' => $path,
            'courier_note' => $data['note'] ?? null,
            'item_status' => 'sampai',
        ]);

        return back()->with('status', 'Bukti pengiriman berhasil diunggah dan status diupdate.');
    }

    public function reportFailure(Request $request, Order $order)
    {
        if ($order->courier_id !== Auth::id()) {
            abort(403);
        }

        if ($order->item_status === 'gagal') {
            return back()->with('error', 'Status sudah gagal.');
        }

        $data = $request->validate([
            'failure_note' => ['required', 'string', 'max:1000'],
        ]);

        $order->update([
            'item_status' => 'gagal',
            'courier_note' => $data['failure_note'],
        ]);

        return back()->with('status', 'Status gagal pengiriman berhasil dicatat.');
    }

    public function retry(Order $order)
    {
        if ($order->courier_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'success' || $order->item_status !== 'gagal') {
            return back()->with('error', 'Pesanan ini tidak dapat dicoba kirim ulang.');
        }

        $order->update([
            'item_status' => 'diproses_kurir',
        ]);

        return back()->with('status', "Pesanan #{$order->order_number} dikembalikan ke proses pengiriman.");
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->courier_id !== Auth::id()) {
            abort(403);
        }

        $transitionMap = $this->transitionMap();
        $nextStatus = $transitionMap[$order->item_status] ?? null;

        if (!$nextStatus) {
            return response()->json(['message' => 'Tidak ada langkah berikutnya.'], 422);
        }

        $order->update(['item_status' => $nextStatus]);

        return response()->json([
            'message' => "Status diperbarui menjadi " . str_replace('_', ' ', $nextStatus),
            'nextStatus' => $nextStatus,
        ]);
    }
}

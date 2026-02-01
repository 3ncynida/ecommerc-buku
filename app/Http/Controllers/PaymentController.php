<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        // Ambil item pertama dari seeder untuk testing
        $item = \App\Models\Item::first();
        return view('checkout', compact('item'));
    }

    // 1. Membuat Transaksi
    public function createTransaction(Request $request)
    {
        $cart = session()->get('cart', []);
        // Jika cart kosong, jangan lanjut
        if (empty($cart))
            return response()->json(['error' => 'Keranjang kosong'], 400);

        $orderId = 'LBRS-' . time();
        $totalAmount = 0;
        $itemDetails = [];

        // Loop keranjang untuk hitung total dan buat detail item untuk Midtrans
        foreach ($cart as $id => $details) {
            $totalAmount += $details['price'] * $details['quantity'];
            $itemDetails[] = [
                'id' => $id,
                'price' => (int) $details['price'],
                'quantity' => $details['quantity'],
                'name' => $details['name'],
            ];
        }

        $firstItemKey = array_key_first($cart);
        $itemId = $firstItemKey;  // Use the key directly (already the item ID)

        // 1. Simpan ke Tabel Orders
        $order = \App\Models\Order::create([
            'order_number' => $orderId,
            'total_price' => $totalAmount,
            'item_status' => 'pending',
            'payment_status' => 'pending',
            'item_id' => $itemId,
            // tambahkan field lain seperti user_id atau alamat jika perlu
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $request->name, // Ambil dari input form checkout
                'email' => $request->email,
            ],
        ];

        try {
            // Validasi request data
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
            ]);

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // 2. Simpan ke Tabel Payments
            \App\Models\Payment::create([
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'status' => 'pending',
                'snap_token' => $snapToken,
            ]);

            // Opsional: Kosongkan keranjang di sini atau setelah sukses di frontend
            session()->forget('cart');

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            // Ini akan mengirimkan pesan error asli ke browser agar bisa kita baca
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // 2. Webhook / Notification Handler
    public function webhook(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // Validasi Signature Key (Keamanan)
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari data pembayaran berdasarkan order_id
        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Update status berdasarkan response Midtrans
        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $payment->update(['status' => 'success']);
        } elseif ($transactionStatus == 'pending') {
            $payment->update(['status' => 'pending']);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $payment->update(['status' => 'failed']);
        }

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // 1. Update Tabel Payment
            $payment->update(['status' => 'success']);

            // 2. Update Tabel Order (PENTING!)
            \App\Models\Order::where('order_number', $request->order_id)
                ->update(['payment_status' => 'success']);
        }

        // Simpan transaction_id dan raw response untuk audit
        $payment->update([
            'transaction_id' => $request->transaction_id,
            'payment_type' => $type,
            'raw_response' => $request->all(),
        ]);

        return response()->json(['message' => 'Webhook received and processed']);
    }

    public function checkout(Request $request)
    {
        // Alias untuk createTransaction (untuk backward compatibility)
        return $this->createTransaction($request);
    }
}

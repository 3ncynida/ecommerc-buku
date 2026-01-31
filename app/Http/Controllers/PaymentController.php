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
        $item = \App\Models\Item::findOrFail($request->item_id);
        $orderId = 'ORD-' . time(); // ID unik untuk Midtrans & Tabel Order

        // --- 1. SIMPAN KE TABEL ORDERS ---
        $order = \App\Models\Order::create([
            'order_number' => $orderId,
            'item_id' => $item->id,
            'quantity' => 1,
            'total_price' => $item->price,
            'item_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $item->price,
            ],
            'customer_details' => [
                'first_name' => 'User Ganteng',
                'email' => 'user@example.com',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // --- 3. SIMPAN KE TABEL PAYMENTS ---
            \App\Models\Payment::create([
                'order_id' => $orderId,
                'amount' => $item->price,
                'status' => 'pending',
                'snap_token' => $snapToken,
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
        // 1. Ambil data item dari database hasil seeder
        $item = \App\Models\Item::findOrFail($request->item_id);

        // 2. Buat Order ID unik
        $orderId = 'TRX-' . mt_rand(1000, 9999);

        // 3. Siapkan Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $item->price, // Harga dari database
            ],
            'item_details' => [
                [
                    'id' => $item->id,
                    'price' => (int) $item->price,
                    'quantity' => 1,
                    'name' => $item->name,
                ]
            ],
            'customer_details' => [
                'first_name' => 'Pembeli Tes',
                'email' => 'pembeli@test.com',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // 4. Simpan ke tabel payments (yang kita buat sebelumnya)
            $payment = \App\Models\Payment::create([
                'order_id' => $orderId,
                'amount' => $item->price,
                'status' => 'pending',
                'snap_token' => $snapToken,
                'raw_response' => json_encode($params) // simpan request awal
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
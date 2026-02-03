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
    // 1. Validasi dulu sebelum proses apapun
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
    ]);

    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return response()->json(['error' => 'Keranjang kosong'], 400);
    }

    $orderId = 'LBRS-' . time();
    $totalAmount = 0;
    $itemDetails = [];

    foreach ($cart as $id => $details) {
        $totalAmount += $details['price'] * $details['quantity'];
        $itemDetails[] = [
            'id' => $id,
            'price' => (int) $details['price'],
            'quantity' => $details['quantity'],
            'name' => $details['name'],
        ];
    }

    try {
        // 2. Simpan Order (Gunakan Transaction agar aman)
        \DB::transaction(function () use ($orderId, $totalAmount, $cart, $request, $itemDetails, &$snapToken) {
            $itemId = array_key_first($cart);

            Order::create([
                'order_number' => $orderId,
                'total_price' => $totalAmount,
                'payment_status' => 'pending',
                'item_id' => $itemId,
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $totalAmount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $request->name,
                    'email' => $request->email,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            \App\Models\Payment::create([
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'status' => 'pending',
                'snap_token' => $snapToken,
            ]);
        });

        // JANGAN hapus cart di sini. Hapus di JavaScript onSuccess.
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
        // Alias untuk createTransaction (untuk backward compatibility)
        return $this->createTransaction($request);
    }
}

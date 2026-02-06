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

    // 2. Webhook / Notification Handler (DEPRECATED - gunakan API route /api/midtrans-callback)
    // Kept for backward compatibility, but use PaymentCallbackController instead
    public function webhook(Request $request)
    {
        return app(\App\Http\Controllers\PaymentCallbackController::class)->receive($request);
    }

    public function checkout(Request $request)
    {
        // Alias untuk createTransaction (untuk backward compatibility)
        return $this->createTransaction($request);
    }
}

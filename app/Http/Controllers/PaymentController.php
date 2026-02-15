<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

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
            // Ambil alamat terpilih user jika ada
            $selectedAddress = null;
            if ($request->filled('address_id')) {
                $selectedAddress = Address::where('id', $request->address_id)
                    ->where('user_id', Auth::id())
                    ->with(['province', 'city', 'district'])
                    ->first();
            }
            // fallback ke alamat default user
            if (!$selectedAddress && Auth::check()) {
                $selectedAddress = Auth::user()->addresses()->where('is_default', true)->with(['province', 'city', 'district'])->first();
            }

            \DB::transaction(function () use ($orderId, $totalAmount, $cart, $request, $itemDetails, $selectedAddress, &$snapToken) {
                $itemId = array_key_first($cart);

                Order::create([
                    'order_number' => $orderId,
                    'user_id' => Auth::id(),
                    'quantity' => array_sum(array_column($cart, 'quantity')),
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
                ];

                // build customer details
                $customer = [
                    'first_name' => $request->name,
                    'email' => $request->email,
                ];

                if ($selectedAddress) {
                    $regionParts = [];
                    if ($selectedAddress->district)
                        $regionParts[] = $selectedAddress->district->name;
                    if ($selectedAddress->city)
                        $regionParts[] = $selectedAddress->city->name;
                    if ($selectedAddress->province)
                        $regionParts[] = $selectedAddress->province->name;
                    $region = implode(', ', array_filter($regionParts));

                    $addressObj = [
                        'first_name' => $selectedAddress->recipient_name,
                        'last_name' => '',
                        'address' => trim($selectedAddress->full_address . ' ' . $region),
                        'city' => $selectedAddress->city->name ?? '',
                        'postal_code' => $selectedAddress->postal_code ?? '',
                        'phone' => $selectedAddress->phone_number ?? '',
                        'country_code' => 'IDN'
                    ];

                    $customer['billing_address'] = $addressObj;
                    $customer['shipping_address'] = $addressObj;
                }

                $params['customer_details'] = $customer;

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                \App\Models\Payment::create([
                    'order_id' => $orderId,
                    'amount' => $totalAmount,
                    'status' => 'pending',
                    'snap_token' => $snapToken,
                    'raw_response' => json_encode(['selected_address' => $selectedAddress ? $selectedAddress->toArray() : null])
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

    public function success($order_id)
    {
        // 1. Cari data order berdasarkan ID atau Order Number
        // Menggunakan findOrFail agar jika data tidak ada, otomatis kembali ke 404
        $order = Order::where('order_number', $order_id)
            ->where('user_id', Auth::id()) // Keamanan: Pastikan milik user yang login
            ->firstOrFail();

        // Coba ambil record payment terbaru untuk order ini
        $payment = Payment::where('order_id', $order->order_number)->latest()->first();

        // Tentukan status (utamakan order->payment_status, fallback ke payment->status)
        $status = $order->payment_status ?? ($payment->status ?? 'pending');

        if ($status === 'success') {
            // Hapus cart hanya jika benar-benar sukses
            session()->forget('cart');
            return view('customer.payment-success', [
                'order' => $order,
                'total' => $order->total_price,
                'order_number' => $order->order_number,
                'payment_method' => $order->payment_method
            ]);
        }

        if ($status === 'pending') {
            return view('customer.payment-pending', [
                'order' => $order,
                'total' => $order->total_price,
                'order_number' => $order->order_number,
                'payment_method' => $order->payment_method,
                'payment' => $payment
            ]);
        }

        // fallback: treat other statuses as failed
        return view('customer.payment-failed', [
            'order' => $order,
            'total' => $order->total_price,
            'order_number' => $order->order_number,
            'payment_method' => $order->payment_method,
            'payment' => $payment
        ]);
    }
}

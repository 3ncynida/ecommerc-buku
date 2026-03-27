<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Item;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\Address;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private function renderPaymentStatusPage(Order $order)
    {
        $payment = Payment::where('order_number', $order->order_number)->latest()->first();
        $status = $order->payment_status ?? ($payment->status ?? 'pending');

        if ($status === 'success') {
            session()->forget('cart');

            return view('customer.payment-success', [
                'order' => $order,
                'total' => $order->total_price,
                'order_number' => $order->order_number,
                'payment_method' => $order->payment_method,
                'payment' => $payment,
            ]);
        }

        if ($status === 'pending') {
            return view('customer.payment-pending', [
                'order' => $order,
                'total' => $order->total_price,
                'order_number' => $order->order_number,
                'payment_method' => $order->payment_method,
                'payment' => $payment,
            ]);
        }

        return view('customer.payment-failed', [
            'order' => $order,
            'total' => $order->total_price,
            'order_number' => $order->order_number,
            'payment_method' => $order->payment_method,
            'payment' => $payment,
        ]);
    }

    private function buildAddressObject(?Address $selectedAddress): ?array
    {
        if (!$selectedAddress) {
            return null;
        }

        $regionParts = [];
        if ($selectedAddress->district) {
            $regionParts[] = $selectedAddress->district->name;
        }
        if ($selectedAddress->city) {
            $regionParts[] = $selectedAddress->city->name;
        }
        if ($selectedAddress->province) {
            $regionParts[] = $selectedAddress->province->name;
        }

        return [
            'first_name' => $selectedAddress->recipient_name,
            'last_name' => '',
            'address' => trim($selectedAddress->full_address . ' ' . implode(', ', array_filter($regionParts))),
            'city' => $selectedAddress->city->name ?? '',
            'postal_code' => $selectedAddress->postal_code ?? '',
            'phone' => $selectedAddress->phone_number ?? '',
            'country_code' => 'IDN',
        ];
    }

    private function createSnapToken(string $gatewayOrderId, Order $order, array $itemDetails, string $customerName, string $customerEmail, ?Address $selectedAddress): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $gatewayOrderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('payment.success', ['orderId' => $order->order_number]),
                'unfinish' => route('payment.unfinish', ['orderId' => $order->order_number]),
                'error' => route('payment.failure', ['orderId' => $order->order_number]),
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
            ],
        ];

        $address = $this->buildAddressObject($selectedAddress);
        if ($address) {
            $params['customer_details']['billing_address'] = $address;
            $params['customer_details']['shipping_address'] = $address;
        }

        return Snap::getSnapToken($params);
    }

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
            'note' => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        // pastikan stok masih tersedia untuk setiap item
        foreach ($cart as $id => $details) {
            $product = Item::find($id);
            if (!$product || $product->stok < $details['quantity']) {
                return response()->json([
                    'error' => "Stok tidak mencukupi untuk {$details['name']}",
                ], 400);
            }
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

                $order = Order::create([
                    'order_number' => $orderId,
                    'user_id' => Auth::id(),
                    'item_id' => $itemId,
                    'shipping_address_id' => $selectedAddress ? $selectedAddress->id : null,
                    'quantity' => array_sum(array_column($cart, 'quantity')),
                    'total_price' => $totalAmount,
                    'note' => $request->note,
                    'payment_status' => 'pending',
                    'item_status' => 'menunggu_pembayaran',
                ]);

                $snapToken = $this->createSnapToken(
                    $order->order_number,
                    $order,
                    $itemDetails,
                    $request->name,
                    $request->email,
                    $selectedAddress
                );

                \App\Models\Payment::create([
                    'order_id' => $order->order_number,
                    'order_number' => $order->order_number,
                    'amount' => $totalAmount,
                    'status' => 'pending',
                    'snap_token' => $snapToken,
                    'raw_response' => [
                        'selected_address' => $selectedAddress ? $selectedAddress->toArray() : null,
                        'attempt' => 'initial',
                    ],
                ]);
            });

            // JANGAN hapus cart di sini. Hapus di JavaScript onSuccess.
            return response()->json(['snap_token' => $snapToken, 'order_id' => $orderId]);

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
        $order = Order::where('order_number', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $this->renderPaymentStatusPage($order);
    }

    public function finish(Request $request)
    {
        $order = Order::where('order_number', $request->order_id)->first();

        if ($order && $order->payment_status === 'success') {
            // Kirim notifikasi ke user
            $order->user->notify(new PaymentSuccessNotification($order));
        }

        return view('customer.payment-success', compact('order'));
    }

    /**
     * Halaman redirect saat transaksi gagal/error.
     */
    public function failure($order_id)
    {
        $order = Order::where('order_number', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $this->renderPaymentStatusPage($order);
    }

    /**
     * Halaman redirect ketika user menutup atau tidak menyelesaikan pembayaran.
     */
    public function unfinish($order_id)
    {
        $order = Order::where('order_number', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $this->renderPaymentStatusPage($order);
    }

    public function retry($order_id)
    {
        $order = Order::with(['item', 'shippingAddress.province', 'shippingAddress.city', 'shippingAddress.district'])
            ->where('order_number', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_status === 'success') {
            return response()->json(['error' => 'Pesanan ini sudah dibayar.'], 422);
        }

        if (!$order->item) {
            return response()->json(['error' => 'Item pesanan tidak ditemukan.'], 404);
        }

        if ($order->item->stok < $order->quantity) {
            return response()->json(['error' => 'Stok item tidak mencukupi untuk pembayaran ulang.'], 422);
        }

        $itemDetails = [[
            'id' => $order->item->id,
            'price' => (int) $order->item->price,
            'quantity' => (int) $order->quantity,
            'name' => $order->item->name,
        ]];

        $gatewayOrderId = $order->order_number . '-R' . time();

        $snapToken = $this->createSnapToken(
            $gatewayOrderId,
            $order,
            $itemDetails,
            Auth::user()->name,
            Auth::user()->email,
            $order->shippingAddress
        );

        Payment::create([
            'order_id' => $gatewayOrderId,
            'order_number' => $order->order_number,
            'amount' => $order->total_price,
            'status' => 'pending',
            'snap_token' => $snapToken,
            'raw_response' => [
                'attempt' => 'retry',
            ],
        ]);

        $order->update([
            'payment_status' => 'pending',
            'item_status' => 'menunggu_pembayaran',
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $order->order_number,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Item;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\Address;
use App\Services\DeliveryEstimate;
use App\Services\DeliveryEstimator;
use App\Services\ShippingCalculator;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private function renderPaymentStatusPage(Order $order)
    {
        $payment = Payment::where('order_number', $order->order_number)->latest()->first();
        $status = $order->payment_status ?? ($payment->status ?? 'pending');
        $deliveryEstimate = $this->deliveryEstimateForOrder($order);
        $shippingMeta = $this->extractShippingMeta($payment);

        $shared = [
            'order' => $order,
            'total' => $order->total_price,
            'order_number' => $order->order_number,
            'payment_method' => $order->payment_method,
            'payment' => $payment,
            'deliveryEstimate' => $deliveryEstimate,
            'shippingMeta' => $shippingMeta,
        ];

        if ($status === 'success') {
            session()->forget('cart');
            return view('customer.payment-success', $shared);
        }

        if ($status === 'pending') {
            return view('customer.payment-pending', $shared);
        }

        return view('customer.payment-failed', $shared);
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

    private function deliveryEstimateForOrder(Order $order): ?DeliveryEstimate
    {
        if (! $order->shippingAddress) {
            return null;
        }

        return app(DeliveryEstimator::class)->estimate($order->shippingAddress);
    }

    private function extractShippingMeta(?Payment $payment): array
    {
        return [
            'distance' => data_get($payment?->raw_response, 'shipping.distance'),
            'cost' => data_get($payment?->raw_response, 'shipping.cost', $payment?->amount),
        ];
    }

    private function loadOrderWithAddress(string $orderNumber, bool $requireOwnership = true): Order
    {
        $query = Order::with(['shippingAddress.province', 'shippingAddress.city', 'shippingAddress.district'])
            ->where('order_number', $orderNumber);

        if ($requireOwnership) {
            $query->where('user_id', Auth::id());
        }

        return $query->firstOrFail();
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
            if (! $selectedAddress) {
                return response()->json([
                    'error' => 'Silakan pilih alamat pengiriman terlebih dahulu.',
                ], 422);
            }

            $shippingCalculator = app(ShippingCalculator::class);
            $shippingData = $shippingCalculator->forAddress($selectedAddress);
            $shippingFee = $shippingData['cost'];
            $adminFee = Order::FIXED_ADMIN_FEE;

            $orderItemDetails = $itemDetails;
            if ($shippingFee > 0) {
                $orderItemDetails[] = [
                    'id' => 'shipping',
                    'price' => (int) round($shippingFee),
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim',
                ];
            }
            $orderItemDetails[] = [
                'id' => 'admin-fee',
                'price' => $adminFee,
                'quantity' => 1,
                'name' => 'Biaya Admin',
            ];

            \DB::transaction(function () use ($orderId, $totalAmount, $cart, $request, $orderItemDetails, $selectedAddress, $shippingFee, $shippingData, $adminFee, &$snapToken) {
                $order = Order::create([
                    'order_number' => $orderId,
                    'user_id' => Auth::id(),
                    'shipping_address_id' => $selectedAddress ? $selectedAddress->id : null,
                    'total_price' => $totalAmount + $shippingFee + $adminFee,
                    'shipping_fee' => $shippingFee,
                    'note' => $request->note,
                    'payment_status' => 'pending',
                    'item_status' => 'menunggu_pembayaran',
                ]);

                foreach ($cart as $id => $details) {
                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $id,
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                    ]);
                }

                $snapToken = $this->createSnapToken(
                    $order->order_number,
                    $order,
                    $orderItemDetails,
                    $request->name,
                    $request->email,
                    $selectedAddress
                );

                \App\Models\Payment::create([
                    'order_id' => $order->order_number,
                    'order_number' => $order->order_number,
                    'amount' => $totalAmount + $shippingFee + $adminFee,
                    'status' => 'pending',
                    'snap_token' => $snapToken,
                    'raw_response' => [
                        'selected_address' => $selectedAddress ? $selectedAddress->toArray() : null,
                        'admin_fee' => $adminFee,
                        'shipping' => [
                            'cost' => $shippingFee,
                            'distance' => $shippingData['distance'],
                        ],
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
        $order = $this->loadOrderWithAddress($order_id);

        return $this->renderPaymentStatusPage($order);
    }

    public function finish(Request $request)
    {
        $order = $this->loadOrderWithAddress($request->order_id, false);

        if ($order && $order->payment_status === 'success') {
            // Kirim notifikasi ke user
            $order->user->notify(new PaymentSuccessNotification($order));
        }

        return $this->renderPaymentStatusPage($order);
    }

    /**
     * Halaman redirect saat transaksi gagal/error.
     */
    public function failure($order_id)
    {
        $order = $this->loadOrderWithAddress($order_id);

        return $this->renderPaymentStatusPage($order);
    }

    /**
     * Halaman redirect ketika user menutup atau tidak menyelesaikan pembayaran.
     */
    public function unfinish($order_id)
    {
        $order = $this->loadOrderWithAddress($order_id);

        return $this->renderPaymentStatusPage($order);
    }

    public function retry($order_id)
    {
        $order = Order::with(['items.item', 'shippingAddress.province', 'shippingAddress.city', 'shippingAddress.district'])
            ->where('order_number', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_status === 'success') {
            return response()->json(['error' => 'Pesanan ini sudah dibayar.'], 422);
        }

        if ($order->items->isEmpty()) {
            return response()->json(['error' => 'Item pesanan tidak ditemukan.'], 404);
        }

        $itemDetails = [];
        foreach ($order->items as $orderItem) {
            if ($orderItem->item->stok < $orderItem->quantity) {
                return response()->json(['error' => 'Stok item ' . $orderItem->item->name . ' tidak mencukupi untuk pembayaran ulang.'], 422);
            }
            
            $itemDetails[] = [
                'id' => $orderItem->item->id,
                'price' => (int) $orderItem->price,
                'quantity' => (int) $orderItem->quantity,
                'name' => $orderItem->item->name,
            ];
        }

        if ($order->shipping_fee > 0) {
            $itemDetails[] = [
                'id' => 'shipping',
                'price' => (int) round($order->shipping_fee),
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        $adminFee = $order->admin_fee ?: Order::FIXED_ADMIN_FEE;
        $itemDetails[] = [
            'id' => 'admin-fee',
            'price' => $adminFee,
            'quantity' => 1,
            'name' => 'Biaya Admin',
        ];

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
                'admin_fee' => $adminFee,
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentSuccessNotification;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');

        Log::info('Midtrans webhook received', $request->all());

        // Validasi signature key untuk keamanan
        $hashed = hash(
            "sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashed !== $request->signature_key) {
            Log::error('Invalid Midtrans signature', [
                'order_id' => $request->order_id,
                'expected' => $hashed,
                'received' => $request->signature_key
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari payment berdasarkan order_id
        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            Log::error('Payment not found', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $latestPayment = Payment::where('order_number', $payment->order_number ?? $payment->order_id)->latest()->first();
        $isLatestAttempt = $latestPayment && $latestPayment->id === $payment->id;

        // Update payment status
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            
            // Pengecekan krusial: Pastikan status sebelumnya belum sukses
            // Ini mencegah pengiriman email berulang dan pengurangan stok ganda
            if ($payment->status !== 'success') {
                
                $payment->update([
                    'status' => 'success',
                    'transaction_id' => $request->transaction_id,
                    'payment_type' => $request->payment_type ?? null,
                    'raw_response' => json_encode($request->all()),
                ]);

                // Update order status
                $order = Order::where('order_number', $payment->order_number ?? $request->order_id)->first();
                if ($order && $order->payment_status !== 'success') {
                    $order->update([
                        'payment_status' => 'success',
                        'item_status' => 'sedang_dikemas'
                    ]);

                    // === 1. KIRIM NOTIFIKASI EMAIL DI SINI ===
                    try {
                        // Pastikan relasi user() ada di model Order
                        if ($order->user) {
                            $order->user->notify(new PaymentSuccessNotification($order));
                            Log::info('Email notification sent', ['order_id' => $order->order_number]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send email notification', [
                            'error' => $e->getMessage(), 
                            'order' => $order->order_number
                        ]);
                    }

                    // === 2. KURANGI STOK ===
                    try {
                        if ($order->items) {
                            foreach ($order->items as $orderItem) {
                                $item = $orderItem->item;
                                if ($item) {
                                    $newStock = max(0, (int) $item->stok - (int) $orderItem->quantity);
                                    $item->update(['stok' => $newStock]);
                                    Log::info('Stock decremented', ['item_id' => $item->id, 'decreased_by' => $orderItem->quantity, 'new_stock' => $newStock]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to decrement stock', ['error' => $e->getMessage(), 'order' => $order->order_number]);
                    }
                }

                Log::info('Payment successful and processed', ['order_id' => $request->order_id]);
            } else {
                Log::info('Payment already marked as success, ignored', ['order_id' => $request->order_id]);
            }

        } elseif ($transactionStatus == 'pending') {
            $payment->update([
                'status' => 'pending',
                'transaction_id' => $request->transaction_id ?? $payment->transaction_id,
                'payment_type' => $request->payment_type ?? $payment->payment_type,
                'raw_response' => $request->all(),
            ]);
            if ($isLatestAttempt) {
                Order::where('order_number', $payment->order_number ?? $request->order_id)->update([
                    'payment_status' => 'pending',
                    'item_status' => 'menunggu_pembayaran',
                ]);
            }
            Log::info('Payment pending', ['order_id' => $request->order_id]);

        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $payment->update([
                'status' => 'failed',
                'transaction_id' => $request->transaction_id ?? $payment->transaction_id,
                'payment_type' => $request->payment_type ?? $payment->payment_type,
                'raw_response' => $request->all(),
            ]);
            if ($isLatestAttempt) {
                Order::where('order_number', $payment->order_number ?? $request->order_id)->update([
                    'payment_status' => 'failed',
                    'item_status' => 'pembayaran_gagal',
                ]);
            }
            Log::info('Payment failed', ['order_id' => $request->order_id, 'reason' => $transactionStatus]);
        }

        return response()->json(['message' => 'Callback received and processed']);
    }
}

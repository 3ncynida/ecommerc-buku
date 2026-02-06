<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        
        Log::info('Midtrans webhook received', $request->all());
        
        // Validasi signature key untuk keamanan
        $hashed = hash("sha512", 
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

        // Update payment status
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            $payment->update([
                'status' => 'success',
                'transaction_id' => $request->transaction_id,
                'payment_type' => $request->payment_type ?? null,
                'raw_response' => json_encode($request->all()),
            ]);

            // Update order status
            Order::where('order_number', $request->order_id)->update([
                'payment_status' => 'success',
                'item_status' => 'diproses'
            ]);

            Log::info('Payment successful', ['order_id' => $request->order_id]);

        } elseif ($transactionStatus == 'pending') {
            $payment->update(['status' => 'pending']);
            Log::info('Payment pending', ['order_id' => $request->order_id]);

        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $payment->update(['status' => 'failed']);
            Order::where('order_number', $request->order_id)->update(['payment_status' => 'failed']);
            Log::info('Payment failed', ['order_id' => $request->order_id, 'reason' => $transactionStatus]);
        }

        return response()->json(['message' => 'Callback received and processed']);
    }
}
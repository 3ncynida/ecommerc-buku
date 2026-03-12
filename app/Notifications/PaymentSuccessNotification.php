<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Sangat penting agar masuk antrean
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    // Masukkan data order ke dalam notifikasi
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // Tentukan bahwa notifikasi ini dikirim via email
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    // Desain isi invoice di sini
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invoice Pembayaran - Libris #' . $this->order->order_number)
            ->markdown('emails.invoice', [
                'order' => $this->order,
                'payment' => $this->order->payment,
                'shippingAddress' => $this->order->shippingAddress,
                'recipientName' => $notifiable->name,
            ]);
    }
}

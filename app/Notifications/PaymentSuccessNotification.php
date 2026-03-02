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

    // Desain isi emailnya di sini
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(subject: 'Pembayaran Berhasil - Libris #' . $this->order->order_number)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih telah berbelanja di Libris.')
            ->line('Pembayaran untuk pesanan nomor **' . $this->order->order_number . '** telah kami terima.')
            ->line('Total Pembayaran: Rp ' . number_format($this->order->total_price, 0, ',', '.'))
            ->action('Lihat Detail Pesanan', url('/orders/' . $this->order->order_number))
            ->line('Kami akan segera memproses pengiriman buku Anda!')
            ->salutation('Salam hangat, Tim Libris');
    }
}
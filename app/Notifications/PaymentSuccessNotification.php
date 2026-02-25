<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Pembayaran Berhasil - ' . $this->order->order_number)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih telah berbelanja di Libris.')
            ->line('Pembayaran untuk pesanan #' . $this->order->order_number . ' telah kami terima.')
            ->action('Lihat Detail Pesanan', url('/orders/' . $this->order->id))
            ->line('Kami akan segera memproses pengiriman buku Anda!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

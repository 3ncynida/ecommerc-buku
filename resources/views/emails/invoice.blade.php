@component('mail::message')

# Invoice Pembayaran — Libris #{{ $order->order_number }}

Halo, {{ $recipientName }}!

Terima kasih telah menyelesaikan pembayaran. Berikut ringkasan faktur pesanan Anda:

| Detail | Keterangan |
| --- | --- |
| **Tanggal Invoice** | {{ optional($order->created_at)->format('d F Y, H:i') }} |
| **Status Pembayaran** | {{ ucfirst($order->payment_status ?? 'pending') }} |
| **Metode Pembayaran** | {{ optional($payment)->payment_type ?? 'Tidak tersedia' }} |
| **Catatan** | {{ $order->note ?: 'Tidak ada' }} |

@component('mail::table')
| Buku | Jumlah | Harga Satuan | Subtotal |
| --- | :---: | ---: | ---: |
| {{ optional($order->item)->name ?? 'Produk tidak ditemukan' }} | {{ $order->quantity }} | Rp {{ number_format(optional($order->item)->price ?? 0, 0, ',', '.') }} | Rp {{ number_format($order->total_price, 0, ',', '.') }} |
@endcomponent

**Total Pembayaran:** Rp {{ number_format($order->total_price, 0, ',', '.') }}

@if($shippingAddress)
@component('mail::panel')
**Alamat Pengiriman**  
{{ $shippingAddress->recipient_name ?? 'Penerima tidak tersedia' }}  
{{ $shippingAddress->full_address ?? 'Alamat lengkap belum diisi' }}  
{{ optional($shippingAddress->district)->name }}, {{ optional($shippingAddress->city)->name }}  
{{ optional($shippingAddress->province)->name }} {{ $shippingAddress->postal_code ?? '' }}  
Telp: {{ $shippingAddress->phone_number ?? 'Tidak tersedia' }}
@endcomponent
@endif

@component('mail::button', ['url' => route('orders.show', $order)])
Lihat detail pesanan
@endcomponent

Kami akan segera mengurus pengiriman buku Anda. Jika ada pertanyaan, balas email ini atau hubungi tim layanan pelanggan.

Salam hangat,  
Tim Libris
@endcomponent

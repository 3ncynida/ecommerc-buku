@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-5xl mx-auto px-8">
            @php
                $paymentBadgeClasses = [
                    'success' => 'bg-green-100 text-green-600',
                    'pending' => 'bg-orange-100 text-orange-600',
                    'failed' => 'bg-red-100 text-red-600',
                    'cancelled' => 'bg-gray-100 text-gray-600',
                ];
                $paymentLabels = [
                    'success' => 'Lunas',
                    'pending' => 'Menunggu Pembayaran',
                    'failed' => 'Pembayaran Gagal',
                    'cancelled' => 'Dibatalkan',
                ];
            @endphp
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-gray-900">Pesanan Saya</h1>
                <p class="text-gray-500">Pantau status pengiriman buku favorit Anda</p>
            </div>

            @if(session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-emerald-700 font-bold">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-6 py-4 text-rose-700 font-bold">
                    {{ session('error') }}
                </div>
            @endif

            @if($orders->isEmpty())
                {{-- Tampilan Kosong --}}
                <div class="bg-white p-20 rounded-[40px] border border-dashed border-gray-200 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-8">Sepertinya Anda belum melakukan pembelian apapun.</p>
                    <a href="/"
                        class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white p-8 rounded-[35px] border border-gray-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

                                {{-- Sisi Kiri: Info Pesanan --}}
                                <div class="flex gap-6">
                                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-receipt text-indigo-500 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">ID Pesanan</p>
                                        <h3 class="font-bold text-gray-900 text-lg uppercase tracking-tight">
                                            {{ $order->order_number }}
                                        </h3>
                                        <div class="flex items-center gap-3 mt-1">
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                                    {{ $paymentBadgeClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Sisi Kanan: Harga & Aksi Utama --}}
                                <div class="flex flex-col items-end gap-3">
                                    <p class="text-2xl font-black text-indigo-600">
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                    @if(in_array($order->payment_status, ['pending', 'failed']))
                                        <div class="flex flex-col gap-2 w-full">
                                            <button
                                                onclick="retryPayment('{{ $order->order_number }}')"
                                                class="w-full bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-credit-card"></i>
                                                {{ $order->payment_status === 'failed' ? 'Coba Bayar Lagi' : 'Bayar Sekarang' }}
                                            </button>
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full border border-gray-200 rounded-xl text-sm font-bold px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
                                                    Batalkan Pesanan
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer Kartu --}}
                            <div class="mt-8 pt-6 border-t border-gray-50 flex flex-wrap justify-between items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400 font-medium">Metode Pembayaran:</span>
                                    <span
                                        class="text-sm font-bold text-gray-800 uppercase bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                                        {{ $order->payment->payment_type ?? 'Belum dibayar' }}
                                    </span>
                                </div>

                                <a href="/orders/{{ $order->order_number }}"
                                    class="text-indigo-600 font-bold hover:text-indigo-800 text-sm flex items-center gap-2 group">
                                    Lihat Detail Transaksi
                                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Script Midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        function retryPayment(orderId) {
            fetch("{{ route('payment.retry', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: function () { window.location.href = "{{ url('/payment/success') }}/" + orderId; },
                        onPending: function () {
                            window.location.href = "{{ url('/payment/unfinish') }}/" + orderId;
                        },
                        onError: function () {
                            window.location.href = "{{ url('/payment/failure') }}/" + orderId;
                        },
                        onClose: function () {
                            window.location.href = "{{ url('/payment/unfinish') }}/" + orderId;
                        }
                    });
                })
                .catch(() => alert('Gagal memulai pembayaran ulang.'));
        }
    </script>
@endsection

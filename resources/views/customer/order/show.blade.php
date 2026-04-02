@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4">

            {{-- Header & Status --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <a href="{{ route('orders.index') }}"
                        class="text-sm font-bold text-gray-400 hover:text-indigo-600 transition flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Pesanan Saya
                    </a>
                    <h1 class="text-2xl font-black text-gray-900">Detail Transaksi #{{ $order->order_number }}</h1>
                </div>

                @php
                    $statusClasses = [
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'success' => 'bg-green-50 text-green-600 border-green-100',
                        'failed' => 'bg-red-50 text-red-600 border-red-100',
                        'cancelled' => 'bg-gray-100 text-gray-600 border-gray-200',
                    ];
                    $paymentLabels = [
                        'pending' => 'Menunggu Pembayaran',
                        'success' => 'Pembayaran Berhasil',
                        'failed' => 'Pembayaran Gagal',
                        'cancelled' => 'Dibatalkan',
                    ];
                    $itemStatusLabels = [
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'pembayaran_gagal' => 'Pembayaran Gagal',
                        'menunggu_kurir' => 'Menunggu Kurir',
                        'diproses_kurir' => 'Diproses Kurir',
                        'dikirim' => 'Dalam Pengiriman',
                        'sampai' => 'Sampai Tujuan',
                        'selesai' => 'Selesai',
                        'gagal' => 'Gagal Pengiriman',
                        'dibatalkan' => 'Dibatalkan',
                    ];
                    $paymentFailureReason = $order->payment?->raw_response['status_message']
                        ?? $order->payment?->raw_response['transaction_status']
                        ?? null;
                @endphp
                <div
                    class="px-6 py-2 rounded-full border text-xs font-black uppercase tracking-widest {{ $statusClasses[$order->payment_status] ?? 'bg-gray-100' }}">
                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                </div>
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

            @if(in_array($order->payment_status, ['pending', 'failed']))
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="flex justify-end mb-4">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2 rounded-full border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-100 transition">
                        Batalkan Pesanan
                    </button>
                </form>
            @endif

            <div class="grid grid-cols-1 gap-8">
                <div class="bg-white rounded-[30px] shadow-sm border border-gray-100 p-8">
                    <h2 class="text-sm font-black uppercase tracking-tighter text-gray-400 mb-6">Produk yang Dibeli</h2>

                    <div class="flex flex-col gap-6">
                        @foreach($order->items as $orderItem)
                        <div class="flex flex-col md:flex-row gap-6 {{ !$loop->last ? 'pb-6 border-b border-dashed border-gray-100' : '' }}">
                            <div class="w-24 h-32 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 shrink-0">
                                <img src="{{ asset('storage/' . $orderItem->item->image) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold text-indigo-600 uppercase mb-1">{{ $orderItem->item->author->name }}</p>
                                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $orderItem->item->name }}</h3>

                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Harga Satuan</p>
                                        <p class="text-sm font-bold text-gray-900">Rp{{ number_format($orderItem->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Jumlah</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $orderItem->quantity }}x</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Subtotal Item</p>
                                        <p class="text-sm font-black text-indigo-600">Rp{{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- TAMBAHAN: BAGIAN CATATAN PEMBELI --}}
                        <div class="mt-2 pt-2">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-2 flex items-center gap-2">
                                <i class="fa-regular fa-note-sticky text-indigo-500"></i> Catatan Anda
                            </p>
                            @if($order->note)
                                <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-600 italic leading-relaxed border border-gray-100">
                                    "{{ $order->note }}"
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">Tidak ada catatan untuk pesanan ini.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 2. Alamat & Pengiriman --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-[30px] shadow-sm border border-gray-100 p-8">
                        <h2 class="text-sm font-black uppercase tracking-tighter text-gray-400 mb-6 text-indigo-500">Alamat Pengiriman</h2>

                        @if ($order->shippingAddress)
                            <p class="font-bold text-gray-900 mb-1">{{ $order->shippingAddress->recipient_name }}</p>
                            <p class="text-sm text-gray-500 mb-3">{{ $order->shippingAddress->phone_number }}</p>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ $order->shippingAddress->full_address }}<br>
                                {{ $order->shippingAddress->district->name ?? '' }},
                                {{ $order->shippingAddress->city->name ?? '' }},
                                {{ $order->shippingAddress->province->name ?? '' }}
                                <br>
                                <span class="font-bold text-gray-900">Kode Pos: {{ $order->shippingAddress->postal_code }}</span>
                            </p>

                            @if(optional($deliveryEstimate)->hasValue())
                                <div class="mt-4 rounded-2xl border border-indigo-100 bg-indigo-50/40 p-4">
                                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-500 mb-1">
                                        Estimasi Sampai
                                    </p>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ $deliveryEstimate->formattedDuration() }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Jarak sekitar {{ $deliveryEstimate->formattedDistance() }} dari {{ config('store.address') }}
                                    </p>
                                    @if($eta = $deliveryEstimate->arrivalAt())
                                        <p class="text-xs text-gray-400 mt-1">
                                            Perkiraan tiba {{ $eta->format('d F Y, H:i') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-400 italic">Data alamat tidak tersedia.</p>
                        @endif
                    </div>

                    <div class="bg-white rounded-[30px] shadow-sm border border-gray-100 p-8 flex flex-col justify-between">
                        <div>
                            <h2 class="text-sm font-black uppercase tracking-tighter text-gray-400 mb-6 text-indigo-500">Status Logistik</h2>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                                    <i class="fa-solid fa-truck-fast text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $itemStatusLabels[$order->item_status] ?? strtoupper(str_replace('_', ' ', $order->item_status)) }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">Diperbarui pada {{ $order->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @if ($order->payment_status === 'failed' && $paymentFailureReason)
                                <div class="mt-4 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-rose-500 mb-1">Keterangan Pembayaran</p>
                                    <p class="text-sm text-rose-700">{{ $paymentFailureReason }}</p>
                                </div>
                            @endif
                            @if (in_array($order->payment_status, ['pending', 'failed']))
                                <button type="button" onclick="retryPayment('{{ $order->order_number }}')"
                                    class="mt-4 w-full rounded-2xl bg-indigo-600 text-white font-bold py-3 hover:bg-indigo-700 transition">
                                    <i class="fa-solid fa-credit-card mr-2"></i>
                                    {{ $order->payment_status === 'failed' ? 'Coba Bayar Lagi' : 'Lanjutkan Pembayaran' }}
                                </button>
                            @endif
                            <div class="mt-5 border-t border-dashed border-gray-100 pt-4">
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-2">Info Kurir</p>
                                @if ($order->courier)
                                    <p class="text-sm font-bold text-gray-900">{{ $order->courier->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->courier->email }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Kurir belum ditugaskan.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-[30px] shadow-sm border border-gray-100 p-8">
                        <h2 class="text-sm font-black uppercase tracking-tighter text-gray-400 mb-6 text-indigo-500">Bukti Foto Pengiriman</h2>

                        @if($order->item_status === 'gagal')
                            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-700 mb-4">
                                <p class="text-[10px] uppercase tracking-[0.2em] font-bold mb-2">Pengiriman Gagal</p>
                                <p>{{ $order->courier_note ?? 'Alasan gagal belum diisi.' }}</p>
                            </div>
                        @endif

                        @if($order->delivery_proof_path)
                            <a href="{{ asset('storage/' . $order->delivery_proof_path) }}" target="_blank" class="block rounded-3xl overflow-hidden border border-gray-200 bg-gray-50 mb-4">
                                <img src="{{ asset('storage/' . $order->delivery_proof_path) }}" alt="Bukti {{ $order->order_number }}" class="w-full h-48 object-cover object-center">
                            </a>
                            <p class="text-sm text-gray-500">Catatan dari kurir: {{ $order->courier_note ?? 'Tidak ada catatan tambahan.' }}</p>
                            @if($order->item_status === 'sampai')
                                <form action="{{ route('orders.confirm', ['order' => $order->order_number]) }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit" class="w-full rounded-2xl bg-green-600 text-white font-bold py-3 hover:bg-green-700 transition">
                                        <i class="fa-solid fa-check-circle mr-2"></i>
                                        Konfirmasi Selesai
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-200 px-4 py-5 text-sm text-gray-500 text-center">
                                Kurir belum mengunggah foto bukti pengiriman.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 3. Ringkasan Pembayaran --}}
                <div class="bg-indigo-900 rounded-[30px] p-8 text-white shadow-xl shadow-indigo-100">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Total Pembayaran</p>
                            <p class="text-4xl font-black text-white">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md p-5 rounded-2xl border border-white/10">
                            <i class="fa-solid fa-shield-check text-2xl text-indigo-300"></i>
                            <div>
                                <p class="text-[10px] font-bold uppercase opacity-60">Metode Pembayaran</p>
                                <p class="text-sm font-bold">Midtrans Secure Payment</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-white/70">
                        Ongkos Kirim: Rp{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        onSuccess: function () { window.location.href = "{{ route('payment.success', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); },
                        onPending: function () { window.location.href = "{{ route('payment.unfinish', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); },
                        onError: function () { window.location.href = "{{ route('payment.failure', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); },
                        onClose: function () { window.location.href = "{{ route('payment.unfinish', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); }
                    });
                })
                .catch(() => alert('Gagal memulai pembayaran.'));
        }
    </script>
@endsection

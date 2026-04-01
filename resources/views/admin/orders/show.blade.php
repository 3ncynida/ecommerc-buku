@extends('admin.admin-layout')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10 px-8">
        <div class="max-w-6xl mx-auto">
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl flex items-center gap-3 animate-fade-in">
                    <i class="fa-solid fa-circle-check"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center gap-3 animate-fade-in">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif
            @php
                $itemStatuses = [
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    'pembayaran_gagal' => 'Pembayaran Gagal',
                    'menunggu_kurir' => 'Menunggu Kurir',
                    'diproses_kurir' => 'Diproses',
                    'dikirim' => 'Dalam Pengiriman',
                    'sampai' => 'Sampai Tujuan',
                    'selesai' => 'Selesai',
                    'gagal' => 'Gagal Pengiriman',
                ];
                $currentItemStatusLabel = $itemStatuses[$order->item_status] ?? 'Status Tidak Dikenal';
            @endphp

            {{-- Header Detail --}}
            <div class="flex flex-col gap-4 mb-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.orders.index') }}"
                        class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center border border-gray-100 shadow-sm hover:text-indigo-600 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->order_number }}</h1>
                        <p class="text-sm text-gray-400 font-medium italic">Dipesan pada
                            {{ $order->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

            </div>

            <div class="mb-8">
                <div class="bg-white rounded-[30px] border border-gray-100 shadow-sm p-6 space-y-3">
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400 font-bold">Rangkaian Status Kurir</p>
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-3">
                        @foreach($itemStatuses as $key => $label)
                            <div class="rounded-2xl border px-4 py-3 text-center text-xs font-bold uppercase tracking-[0.2em]
                                {{ $order->item_status === $key ? 'bg-indigo-600 border-indigo-600 text-white shadow' : 'bg-white text-gray-500 border-gray-100' }}">
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Kolom Kiri: Detail Produk --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[30px] border border-gray-100 shadow-sm p-8">
                        <h3 class="font-bold text-gray-900 mb-6">Item yang Dibeli</h3>

                        {{-- Langsung akses $order->item tanpa @foreach --}}
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-32 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                <img src="{{ asset('storage/' . $order->item->image) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ $order->item->name }}
                                </h4>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">
                                    {{ $order->item->author->name }}
                                </p>
                                <div class="mt-4 flex items-center gap-4">
                                    <div class="text-sm">
                                        <span class="text-gray-400">Jumlah:</span>
                                        <span class="font-bold text-gray-900 ml-1">{{ $order->quantity }}</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-400">Harga Satuan:</span>
                                        <span
                                            class="font-bold text-gray-900 ml-1">Rp{{ number_format($order->item->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1">Total Item</p>
                                {{-- Gunakan total_price dari tabel orders --}}
                                <p class="font-black text-indigo-600 text-xl">
                                    Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Ringkasan Biaya --}}
                        <div class="mt-10 pt-8 border-t border-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 font-medium">Total Pembayaran</span>
                                <span
                                    class="text-2xl font-black text-gray-900">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Info Pelanggan & Alamat --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-[30px] border border-gray-100 shadow-sm p-8">
                        <h3 class="font-bold text-gray-900 mb-6">Informasi Pelanggan</h3>
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                {{ substr($order->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-gray-50">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Alamat Pengiriman</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    @if ($order->shippingAddress)
                                        {{-- Menampilkan Nama Penerima --}}
                                        <p class="font-bold text-gray-900 mb-1">
                                            Penerima : {{ $order->shippingAddress->recipient_name }}
                                        </p>

                                        {{-- Menampilkan Nomor Telepon --}}
                                        <p class="text-sm text-gray-500 mb-3">
                                            Nomor : {{ $order->shippingAddress->phone_number }}
                                        </p>

                                        {{-- Menampilkan Alamat Lengkap & Wilayah --}}
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    {{ $order->shippingAddress->full_address }}<br>

                                    {{-- Pastikan memanggil ->name agar tidak muncul JSON --}}
                                    {{ $order->shippingAddress->district->name ?? '' }},
                                    {{ $order->shippingAddress->city->name ?? '' }},
                                    {{ $order->shippingAddress->province->name ?? '' }}

                                    <br>
                                    <span class="font-bold">Kode Pos:
                                        {{ $order->shippingAddress->postal_code }}</span>
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
                                </p>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>

                <div class="lg:col-span-2 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="bg-white rounded-[30px] border border-gray-100 shadow-sm p-8 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[10px] tracking-[0.3em] uppercase text-gray-400 font-bold">Kurir Penugasan</p>
                                @if ($order->courier)
                                    <h4 class="text-lg font-bold text-gray-900">{{ $order->courier->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $order->courier->email }}</p>
                                @else
                                    <h4 class="text-lg font-bold text-gray-900">Belum ditugaskan</h4>
                                    <p class="text-sm text-gray-500">Cari kurir yang tersedia untuk memproses pesanan ini.</p>
                                @endif
                            </div>
                            <div class="inline-flex items-center justify-center rounded-full border border-indigo-100 px-3 py-1 text-xs font-bold text-indigo-600">
                                <i class="fa-solid fa-truck-arrow-right mr-1"></i>
                                {{ $currentItemStatusLabel }}
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p class="font-bold text-gray-900">Catatan Kurir</p>
                            <p class="text-gray-500">{{ $order->courier_note ?? 'Tidak ada catatan tambahan.' }}</p>
                        </div>
                        @if ($order->payment_status === 'success' && $order->item_status === 'gagal')
                            <form action="{{ route('admin.orders.reassign', $order) }}" method="POST" class="pt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white hover:bg-indigo-700 transition">
                                    Tugaskan Ulang Ke Antrian Kurir
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="bg-white rounded-[30px] border border-gray-100 shadow-sm p-8 space-y-4">
                        <h3 class="text-sm font-black uppercase tracking-[0.3em] text-indigo-500">Bukti Foto Pengiriman</h3>
                        @if ($order->delivery_proof_path)
                            <a href="{{ asset('storage/' . $order->delivery_proof_path) }}" target="_blank" class="block rounded-3xl overflow-hidden border border-gray-200 bg-gray-50">
                                <img src="{{ asset('storage/' . $order->delivery_proof_path) }}" alt="Bukti {{ $order->order_number }}" class="w-full h-48 object-cover object-center">
                            </a>
                            <p class="text-sm text-gray-500">Catatan kurir: {{ $order->courier_note ?? 'Tidak ada catatan tambahan.' }}</p>
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-200 px-4 py-5 text-sm text-gray-500 text-center">
                                Kurir belum mengunggah foto bukti pengiriman.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-indigo-900 rounded-[30px] p-8 text-white shadow-xl shadow-indigo-100">
                    <p class="text-[10px] font-bold text-indigo-300 uppercase mb-2">Metode Pembayaran</p>
                    <h4 class="font-bold text-lg mb-4">Midtrans Payment Gateway</h4>
                    <div class="flex items-center gap-2 bg-indigo-800/50 rounded-xl px-4 py-2 border border-indigo-700">
                        <i class="fa-solid fa-shield-check text-indigo-300"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">{{ strtoupper($order->payment_status) }}</span>
                    </div>
                    @if ($order->payment_status === 'failed')
                        <div class="mt-4 rounded-2xl bg-rose-500/15 border border-rose-300/20 px-4 py-3">
                            <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-rose-200 mb-1">Alasan Gagal</p>
                            <p class="text-sm text-white">{{ $order->payment?->raw_response['status_message'] ?? $order->payment?->raw_response['transaction_status'] ?? 'Tidak ada detail dari payment gateway.' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

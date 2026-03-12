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
                    ];
                @endphp
                <div
                    class="px-6 py-2 rounded-full border text-xs font-black uppercase tracking-widest {{ $statusClasses[$order->payment_status] ?? 'bg-gray-100' }}">
                    {{ $order->payment_status }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                {{-- 1. Informasi Produk --}}
                <div class="bg-white rounded-[30px] shadow-sm border border-gray-100 p-8">
                    <h2 class="text-sm font-black uppercase tracking-tighter text-gray-400 mb-6">Produk yang Dibeli</h2>

                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-24 h-32 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 shrink-0">
                            <img src="{{ asset('storage/' . $order->item->image) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-indigo-600 uppercase mb-1">{{ $order->item->author->name }}</p>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $order->item->name }}</h3>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Harga</p>
                                    <p class="text-sm font-bold text-gray-900">Rp{{ number_format($order->item->price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Jumlah</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $order->quantity }}x</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Subtotal</p>
                                    <p class="text-sm font-black text-indigo-600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- TAMBAHAN: BAGIAN CATATAN PEMBELI --}}
                            <div class="mt-6 pt-6 border-t border-dashed border-gray-100">
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
                </div>

                {{-- 2. Alamat & Pengiriman --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $order->item_status }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">Diperbarui pada {{ $order->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
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
                </div>
            </div>
        </div>
    </div>
@endsection
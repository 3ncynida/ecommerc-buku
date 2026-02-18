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
            {{-- Header Detail --}}
            <div class="flex items-center justify-between mb-8">
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

                {{-- Form Update Status --}}
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="flex gap-3">
                    @csrf
                    @method('PATCH')
                    <select name="status"
                        class="bg-white border-gray-200 rounded-2xl text-sm font-bold px-5 py-2.5 focus:ring-indigo-500">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit"
                        class="bg-gray-900 text-white px-6 py-2.5 rounded-2xl font-bold hover:bg-black transition">Update</button>
                </form>
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
                                    @else
                                        <p class="text-sm text-gray-400 italic">Data alamat tidak tersedia.</p>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Status Pembayaran --}}
                    <div class="bg-indigo-900 rounded-[30px] p-8 text-white shadow-xl shadow-indigo-100">
                        <p class="text-[10px] font-bold text-indigo-300 uppercase mb-2">Metode Pembayaran</p>
                        <h4 class="font-bold text-lg mb-4">Midtrans Payment Gateway</h4>
                        <div class="flex items-center gap-2 bg-indigo-800/50 rounded-xl px-4 py-2 border border-indigo-700">
                            <i class="fa-solid fa-shield-check text-indigo-300"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Transaksi Aman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

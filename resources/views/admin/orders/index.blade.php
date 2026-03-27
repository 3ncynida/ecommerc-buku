@extends('admin.admin-layout')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10 px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Daftar Pesanan</h1>
                    <p class="text-sm text-gray-400 font-medium">Kelola dan pantau semua transaksi pelanggan</p>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-[30px] border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase">ID Pesanan</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase">Pelanggan</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase">Tanggal</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase">Total</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase">Status</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-8 py-5 font-bold text-gray-900">#{{ $order->order_number }}</td>
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-bold text-gray-900">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-sm text-gray-500">
                                        {{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-8 py-5 font-black text-gray-900">
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-8 py-5">
                                        @php
                                            $statusColors = [
                                                'menunggu_pembayaran' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'pembayaran_gagal' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                'menunggu_kurir' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'diproses_kurir' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'sampai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'selesai' => 'bg-gray-100 text-gray-500 border-gray-200',
                                                'gagal' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            ];
                                            $statusLabels = [
                                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                                'pembayaran_gagal' => 'Pembayaran Gagal',
                                                'menunggu_kurir' => 'Menunggu Kurir',
                                                'diproses_kurir' => 'Diproses Kurir',
                                                'dikirim' => 'Dalam Pengiriman',
                                                'sampai' => 'Sampai Tujuan',
                                                'selesai' => 'Selesai',
                                                'gagal' => 'Gagal Pengiriman',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusColors[$order->item_status] ?? 'bg-gray-50 text-gray-600' }}">
                                            {{ $statusLabels[$order->item_status] ?? strtoupper(str_replace('_', ' ', $order->item_status)) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-8 py-6 border-t border-gray-50">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

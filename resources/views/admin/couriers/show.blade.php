@extends('admin.admin-layout')
@section('title', 'Detail Kurir')

@section('content')
    @php
        $statusColors = [
            'menunggu_kurir' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
            'diproses_kurir' => 'bg-amber-50 text-amber-600 border-amber-100',
            'dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
            'sampai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'selesai' => 'bg-gray-100 text-gray-500 border-gray-200',
            'gagal' => 'bg-rose-50 text-rose-600 border-rose-100',
        ];
    @endphp

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Profil Kurir</p>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $courier->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $courier->email }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('couriers.edit', $courier->id) }}"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                        Edit Kurir
                    </a>
                    <a href="{{ route('couriers.index') }}"
                        class="px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Total Ditugaskan</p>
                <p class="text-2xl font-black text-gray-900 mt-2">{{ $totalAssigned }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Selesai</p>
                <p class="text-2xl font-black text-emerald-600 mt-2">{{ $completed }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Gagal</p>
                <p class="text-2xl font-black text-rose-600 mt-2">{{ $failed }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Dalam Proses</p>
                <p class="text-2xl font-black text-amber-600 mt-2">{{ $inProgress }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Success Rate</p>
                <p class="text-2xl font-black text-indigo-600 mt-2">{{ $successRate }}%</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Riwayat Pengiriman</h3>
                <span class="text-sm text-gray-400">Total {{ $orders->total() }} pesanan</span>
            </div>

            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Item</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900">#{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $order->item->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusColors[$order->item_status] ?? 'bg-gray-50 text-gray-600' }}">
                                    {{ $order->item_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $order->updated_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">
                                Belum ada riwayat pengiriman untuk kurir ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6 px-4 pb-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection

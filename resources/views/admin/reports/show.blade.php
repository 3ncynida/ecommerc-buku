@extends('admin.admin-layout')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Laporan Penjualan</h3>
            <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                <i class="fa-solid fa-print mr-2"></i>Cetak Laporan
            </button>
        </div>

        <div class="mb-4">
            <p><strong>Periode:</strong> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
            <p><strong>Total Pesanan:</strong> {{ $totalOrders }}</p>
            <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>

        <div class="overflow-x-auto">
            @php
                $statusColors = [
                    'menunggu_kurir' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    'diproses_kurir' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                    'sampai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'selesai' => 'bg-gray-100 text-gray-500 border-gray-200',
                ];
            @endphp
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-2 px-4 border-b text-left">No. Pesanan</th>
                        <th class="py-2 px-4 border-b text-left">Pelanggan</th>
                        <th class="py-2 px-4 border-b text-left">Buku</th>
                        <th class="py-2 px-4 border-b text-left">Tanggal</th>
                        <th class="py-2 px-4 border-b text-left">Total</th>
                        <th class="py-2 px-4 border-b text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ $order->order_number }}</td>
                            <td class="py-2 px-4 border-b">{{ $order->user->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $order->item->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 px-4 border-b">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 border-b">
                                @php
                                    $statusLabel = strtoupper(str_replace('_', ' ', $order->item_status));
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full border {{ $statusColors[$order->item_status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->isEmpty())
            <p class="text-gray-500 mt-4">Tidak ada pesanan dalam periode ini.</p>
        @endif
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .bg-white.rounded-lg.shadow.p-6,
            .bg-white.rounded-lg.shadow.p-6 * {
                visibility: visible;
            }

            .bg-white.rounded-lg.shadow.p-6 {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            button {
                display: none;
            }
        }
    </style>
@endsection

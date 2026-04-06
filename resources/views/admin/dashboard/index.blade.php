@extends('admin.admin-layout')
@section('title', 'Admin Analytics Dashboard')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @php
        $logisticOrders = $orders->where('payment_status', 'success');
        $statusStyles = [
            'menunggu_pembayaran' => 'bg-amber-50 text-amber-600 border-amber-100',
            'pembayaran_gagal' => 'bg-rose-50 text-rose-600 border-rose-100',
            'sedang_dikemas' => 'bg-purple-50 text-purple-600 border-purple-100',
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
            'sedang_dikemas' => 'Sedang Dikemas',
            'menunggu_kurir' => 'Menunggu Kurir',
            'diproses_kurir' => 'Diproses Kurir',
            'dikirim' => 'Dalam Pengiriman',
            'sampai' => 'Sampai Tujuan',
            'selesai' => 'Selesai',
            'gagal' => 'Gagal Pengiriman',
        ];
    @endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                <h4 class="text-2xl font-black text-indigo-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-emerald-600 font-bold">
            <i class="fa-solid fa-arrow-up mr-1"></i>
            <span>Statistik Real-time</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pesanan</p>
                <h4 class="text-2xl font-black text-gray-800 mt-1">{{ $totalOrders }}</h4>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-box text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-amber-600 font-bold">
            <i class="fa-solid fa-clock mr-1"></i>
            <span>{{ $pendingOrders }} Menunggu Pembayaran</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pelanggan</p>
                <h4 class="text-2xl font-black text-gray-800 mt-1">{{ $totalCustomers }}</h4>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-emerald-600 font-bold">
            <i class="fa-solid fa-user-plus mr-1"></i>
            <span>User Terdaftar</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Koleksi Buku</p>
                <h4 class="text-2xl font-black text-gray-800 mt-1">{{ $totalItems }}</h4>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-book-open text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-blue-600 font-bold">
            <i class="fa-solid fa-truck-fast mr-1"></i>
            <span>{{ $shippingOrders }} Perlu Diproses</span>
        </div>
    </div>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-black text-gray-800 text-lg uppercase tracking-tight">Tren Penjualan (7 Hari Terakhir)</h3>
            </div>
            <canvas id="salesChart" height="120"></canvas>
        </div>

        <!-- Quick Actions / Mini Stats -->
        <div class="bg-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-xl mb-4 text-indigo-100">Ringkasan Logistik</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-indigo-800/50 p-3 rounded-xl">
                        <span class="text-sm font-medium">Sedang Dikemas</span>
                        <span class="px-2.5 py-1 bg-white/10 rounded-lg font-bold">{{ $logisticOrders->where('item_status', 'sedang_dikemas')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-800/50 p-3 rounded-xl">
                        <span class="text-sm font-medium">Menunggu Kurir</span>
                        <span class="px-2.5 py-1 bg-white/10 rounded-lg font-bold">{{ $logisticOrders->where('item_status', 'menunggu_kurir')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-800/50 p-3 rounded-xl">
                        <span class="text-sm font-medium">Diproses Kurir</span>
                        <span class="px-2.5 py-1 bg-white/10 rounded-lg font-bold">{{ $logisticOrders->where('item_status', 'diproses_kurir')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-800/50 p-3 rounded-xl">
                        <span class="text-sm font-medium">Dalam Pengiriman</span>
                        <span class="px-2.5 py-1 bg-white/10 rounded-lg font-bold">{{ $logisticOrders->whereIn('item_status', ['dikirim', 'sampai'])->count() }}</span>
                    </div>
                </div>
                <a href="/admin/orders">
                    <button class="w-full mt-6 py-3 bg-white text-indigo-900 font-black rounded-xl hover:bg-indigo-50 transition shadow-lg">
                        LIHAT SEMUA PESANAN
                    </button>
                </a>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>
    </div>


    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-gray-800 text-lg uppercase tracking-tight">Transaksi Terbaru</h3>
                <p class="text-xs text-gray-400 font-bold uppercase mt-1">Menampilkan 10 aktifitas terakhir</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider">
                Kelola Semua <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Buku & Order ID</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4 text-center">Payment</th>
                        <th class="px-6 py-4 text-center">Logistik</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=6366f1&color=fff" class="w-8 h-8 rounded-lg mr-3 shadow-sm">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium">{{ $order->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-mono text-indigo-500 font-bold mb-1">#{{ $order->order_number }}</div>
                                <div class="text-sm font-bold text-gray-800 line-clamp-1">
                                    {{ $order->items->first()?->item->name ?? 'Produk' }} 
                                    @if($order->items->count() > 1) dkk @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-gray-900">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                    {{ $order->items->sum('quantity') }} Item
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($order->payment_status == 'success')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        PAID
                                    </span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100">
                                        PENDING
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-100">
                                        FAILED
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $badgeClasses = $statusStyles[$order->item_status] ?? 'bg-indigo-50 text-indigo-600 border-indigo-100';
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $badgeClasses }}">
                                    {{ $statusLabels[$order->item_status] ?? strtoupper(str_replace('_', ' ', $order->item_status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        // make sure the arrays contain numbers, not strings
        const labels = {!! json_encode($salesData->pluck('date')) !!};
        const rawData = {!! json_encode($salesData->pluck('total')) !!};
        const dataPoints = rawData.map(n => parseFloat(n) || 0);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dataPoints,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            autoSkip: labels.length > 7 ? true : false
                        }
                    }
                }
            }
        });
    </script>
@endsection

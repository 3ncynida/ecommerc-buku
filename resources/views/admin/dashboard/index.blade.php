@extends('admin.admin-layout')
@section('title', 'Manajemen Pesanan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pesanan</p>
                <h4 class="text-2xl font-bold text-gray-800">{{ $orders->count() }}</h4>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Menunggu Bayar</p>
                <h4 class="text-2xl font-bold text-amber-600">{{ $orders->where('payment_status', 'pending')->count() }}</h4>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pembayaran Sukses</p>
                <h4 class="text-2xl font-bold text-emerald-600">{{ $orders->where('payment_status', 'success')->count() }}</h4>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Perlu Dikirim</p>
                <h4 class="text-2xl font-bold text-blue-600">{{ $orders->where('item_status', 'diproses')->count() }}</h4>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Transaksi</h3>
        <p class="text-sm text-gray-500">Pantau dan kelola status pembayaran serta logistik pelanggan.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">No. Order</th>
                    <th class="px-6 py-4">Informasi Item</th>
                    <th class="px-6 py-4">Total Bayar</th>
                    <th class="px-6 py-4 text-center">Status Pembayaran</th>
                    <th class="px-6 py-4 text-center">Status Logistik</th>
                    <th class="px-6 py-4 text-center">Update Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-mono text-sm text-indigo-600 font-bold">
                        #{{ $order->order_number }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900">{{ $order->item->name }}</div>
                        <div class="text-xs text-gray-400">ID Pesanan: {{ $order->id }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($order->payment_status == 'success')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                <i class="fa-solid fa-check-double mr-1 text-[10px]"></i> LUNAS
                            </span>
                        @elseif($order->payment_status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                <i class="fa-solid fa-spinner fa-spin mr-1 text-[10px]"></i> MENUNGGU
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                <i class="fa-solid fa-xmark mr-1 text-[10px]"></i> GAGAL
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider
                            @if($order->item_status == 'selesai') bg-gray-100 text-gray-600 
                            @else bg-blue-50 text-blue-600 @endif border border-current opacity-80">
                            {{ $order->item_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ url('/admin/orders/'.$order->id.'/update') }}" method="POST" class="inline-block">
                            @csrf
                            <select name="item_status" onchange="this.form.submit()" 
                                class="text-xs border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-1.5 bg-gray-50 cursor-pointer">
                                <option value="pending" {{ $order->item_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="diproses" {{ $order->item_status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $order->item_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $order->item_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
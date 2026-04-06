@extends('admin.admin-layout')
@section('title', 'Detail Kurir')

@section('content')
@php
    $statusColors = [
        'sedang_dikemas' => 'bg-purple-50 text-purple-700 border-purple-200',
        'menunggu_kurir' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'diproses_kurir' => 'bg-amber-50 text-amber-700 border-amber-200',
        'dikirim' => 'bg-blue-50 text-blue-700 border-blue-200',
        'sampai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'selesai' => 'bg-slate-100 text-slate-600 border-slate-200',
        'gagal' => 'bg-rose-50 text-rose-700 border-rose-200',
    ];
@endphp

<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    
    <div class="mb-6">
        <a href="{{ route('couriers.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="space-y-8 lg:max-w-7xl mx-auto">
        
        {{-- Profile Banner --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/70 overflow-hidden relative group">
            <div class="h-32 md:h-40 bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/20 rounded-full blur-3xl mix-blend-overlay"></div>
            </div>
            
            <div class="px-8 sm:px-10 pb-8 flex flex-col md:flex-row gap-6 justify-between items-start md:items-end -mt-12 md:-mt-16 relative z-10">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-5 text-center sm:text-left w-full md:w-auto">
                    <div class="relative inline-block hover:scale-105 transition-transform duration-300">
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-[2rem] p-1.5 bg-white shadow-xl shadow-indigo-100/50">
                            <div class="w-full h-full bg-slate-100 rounded-[1.75rem] overflow-hidden flex items-center justify-center text-4xl text-indigo-400 font-black">
                                {{ strtoupper(substr($courier->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                    <div class="pb-2">
                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black tracking-widest uppercase mb-2 inline-block border border-indigo-100">
                            Profil Kurir
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">{{ $courier->name }}</h2>
                        <p class="text-slate-500 mt-1 font-medium flex items-center justify-center sm:justify-start gap-2">
                            <i class="fa-regular fa-envelope"></i> {{ $courier->email }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 w-full md:w-auto justify-center md:justify-end mt-4 md:mt-0">
                    <a href="{{ route('couriers.edit', $courier->id) }}"
                        class="px-6 py-3 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl text-sm font-bold hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm flex items-center gap-2 group/btn relative overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i> Edit Data</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistics Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-3xl border border-slate-200/70 p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 text-slate-900 group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300">
                    <i class="fa-solid fa-boxes-stacked text-8xl"></i>
                </div>
                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest relative z-10">Total Ditugaskan</p>
                <p class="text-3xl font-black text-slate-800 mt-2 relative z-10">{{ $totalAssigned }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-3xl border border-emerald-200 p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-emerald-600 group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300">
                    <i class="fa-solid fa-check-circle text-8xl"></i>
                </div>
                <p class="text-[10px] text-emerald-700 font-black uppercase tracking-widest relative z-10">Selesai</p>
                <p class="text-3xl font-black text-emerald-600 mt-2 relative z-10">{{ $completed }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-3xl border border-amber-200 p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-amber-600 group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300">
                    <i class="fa-solid fa-truck-fast text-8xl"></i>
                </div>
                <p class="text-[10px] text-amber-700 font-black uppercase tracking-widest relative z-10">Dlm Proses</p>
                <p class="text-3xl font-black text-amber-600 mt-2 relative z-10">{{ $inProgress }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-rose-50 to-rose-100/50 rounded-3xl border border-rose-200 p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-rose-600 group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300">
                    <i class="fa-solid fa-triangle-exclamation text-8xl"></i>
                </div>
                <p class="text-[10px] text-rose-700 font-black uppercase tracking-widest relative z-10">Gagal</p>
                <p class="text-3xl font-black text-rose-600 mt-2 relative z-10">{{ $failed }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-3xl border border-indigo-400 p-5 shadow-md relative overflow-hidden group text-white md:col-span-1 col-span-2">
                <div class="absolute -right-4 -bottom-4 opacity-20 text-white group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300">
                    <i class="fa-solid fa-chart-pie text-8xl"></i>
                </div>
                <p class="text-[10px] text-indigo-100 font-black uppercase tracking-widest relative z-10">Success Rate</p>
                <p class="text-3xl font-black mt-2 relative z-10">{{ $successRate }}%</p>
            </div>
        </div>

        {{-- History Table --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/70 overflow-hidden relative transition-all">
            <div class="p-6 md:px-8 md:py-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Riwayat Pengiriman</h3>
                        <p class="text-xs text-slate-500">Tugas pesanan yang ditangani kurir ini</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold font-mono">
                    Total: {{ $orders->total() }} Log
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">ID PESANAN</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">PELANGGAN & ITEM</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">TOTAL HASIL</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">STATUS</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">UPDATE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-4">
                                    <span class="font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="font-bold text-slate-800 text-sm">{{ $order->user->name ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5 truncate max-w-[200px] leading-tight">
                                        @if($order->items->count() > 1)
                                            {{ $order->items->first()->item->name ?? '-' }} dan {{ $order->items->count() - 1 }} item lainnya
                                        @else
                                            {{ $order->items->first()->item->name ?? '-' }}
                                        @endif
                                    </p>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="text-sm font-black text-slate-800">
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $order->items->sum('quantity') }} Produk</p>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-black tracking-wider uppercase border {{ $statusColors[$order->item_status] ?? 'bg-slate-50 text-slate-600 border-slate-200' }} flex items-center justify-center w-max gap-1.5 shadow-sm">
                                        @if(in_array($order->item_status, ['sampai', 'selesai'])) <i class="fa-solid fa-check"></i>
                                        @elseif(in_array($order->item_status, ['gagal'])) <i class="fa-solid fa-xmark"></i>
                                        @elseif(in_array($order->item_status, ['diproses_kurir', 'dikirim'])) <i class="fa-solid fa-truck-fast"></i>
                                        @else <i class="fa-solid fa-clock"></i>
                                        @endif
                                        {{ str_replace('_', ' ', $order->item_status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar text-slate-400"></i>
                                        {{ $order->updated_at->format('d M y') }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5 ml-4">
                                        {{ $order->updated_at->format('H:i') }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 text-slate-300">
                                            <i class="fa-solid fa-clipboard-list text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium text-sm">Belum ada riwayat pengiriman untuk kurir ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="p-6 md:px-8 border-t border-slate-100 bg-slate-50/30">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

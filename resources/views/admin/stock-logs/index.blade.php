@extends('admin.admin-layout')

@section('title', 'Riwayat Stok')

@section('content')
    <div class="bg-white rounded-[30px] shadow-sm border border-slate-200/60 overflow-hidden mb-8">
        
        {{-- Header --}}
        <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Riwayat Perubahan Stok</h3>
                <p class="text-[12px] font-medium text-slate-500 mt-1">Log aktivitas penambahan dan pengurangan inventaris</p>
            </div>
            
            {{-- Tombol Kembali ke Katalog (Opsional) --}}
            <a href="{{ route('items.index') }}" class="text-[13px] font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Katalog
            </a>
        </div>

        {{-- Tabel Log --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Waktu</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Buku / Produk</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Admin</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Perubahan</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Stok Akhir</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($stockLogs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            {{-- Tanggal & Jam --}}
                            <td class="px-8 py-4">
                                <p class="text-[13px] font-bold text-slate-700">{{ $log->created_at->format('d M Y') }}</p>
                                <p class="text-[11px] font-medium text-slate-400">{{ $log->created_at->format('H:i') }} WIB</p>
                            </td>

                            {{-- Info Produk --}}
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-900 text-[14px]">{{ $log->item->name }}</span>
                                </div>
                            </td>

                            {{-- Nama Admin --}}
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-[13px] font-bold text-slate-600">{{ $log->user->name }}</span>
                                </div>
                            </td>

                            {{-- Log Perubahan (Dinamis Warna) --}}
                            <td class="px-8 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">
                                        Dari {{ $log->previous_stock }} Unit
                                    </span>
                                    @if($log->quantity_added > 0)
                                        <span class="text-[14px] font-black text-emerald-500">
                                            <i class="fa-solid fa-caret-up mr-0.5"></i> +{{ $log->quantity_added }}
                                        </span>
                                    @else
                                        <span class="text-[14px] font-black text-rose-500">
                                            <i class="fa-solid fa-caret-down mr-0.5"></i> {{ $log->quantity_added }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Stok Sesudah --}}
                            <td class="px-8 py-4">
                                <span class="px-3 py-1.5 bg-slate-900 text-white rounded-lg text-[12px] font-black">
                                    {{ $log->new_stock }}
                                </span>
                            </td>

                            {{-- Catatan --}}
                            <td class="px-8 py-4">
                                <p class="text-[12px] font-medium text-slate-500 italic max-w-xs truncate">
                                    {{ $log->notes ?: 'Tidak ada catatan' }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                    <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300"></i>
                                </div>
                                <h4 class="text-slate-900 font-bold mb-1">Riwayat Kosong</h4>
                                <p class="text-sm text-slate-500 text-center mx-auto max-w-[250px]">Belum ada aktivitas perubahan stok yang tercatat di sistem.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="p-8 border-t border-slate-100 bg-slate-50/30">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[12px] font-medium text-slate-500">
                    Menampilkan <span class="font-bold text-slate-900">{{ $stockLogs->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-900">{{ $stockLogs->lastItem() ?? 0 }}</span> riwayat
                </p>
                <div class="pagination-wrapper">
                    {{ $stockLogs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
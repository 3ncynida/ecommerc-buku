@extends('admin.admin-layout')
@section('title', 'Detail Laporan Penjualan')

@section('content')

{{-- Tombol Aksi (Hanya tampil di layar) --}}
<div class="max-w-5xl mx-auto mb-6 flex justify-between items-center print-hidden-area">
    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-slate-500 hover:text-indigo-600 transition-colors bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Form
    </a>
    <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-[13px] font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center gap-2">
        <i class="fa-solid fa-print"></i> Cetak Laporan (PDF / Print)
    </button>
</div>

{{-- Kertas Laporan --}}
<div id="print-area" class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 printable-surface">
    <div class="p-8 md:p-12">
        
        {{-- Report Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-[3px] border-slate-900 pb-6 mb-8 gap-6 print-header-box">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase print-title">Laporan Penjualan</h1>
                <p class="text-slate-500 text-[14px] font-medium mt-1 tracking-wide uppercase">Libris E-Commerce Management</p>
            </div>
            
            <div class="bg-slate-50 px-6 py-4 rounded-xl border border-slate-100 text-right min-w-[250px] print-meta-box">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Periode Laporan</p>
                <p class="text-slate-900 font-bold text-[15px]">
                    {{ $startDate->format('d/m/Y') }} <span class="text-slate-400 font-normal mx-1">s/d</span> {{ $endDate->format('d/m/Y') }}
                </p>
            </div>
        </div>

        {{-- Highlight Metrics --}}
        <div class="flex gap-8 mb-10 print-metrics-row">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pesanan</p>
                <p class="text-3xl font-black text-indigo-600 print-indigo">{{ number_format($totalOrders) }} <span class="text-sm font-bold text-slate-500">Trx</span></p>
            </div>
            <div class="w-px bg-slate-200"></div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pendapatan</p>
                <p class="text-3xl font-black text-emerald-600 print-emerald"><span class="text-lg text-emerald-400">Rp</span> {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel Data Laporan --}}
        <div class="overflow-x-auto border border-slate-200 rounded-xl print-table-container">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-900 text-white print-table-header">
                    <tr>
                        <th class="px-5 py-4 text-[11px] font-black uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-5 py-4 text-[11px] font-black uppercase tracking-wider">Pelanggan</th>
                        <th class="px-5 py-4 text-[11px] font-black uppercase tracking-wider">Buku Terjual</th>
                        <th class="px-5 py-4 text-[11px] font-black uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-4 text-[11px] font-black uppercase tracking-wider text-right">Nilai Rupiah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 print-table-body">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 transition-colors py-1">
                            <td class="px-5 py-3">
                                <span class="font-bold text-slate-900 text-[13px]">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="font-bold text-slate-700 text-[13px]">{{ $order->user->name }}</span>
                            </td>
                            <td class="px-5 py-3 max-w-[200px] truncate">
                                <span class="text-[13px] font-medium text-slate-600">{{ $order->item->name }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-[13px] font-medium text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="font-black text-slate-900 text-[14px]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center text-slate-500">
                                <i class="fa-solid fa-folder-open text-3xl text-slate-300 mb-3"></i>
                                <p class="text-sm font-medium">Tidak ada transaksi pesanan yang terekam pada periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
    @media print {
        @page { size: auto; margin: 0; } /* margin: 0 otomatis mematikan header (URL) dan footer (Page Number) bawaan Chrome/Edge */
        body { background-color: white !important; font-size: 11pt !important; color: #000 !important; }
        
        /* Hilangkan UI Admin */
        aside, header, .print-hidden-area { display: none !important; }
        main, .flex-1, .bg-slate-50 { background: white !important; padding: 0 !important; margin: 0 !important; overflow: visible !important; }
        
        /* Rapikan Area Cetak Utama dan cegah teks menempel ke tepi kertas */
        .printable-surface { border: none !important; box-shadow: none !important; margin: 0 !important; padding: 15mm !important; }
        
        /* Header Tabel supaya tetap kontras tinggi ketika di cetak PDF hitam putih */
        .print-table-header { background-color: #0f172a !important; -webkit-print-color-adjust: exact; color: white !important; }
        
        .print-table-container { border-radius: 0 !important; border: 1px solid #cbd5e1 !important; }
        .print-table-body td { border-bottom: 1px solid #f1f5f9 !important; }
        
        .print-meta-box { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection

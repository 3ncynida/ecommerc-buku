@extends('admin.admin-layout')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Cetak Laporan Penjualan</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Tentukan periode waktu untuk menghasilkan laporan data transaksi penjualan.</p>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-[24px] shadow-sm border border-slate-200/60 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        
        <form action="{{ route('admin.reports.generate') }}" method="POST">
            @csrf
            
            <div class="p-6 md:p-8 space-y-6">
                <!-- Wrapper Flex for Desktop -->
                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Input Start --}}
                    <div class="space-y-2 flex-1">
                        <label for="start_date" class="block text-[13px] font-bold text-slate-700">Tanggal Mulai <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-calendar-minus"></i>
                            </div>
                            <input type="date" name="start_date" id="start_date" 
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-700 text-[14px]"
                                required>
                        </div>
                    </div>

                    {{-- Input End --}}
                    <div class="space-y-2 flex-1">
                        <label for="end_date" class="block text-[13px] font-bold text-slate-700">Tanggal Selesai <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-calendar-check"></i>
                            </div>
                            <input type="date" name="end_date" id="end_date" 
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-700 text-[14px]"
                                required>
                        </div>
                    </div>
                </div>

                {{-- Alert/Info Text --}}
                <div class="flex gap-3 bg-indigo-50 text-indigo-700 p-4 rounded-xl border border-indigo-100">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p class="text-[13px] font-medium leading-relaxed">
                        Laporan yang dicetak akan mencakup seluruh status pesanan dalam rentang tersebut. Anda bisa menyimpan hasil cetaknya ke format PDF atau mencetaknya langsung menggunakan Printer.
                    </p>
                </div>
            </div>

            {{-- Footer / Actions --}}
            <div class="p-6 md:p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-[14px] font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice"></i> Buat Laporan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
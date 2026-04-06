@extends('admin.admin-layout')
@section('title', 'Manajemen Kurir')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[11px] font-black tracking-widest uppercase mb-3 border border-blue-100">
                <i class="fa-solid fa-truck-fast"></i> Armada
            </span>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Manajemen Kurir</h1>
            <p class="text-slate-500 mt-2 text-sm md:text-base max-w-xl">Kelola data kurir pengiriman, pantau performa, dan riwayat tugas mereka.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <form action="{{ route('couriers.index') }}" method="GET" class="relative w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kurir..." class="w-full sm:w-64 pl-10 pr-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white shadow-sm transition">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </form>
            <a href="{{ route('couriers.create') }}" class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-300 bg-indigo-600 rounded-2xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 w-full sm:w-auto">
                <i class="fa-solid fa-plus mr-2 transition-transform group-hover:rotate-90"></i>
                Tambah Kurir Baru
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <p class="text-emerald-700 font-semibold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 rounded-full bg-rose-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
            </div>
            <p class="text-rose-700 font-semibold text-sm">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/70 overflow-hidden relative transition-all hover:shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest w-20">NO</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">INFORMASI KURIR</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($couriers as $courier)
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                            <td class="px-8 py-4 text-sm font-bold text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold shrink-0">
                                        {{ substr($courier->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $courier->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $courier->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('couriers.show', $courier->id) }}"
                                        class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 hover:scale-105 transition-all shadow-sm" title="Lihat Detail">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('couriers.edit', $courier->id) }}"
                                        class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-amber-500 hover:bg-amber-50 hover:border-amber-200 hover:scale-105 transition-all shadow-sm" title="Edit">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <form action="{{ route('couriers.destroy', $courier->id) }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-rose-500 hover:bg-rose-50 hover:border-rose-200 hover:scale-105 transition-all shadow-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kurir ini? Semua log yang terikat mungkin terpengaruh.')" title="Hapus">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                        <i class="fa-solid fa-box-open text-3xl"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada kurir yang terdaftar di sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($couriers->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $couriers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

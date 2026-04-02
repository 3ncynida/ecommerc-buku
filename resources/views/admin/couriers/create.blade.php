@extends('admin.admin-layout')
@section('title', 'Tambah Kurir')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    
    <div class="mb-8">
        <a href="{{ route('couriers.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/70 overflow-hidden relative transition-all hover:shadow-md">
            
            <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                        <i class="fa-solid fa-user-plus text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Kurir Baru</h3>
                        <p class="text-sm text-slate-500 mt-1">Daftarkan armada kurir baru untuk menangani pengiriman pesanan.</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('couriers.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-2 group/input">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-id-badge"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('name') border-rose-300 ring-rose-100 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-500 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="Masukkan nama kurir">
                        </div>
                        @error('name')
                            <p class="text-xs font-bold text-rose-500 mt-1 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 group/input">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('email') border-rose-300 ring-rose-100 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-500 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="contoh: kurir@libris.com">
                        </div>
                        @error('email')
                            <p class="text-xs font-bold text-rose-500 mt-1 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 group/input">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Password Akses</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('password') border-rose-300 ring-rose-100 @else border-slate-200 @enderror rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-500 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="Minimal 8 karakter">
                        </div>
                        @error('password')
                            <p class="text-xs font-bold text-rose-500 mt-1 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('couriers.index') }}" class="px-6 py-3.5 text-sm font-bold text-slate-500 bg-slate-50 border border-slate-200 rounded-2xl hover:bg-slate-100 transition-all">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3.5 text-sm font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98] transition-all flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

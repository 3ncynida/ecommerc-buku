@extends('admin.admin-layout')
@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-3xl mx-auto">
    
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-slate-500 hover:text-indigo-600 transition-colors bg-white px-4 py-2 rounded-xl shadow-[0_2px_8px_-3px_rgba(0,0,0,0.05)] border border-slate-100">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kategori
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-[24px] shadow-sm border border-slate-200/60 overflow-hidden">
        
        {{-- Header --}}
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0 shadow-inner">
                    <i class="fa-solid fa-pen-to-square text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Edit Kategori</h3>
                    <p class="text-[13px] font-medium text-slate-500 mt-1">Perbarui informasi kategori &quot;{{ $category->name }}&quot;.</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 md:p-8 space-y-6">
                {{-- Input Name --}}
                <div class="space-y-2">
                    <label for="name" class="block text-[13px] font-bold text-slate-700">Nama Kategori <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <input type="text" name="name" id="name" 
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-700 text-[14px] @error('name') border-rose-300 bg-rose-50 focus:ring-rose-500/20 focus:border-rose-500 @enderror"
                            placeholder="Contoh: Fiksi Sains, Komik, Novel..." 
                            value="{{ old('name', $category->name) }}" required autocomplete="off">
                    </div>
                    @error('name')
                        <p class="text-[12px] font-bold text-rose-500 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Footer / Actions --}}
            <div class="p-6 md:p-8 border-t border-slate-100 bg-slate-50/30 flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-200 bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-[13px] font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
@extends('admin.admin-layout')
@section('title', 'Detail Buku')

@section('content')

{{-- Tombol Kembali & Aksi --}}
<div class="max-w-5xl mx-auto mb-6 flex justify-between items-center">
    <a href="{{ route('items.index') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-slate-500 hover:text-indigo-600 transition-colors bg-white px-4 py-2 rounded-xl shadow-[0_2px_8px_-3px_rgba(0,0,0,0.05)] border border-slate-100">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
    </a>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('items.edit', $item->id) }}" class="bg-orange-50 text-orange-600 hover:bg-orange-100 hover:text-orange-700 px-5 py-2.5 rounded-xl text-[13px] font-bold transition-all border border-orange-200 flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-pen-to-square"></i> Edit Data Buku
        </a>
    </div>
</div>

<div class="max-w-5xl mx-auto mb-16">
    <div class="bg-white rounded-[32px] shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="flex flex-col md:flex-row">
            
            {{-- Kolom Kiri: Cover Buku --}}
            <div class="w-full md:w-5/12 p-8 md:p-12 bg-slate-50 border-r border-slate-100 flex flex-col items-center justify-start relative">
                <div class="absolute top-6 left-6">
                    <span class="px-3 py-1 bg-white text-slate-400 border border-slate-200 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm">
                        ID: #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="relative group mt-8 w-full max-w-[280px]">
                    {{-- Bayangan glow di belakang gambar --}}
                    <div class="absolute -inset-4 bg-indigo-500/20 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition duration-700 hidden md:block"></div>
                    <img src="{{ asset('storage/' . $item->image) }}" 
                         alt="{{ $item->name }}" 
                         class="relative w-full rounded-2xl shadow-[0_20px_40px_-15px_rgba(0,0,0,0.3)] transform group-hover:-translate-y-2 transition-all duration-500 object-cover aspect-[3/4] border border-slate-200/50 bg-white">
                </div>
            </div>

            {{-- Kolom Kanan: Detail Buku --}}
            <div class="w-full md:w-7/12 p-8 md:p-12">
                
                {{-- Kategori & Judul --}}
                <div class="mb-8">
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($item->categories && $item->categories->count() > 0)
                            @foreach ($item->categories as $category)
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-indigo-100">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-200">
                                Kategori Umum
                            </span>
                        @endif
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight tracking-tight">{{ $item->name }}</h1>
                    <div class="flex items-center gap-2 mt-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center border border-slate-200 overflow-hidden">
                            <i class="fa-solid fa-feather-pointed text-[12px]"></i>
                        </div>
                        <span class="text-[14px] font-bold text-slate-600">{{ $item->author->name ?? 'Penulis Tidak Diketahui' }}</span>
                    </div>
                </div>

                {{-- Harga & Stok --}}
                <div class="flex items-center justify-between gap-6 mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Harga Jual</p>
                        <span class="text-3xl font-black text-indigo-600"><span class="text-xl text-indigo-400">Rp</span> {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-px h-12 bg-slate-200 hidden sm:block"></div>
                    <div class="text-right sm:text-left">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Ketersediaan Stok</p>
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm inline-flex">
                            <span class="w-2.5 h-2.5 rounded-full relative flex h-3 w-3">
                              @if($item->stok > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                              @else
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                              @endif
                            </span>
                            <span class="text-[14px] font-black {{ $item->stok > 0 ? 'text-slate-700' : 'text-rose-600' }}">{{ $item->stok }} <span class="text-[12px] font-medium text-slate-400">Unit</span></span>
                        </div>
                    </div>
                </div>

                {{-- Grid Info --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                    <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm flex flex-col justify-center">
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1.5"><i class="fa-solid fa-building mr-1 opacity-50"></i> Penerbit</p>
                        <p class="text-[12px] font-bold text-slate-800 leading-tight">{{ $item->publisher ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm flex flex-col justify-center">
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1.5"><i class="fa-regular fa-calendar mr-1 opacity-50"></i> Tahun</p>
                        <p class="text-[13px] font-bold text-slate-800 leading-tight">{{ $item->publication_year ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm flex flex-col justify-center">
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1.5"><i class="fa-solid fa-book-open text-[10px] mr-1 opacity-50"></i> Halaman</p>
                        <p class="text-[13px] font-bold text-slate-800 leading-tight">{{ $item->pages ?? '-' }} Hlm</p>
                    </div>
                    <div class="p-4 rounded-xl border border-slate-100 bg-white shadow-sm flex flex-col justify-center">
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1.5"><i class="fa-solid fa-barcode mr-1 opacity-50"></i> ISBN</p>
                        <p class="text-[13px] font-bold text-slate-800 leading-tight break-all">{{ $item->isbn ?? '-' }}</p>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <h3 class="flex items-center text-[12px] font-bold text-slate-900 uppercase tracking-widest mb-4 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-align-left text-indigo-500 mr-2"></i> Sinopsis Singkat
                    </h3>
                    <div class="text-slate-600 text-[13px] font-medium leading-relaxed max-w-none text-justify">
                        {{ $item->description ?? 'Tidak ada deskripsi rinci untuk buku ini.' }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
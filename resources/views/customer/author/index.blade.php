@extends('customer.layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        
        {{-- 1. Profil Penulis (image_87b6d7.jpg) --}}
        <div class="bg-white border border-gray-100 rounded-[30px] p-8 shadow-sm mb-12">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="w-24 h-24 rounded-full overflow-hidden shrink-0 border-2 border-gray-50">
                    <img src="{{ asset('storage/' . $author->photo) }}" class="w-full h-full object-cover">
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-xl font-bold text-gray-900">{{ $author->name }}</h1>
                        <span class="text-gray-400 text-sm flex items-center gap-1">
                            <i class="fa-solid fa-book-open text-xs"></i> {{ $author->items_count ?? $author->items->count() }} Buku
                        </span>
                    </div>
                    
                    <div x-data="{ expanded: false }" class="relative">
                        <p :class="expanded ? '' : 'line-clamp-2'" class="text-gray-500 text-sm leading-relaxed">
                            {{ $author->bio ?? 'Belum ada biografi untuk penulis ini.' }}
                        </p>
                        <button @click="expanded = !expanded" class="text-gray-900 text-xs font-bold mt-2 hover:underline focus:outline-none">
                            <span x-text="expanded ? 'Sembunyikan' : 'Baca Selengkapnya'"></span>
                            <i class="fa-solid fa-chevron-down ml-1 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                        </button>
                    </div>
                </div>

                <button class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-indigo-600">
                    <i class="fa-solid fa-share-nodes text-lg"></i>
                </button>
            </div>
        </div>

        {{-- 2. Toolbar & Katalog (image_88179e.jpg) --}}
        <div class="w-full">
            {{-- Toolbar Atas: Sortir & Status --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                {{-- Tombol Filter Cepat (Kiri) --}}
                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200 transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-gray-100 rounded-full border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-200 transition">
                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                        Stok Tersedia
                    </button>
                </div>

                {{-- Dropdown Urutkan (Kanan) --}}
                <div class="relative w-full md:w-56">
                    <label class="absolute -top-2 left-4 bg-white px-1 text-[10px] text-gray-400 font-bold uppercase tracking-tighter z-10">Urutkan</label>
                    <select class="w-full pl-5 pr-10 py-3 border border-gray-200 rounded-2xl appearance-none text-sm font-semibold text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-300 transition">
                        <option>Terbaru</option>
                        <option>Harga Terendah</option>
                        <option>Harga Tertinggi</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            {{-- Grid Produk Full Width --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @forelse($books as $book)
                <div class="group relative bg-white border border-transparent rounded-3xl p-2 transition hover:border-gray-100 hover:shadow-lg hover:shadow-gray-50">
                    
                    {{-- Badge Bahasa (Top-Left) --}}
                    <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-sm text-blue-600 text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center gap-1 border border-blue-100">
                        <i class="fa-solid fa-globe"></i> ID
                    </div>

                    {{-- Tombol Favorit (Top-Right) --}}
                    <button onclick="toggleFavorite(this, {{ $book->id }})" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-red-500 transition">
                        <i class="{{ $book->isFavorited() ? 'fa-solid text-red-500' : 'fa-solid' }} fa-heart text-lg"></i>
                    </button>

                    <a href="{{ route('book.show', $book->slug) }}">
                        {{-- Cover Image --}}
                        <div class="aspect-[3/4] rounded-2xl overflow-hidden mb-4 bg-gray-50 border border-gray-50">
                            <img src="{{ asset('storage/' . $book->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        
                        {{-- Book Info --}} 
                        <div class="px-2 pb-2">
                            <p class="text-[10px] text-gray-400 font-bold mb-1 uppercase tracking-tight">{{ $author->name }}</p>
                            <h3 class="text-xs font-bold text-gray-800 leading-tight mb-3 h-8 line-clamp-2">{{ $book->name }}</h3>
                            
                            <p class="text-sm font-black text-gray-900">Rp{{ number_format($book->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-span-full py-20 text-center text-gray-400 font-medium">
                    Belum ada koleksi buku untuk penulis ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
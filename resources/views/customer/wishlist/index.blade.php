@extends('customer.layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        
        {{-- Judul Halaman --}}
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Wishlist</h1>

        {{-- Baris Info & Filter --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div class="text-gray-900 font-medium">
                {{ $wishlists->count() }} Barang
            </div>

            {{-- Custom Select Sortir (image_ee1486.png) --}}
            <div class="relative w-full md:w-64">
                <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">Urutkan</label>
                <select class="w-full pl-5 pr-10 py-3 border border-gray-200 rounded-2xl appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm font-semibold text-gray-700">
                    <option>Terbaru Disimpan</option>
                    <option>Harga Terendah</option>
                    <option>Harga Tertinggi</option>
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                </div>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($wishlists as $wishlist)
                <div class="group relative bg-white border border-gray-100 rounded-[24px] p-3 transition hover:shadow-xl hover:shadow-gray-100">
                    
                    {{-- Ikon Favorit Aktif (image_ee1486.png) --}}
                    <button onclick="toggleFavorite(this, {{ $wishlist->item->id }})" class="absolute top-4 right-4 z-10 bg-white/80 backdrop-blur-sm p-1.5 rounded-full text-red-500 shadow-sm">
                        <i class="fa-solid fa-heart text-sm"></i>
                    </button>
                    
                    {{-- Gambar Buku --}}
                    <a href="{{ route('book.show', $wishlist->item->slug) }}">
                        <div class="aspect-[3/4] rounded-xl overflow-hidden mb-4">
                            <img src="{{ asset('storage/' . $wishlist->item->image) }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                                 alt="{{ $wishlist->item->name }}">
                        </div>
                    </a>

                    {{-- Informasi Buku --}}
                    <div class="px-1">
                        <p class="text-[11px] text-gray-400 font-medium mb-1 truncate">{{ $wishlist->item->author->name ?? 'Penulis' }}</p>
                        <h3 class="text-sm font-bold text-gray-800 leading-tight mb-2 h-10 line-clamp-2">
                            {{ $wishlist->item->name }}
                        </h3>

                        <div class="mt-auto">
                            <p class="text-sm font-black text-gray-900">
                                Rp{{ number_format($wishlist->item->price, 0, ',', '.') }}
                            </p>
                            
                            {{-- Diskon (image_ee1486.png) --}}
                            @if($wishlist->item->discount_price)
                            <div class="flex items-center gap-2 mt-1">
                                <span class="bg-red-50 text-red-500 text-[10px] font-bold px-1.5 py-0.5 rounded">25%</span>
                                <span class="text-[11px] text-gray-300 line-through">Rp{{ number_format($wishlist->item->original_price, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <i class="fa-solid fa-heart-crack text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-400 font-medium">Belum ada buku di wishlist kamu.</p>
                    <a href="/" class="text-indigo-600 font-bold mt-2 inline-block hover:underline">Cari Buku Favorit</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
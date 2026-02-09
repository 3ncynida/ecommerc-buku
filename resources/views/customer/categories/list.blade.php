@extends('customer.layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-8">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Jelajahi Kategori</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Temukan ribuan koleksi buku terbaik yang dikelompokkan berdasarkan minat dan kebutuhan membaca Anda.</p>
        </div>

        {{-- Category Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('category.show', $category->id) }}" class="group">
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 flex flex-col items-center text-center h-full">
                    
{{-- Icon Placeholder (Kotak ungu muda seperti di gambar) --}}
                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors duration-300">
                        <i class="{{ $category->icon ?? 'fa-solid fa-book-open' }} text-2xl text-indigo-400 group-hover:text-white transition-colors"></i>
                    </div>

                    {{-- Category Info --}}
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                        {{ $category->name }}
                    </h3>
                    
                    {{-- Menampilkan jumlah buku dalam kategori --}}
                    <p class="text-sm text-gray-400 mt-2">
                        {{ $category->items_count ?? '0' }} Koleksi Buku
                    </p>

                    <div class="mt-6 text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity flex items-center text-sm font-bold">
                        Lihat Buku <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Empty State (Jika kategori kosong) --}}
        @if($categories->isEmpty())
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed">
            <i class="fa-solid fa-layer-group text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-500">Belum ada kategori yang tersedia saat ini.</p>
        </div>
        @endif

    </div>
</div>
@endsection
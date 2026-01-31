@extends('customer.layouts.app') {{-- Pastikan Anda punya layout utama --}}

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-8">
        
        <nav class="flex mb-8 text-sm text-gray-500">
            <a href="/" class="hover:text-indigo-600">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">{{ $item->name }}</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex flex-col md:row flex-wrap md:flex-nowrap">
                
                <div class="md:w-1/3 p-8 bg-gray-50 flex justify-center">
                    <img src="{{ asset('storage/' . $item->image) }}" 
                         alt="{{ $item->name }}" 
                         class="w-full max-w-sm rounded-xl shadow-2xl transform hover:scale-105 transition duration-500">
                </div>

                <div class="md:w-2/3 p-8 md:p-12">
                    <div class="mb-6">
                        <span class="text-indigo-600 font-semibold uppercase tracking-wider text-sm">
                            {{ $item->category->name ?? 'Kategori Umum' }}
                        </span>
                        <h1 class="text-4xl font-extrabold text-gray-900 mt-2">{{ $item->name }}</h1>
                        <p class="text-xl text-gray-500 mt-1">oleh <span class="font-medium text-gray-800">{{ $item->author->name ?? 'Penulis' }}</span></p>
                    </div>

                    <div class="flex items-center space-x-4 mb-8">
                        <span class="text-3xl font-bold text-indigo-600">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">Stok Tersedia</span>
                    </div>

                    <div class="border-t border-b border-gray-100 py-6 mb-8">
                        <h3 class="font-bold text-gray-800 mb-3">Sinopsis / Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $item->description ?? 'Tidak ada deskripsi untuk buku ini.' }}
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button class="px-4 py-2 hover:bg-gray-100">-</button>
                            <input type="number" value="1" class="w-12 text-center border-none focus:ring-0">
                            <button class="px-4 py-2 hover:bg-gray-100">+</button>
                        </div>
                        <button class="flex-1 bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition">
                            <i class="fa-solid fa-cart-shopping mr-2"></i> Tambah ke Keranjang
                        </button>
                        <button class="p-4 border border-gray-300 rounded-xl hover:bg-red-50 hover:text-red-500 transition">
                            <i class="fa-regular fa-heart text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20">
            <h2 class="text-2xl font-bold mb-8 text-gray-800">Mungkin Anda Juga Suka</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedBooks as $related)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <img src="{{ asset('storage/' . $related->image) }}" class="rounded-lg mb-4 h-48 w-full object-cover">
                    <h4 class="font-bold text-gray-900 truncate">{{ $related->name }}</h4>
                    <p class="text-indigo-600 font-bold mt-2">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                    <a href="{{ route('book.show', $related->slug) }}" class="block text-center mt-4 text-sm font-semibold text-gray-500 hover:text-indigo-600">Detail →</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
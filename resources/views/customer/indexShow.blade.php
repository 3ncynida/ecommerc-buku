@extends('customer.layouts.app')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-6">

            {{-- Breadcrumbs --}}
            <nav class="flex mb-8 text-sm text-gray-500 overflow-x-auto whitespace-nowrap">
                <a href="/" class="hover:text-indigo-600">Home</a>
                <span class="mx-2 text-gray-300">></span>
                <a href="#" class="hover:text-indigo-600">Buku</a>
                <span class="mx-2 text-gray-300">></span>
                <span class="text-gray-400">{{ $item->category->name ?? 'Kategori' }}</span>
                <span class="mx-2 text-gray-300">></span>
                <span class="text-gray-800 font-medium truncate">{{ $item->name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-12">

                {{-- Bagian Kiri: Gambar (Sticky) --}}
                <div class="md:w-1/3">
                    <div class="sticky top-24">
                        <div class="bg-gray-50 rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-auto object-cover transform hover:scale-105 transition duration-700">
                        </div>
                    </div>
                </div>

                {{-- Bagian Kanan: Informasi Produk --}}
                <div class="md:w-2/3">
                    <div class="mb-2">
                        <a href="{{ route('author.show', $item->author->slug) }}"
                            class="block text-lg text-gray-500 hover:text-blue-700 transition">
                            {{ $item->author->name ?? 'Penulis' }}
                        </a>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mt-1">{{ $item->name }}</h1>
                    </div>

                    <div class="mt-4 mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex space-x-6 mb-8">
                        <button onclick="toggleFavorite(this, {{ $item->id }})"
                            class="favorite-btn flex items-center transition {{ $item->isFavorited() ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                            <i class="{{ $item->isFavorited() ? 'fa-solid' : 'fa-regular' }} fa-heart mr-2 text-xl"></i>
                            <span>Favorit</span>
                        </button>
                        <button class="flex items-center text-gray-500 hover:text-indigo-600 transition">
                            <i class="fa-solid fa-share-nodes mr-2 text-xl"></i> Bagikan
                        </button>
                    </div>

                    {{-- Deskripsi Ringkas --}}
                    <div class="border-t pt-8">
                        <h3 class="font-bold text-gray-900 mb-4">Sinopsis</h3>
                        <div class="text-gray-600 leading-relaxed prose prose-indigo">
                            {{ $item->description ?? 'Tidak ada deskripsi untuk buku ini.' }}
                        </div>
                    </div>

                    {{-- Spesifikasi Detail --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4 mt-10 p-6 bg-gray-50 rounded-2xl">
                        @if ($item->publisher)
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Penerbit</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->publisher }}</p>
                            </div>
                        @endif
                        @if ($item->isbn)
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">ISBN</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->isbn }}</p>
                            </div>
                        @endif
                        @if ($item->pages)
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Halaman</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->pages }} Halaman</p>
                            </div>
                        @endif
                        @if ($item->publication_year)
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Tahun Terbit</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->publication_year }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    @auth
                        <div class="sticky bottom-6 mt-12 md:relative md:bottom-0">
                            <form action="{{ route('cart.add', $item->id) }}" method="POST"
                                class="flex gap-4 add-to-cart-form">
                                @csrf
                                <div
                                    class="flex items-center bg-white border-2 border-gray-200 rounded-xl overflow-hidden shrink-0">
                                    <button type="button" class="px-4 py-2 hover:bg-gray-100 font-bold">-</button>
                                    <input type="number" name="quantity" value="1"
                                        class="w-12 text-center border-none focus:ring-0 font-bold" min="1">
                                    <button type="button" class="px-4 py-2 hover:bg-gray-100 font-bold">+</button>
                                </div>
                                <button data-add-to-cart
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold text-lg shadow-xl shadow-indigo-100 transition-all flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-cart-plus text-xl"></i>
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="sticky bottom-6 mt-12 md:relative md:bottom-0">
                            <div class="flex gap-4">
                                <div
                                    class="flex items-center bg-white border-2 border-gray-200 rounded-xl overflow-hidden shrink-0">
                                    <button type="button" class="px-4 py-2 hover:bg-gray-100 font-bold">-</button>
                                    <input type="number" name="quantity" value="1" disabled
                                        class="w-12 text-center border-none focus:ring-0 font-bold bg-gray-100" min="1">
                                    <button type="button" class="px-4 py-2 hover:bg-gray-100 font-bold">+</button>
                                </div>
                                <a href="{{ route('register') }}"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold text-lg shadow-xl shadow-indigo-100 transition-all flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-cart-plus text-xl"></i>
                                    Daftar untuk Membeli
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Produk Terkait --}}
            <div class="mt-24">
                <div class="flex justify-between items-end mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Mungkin Anda Juga Suka</h2>
                    <a href="#" class="text-indigo-600 font-semibold hover:underline">Lihat Semua</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach ($relatedBooks as $related)
                        <a href="{{ route('book.show', $related->slug) }}" class="group">
                            <div class="bg-white rounded-xl overflow-hidden transition group-hover:translate-y-[-5px]">
                                <img src="{{ asset('storage/' . $related->image) }}"
                                    class="w-full h-64 object-cover rounded-xl shadow-sm border">
                                <div class="mt-3">
                                    <p class="text-xs text-gray-400 mb-1">{{ $related->author->name ?? 'Penulis' }}</p>
                                    <h4
                                        class="font-bold text-gray-900 truncate text-sm group-hover:text-indigo-600 transition">
                                        {{ $related->name }}
                                    </h4>
                                    <p class="text-indigo-600 font-bold mt-1">
                                        Rp{{ number_format($related->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

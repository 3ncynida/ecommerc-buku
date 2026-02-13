<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libris | Toko Buku Online Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans text-gray-900">

    @include('layouts.navigation')

    <header class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 space-y-6">
            <span class="bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full text-sm font-semibold">Rilis Terbaru
                2026</span>
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight">Temukan Jendela <br><span
                    class="text-indigo-600">Dunia Anda</span> Disini.</h1>
            <p class="text-gray-500 text-lg max-w-md">Jelajahi ribuan koleksi buku dari penulis terbaik dunia dengan
                harga yang bersahabat dan pengiriman kilat.</p>
            <div class="flex space-x-4">
                <button
                    class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-indigo-200 hover:scale-105 transition">Mulai
                    Belanja</button>
                <button class="border border-gray-300 px-8 py-3 rounded-lg font-bold hover:bg-gray-50 transition">Lihat
                    Katalog</button>
            </div>
        </div>
        <div class="md:w-1/2 mt-12 md:mt-0 relative flex justify-center">
            <div class="w-72 h-96 bg-indigo-200 rounded-2xl absolute -rotate-6 z-0"></div>
            <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400"
                alt="Featured Book"
                class="w-72 h-96 object-cover rounded-2xl shadow-2xl z-10 relative rotate-3 hover:rotate-0 transition duration-500">
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-8 py-20">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-bold">Kategori Populer</h2>
                <p class="text-gray-500">Cari buku berdasarkan minat Anda</p>
            </div>
            <a href="{{ route('category.list') }}" class="text-indigo-600 font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $cat)
                <a href="/category/{{ $cat->slug }}" class="block">
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition cursor-pointer group text-center">
                        <div
                            class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-600 transition">
                            <i class="fa-solid fa-book-open text-indigo-600 group-hover:text-white transition text-xl"></i>
                        </div>
                        <h3 class="font-bold text-lg">{{ $cat->name }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-3xl font-bold text-gray-800">Koleksi Buku Terbaru</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($featuredBooks as $book)
                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl aspect-[3/4] bg-gray-100 mb-4 shadow-sm">
                            @if($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <img src="https://via.placeholder.com/300x400?text=No+Image" alt="No Image"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent 
                                               opacity-0 group-hover:opacity-100 transition-all duration-300
                                               flex items-center justify-center gap-4">

                                @auth
                                    <form action="{{ route('cart.add', $book->id) }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <button type="button" data-add-to-cart class="p-4 rounded-full bg-white/90 backdrop-blur
                                                                   text-gray-800 shadow-xl
                                                                   hover:bg-indigo-600 hover:text-white
                                                                   hover:scale-110 active:scale-95
                                                                   transition-all duration-300">
                                            <i class="fa-solid fa-cart-plus text-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('register') }}" class="p-4 rounded-full bg-white/90 backdrop-blur
                                                               text-gray-800 shadow-xl
                                                               hover:bg-indigo-600 hover:text-white
                                                               hover:scale-110 active:scale-95
                                                               transition-all duration-300">
                                        <i class="fa-solid fa-cart-plus text-lg"></i>
                                    </a>
                                @endauth

                                <a href="/book/{{ $book->slug }}" class="p-4 rounded-full bg-white/90 backdrop-blur
                                                   text-gray-800 shadow-xl
                                                   hover:bg-indigo-600 hover:text-white
                                                   hover:scale-110 active:scale-95
                                                   transition-all duration-300">
                                    <i class="fa-solid fa-eye text-lg"></i>
                                </a>
                            </div>

                        </div>

                        <h3
                            class="font-bold text-lg leading-tight mb-1 text-gray-900 group-hover:text-indigo-600 transition">
                            {{ $book->name }}
                        </h3>
                        <p class="text-gray-500 text-sm mb-2">{{ $book->author->name ?? 'Penulis Anonim' }}</p>

                        <div class="flex justify-between items-center">
                            <p class="text-indigo-600 font-bold text-lg">
                                Rp {{ number_format($book->price, 0, ',', '.') }}
                            </p>
                            <span
                                class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-md font-medium">Tersedia</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20">
                        <i class="fa-solid fa-book-open text-5xl text-gray-200 mb-4"></i>
                        <p class="text-gray-500 italic">Belum ada buku yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-gray-800 pb-12 mb-10">
            <div class="space-y-4">
                <h2 class="text-2xl font-bold italic">Libris.</h2>
                <p class="text-gray-400">Toko buku online terpercaya yang menyediakan berbagai macam literatur untuk
                    mencerahkan masa depan bangsa.</p>
            </div>
            <div>
                <h4 class="font-bold mb-6">Tautan Cepat</h4>
                <ul class="space-y-4 text-gray-400">
                    <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-white">Syarat & Ketentuan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">Layanan Pelanggan</h4>
                <ul class="space-y-4 text-gray-400">
                    <li><a href="#" class="hover:text-white">Bantuan</a></li>
                    <li><a href="#" class="hover:text-white">Lacak Pesanan</a></li>
                    <li><a href="#" class="hover:text-white">Hubungi Kami</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">Newsletter</h4>
                <div class="flex">
                    <input type="email" placeholder="Email Anda"
                        class="bg-gray-800 px-4 py-2 rounded-l-lg w-full focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <button class="bg-indigo-600 px-4 py-2 rounded-r-lg hover:bg-indigo-700 transition">Kirim</button>
                </div>
            </div>
        </div>
        <p class="text-center text-gray-500 text-sm">© 2026 Libris E-commerce. Dibuat dengan cinta & Laravel.</p>
    </footer>

</body>

</html>
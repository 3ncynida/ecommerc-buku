<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libris | Toko Buku Online Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-slate-50 font-sans text-gray-900 antialiased selection:bg-indigo-500 selection:text-white">

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>

    @include('layouts.navigation')

    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-50/90 via-purple-50/80 to-white/90 backdrop-blur-xl border-b border-indigo-100">
        <!-- Decorative blobs -->
        <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob z-0"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000 z-0"></div>
        <div class="absolute -bottom-8 left-40 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000 z-0"></div>



        <header class="relative max-w-7xl mx-auto px-8 py-20 lg:py-28 flex flex-col md:flex-row items-center z-10">
            <div class="md:w-1/2 space-y-8 relative z-10">
                <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md px-4 py-2 rounded-full text-sm font-bold text-indigo-700 shadow-sm border border-indigo-100">
                    <span class="flex w-2.5 h-2.5 bg-indigo-600 rounded-full animate-pulse"></span>
                    Rilis Terbaru 2026
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-indigo-800 to-gray-900 leading-[1.1] tracking-tight">
                    Temukan Jendela <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Dunia Anda</span> Disini.
                </h1>
                <p class="text-gray-600 text-lg md:text-xl max-w-lg leading-relaxed font-medium">
                    Jelajahi ribuan koleksi buku dari penulis terbaik dunia dengan harga yang bersahabat dan pengiriman kilat.
                </p>
            <div class="relative max-w-md">
                <div class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-lg hidden z-20"
                    data-home-search-results>
                    <ul class="max-h-72 overflow-y-auto" data-home-search-list></ul>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('category.index') }}"
                    class="relative overflow-hidden group bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold shadow-xl shadow-indigo-200/50 hover:shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-1 text-center">
                    <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></span>
                    <span class="relative z-10 flex items-center justify-center gap-2">Mulai Belanja <i class="fa-solid fa-arrow-right text-sm"></i></span>
                </a>
            </div>
        </div>
        <div class="md:w-1/2 mt-12 md:mt-0 relative hidden md:flex justify-end pr-10">
            <!-- Decorative Cards Setup -->
            <div class="relative w-[320px] h-[420px]">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-400 to-purple-400 rounded-3xl transform rotate-6 translate-x-4 translate-y-4 opacity-50 blur-lg"></div>
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600 to-purple-500 rounded-3xl transform rotate-3 z-0 transition-transform duration-500 hover:rotate-6"></div>
                <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400"
                    alt="Featured Book"
                    class="absolute inset-0 w-full h-full object-cover rounded-3xl shadow-2xl z-10 transform -rotate-3 hover:rotate-0 transition-all duration-500 hover:scale-[1.02] border-4 border-white/20">
            </div>
        </div>
        </header>
    </div> <!-- End Hero Wrap -->

    <section class="w-full bg-indigo-50/50 border-t border-indigo-100">
        <div class="relative max-w-7xl mx-auto px-8 py-24">
            <div class="flex flex-col md:flex-row justify-between md:items-end mb-12 gap-4">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Kategori <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Terpopuler</span></h2>
                <p class="text-gray-500 text-lg">Jelajahi karya terbaik berdasarkan minat Anda</p>
            </div>
            <a href="{{ route('category.list') }}" class="group flex items-center gap-2 text-indigo-600 font-bold hover:text-purple-700 transition">
                Lihat Semua Kategori <i class="fa-solid fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
            @foreach($categories as $cat)
                <a href="/category/{{ $cat->slug }}" class="group block h-full">
                    <div class="relative bg-white p-8 rounded-3xl border border-gray-100/50 shadow-sm hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 cursor-pointer h-full overflow-hidden transform hover:-translate-y-2">
                        <!-- BG Decoration -->
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out z-0"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            <div class="w-20 h-20 bg-gradient-to-tr from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:from-indigo-600 group-hover:to-purple-600 transition-colors duration-500 shadow-inner group-hover:shadow-indigo-500/30 transform group-hover:rotate-3">
                                <i class="fa-solid fa-book-open text-indigo-600 group-hover:text-white transition-colors duration-500 text-3xl"></i>
                            </div>
                            <h3 class="font-extrabold text-xl text-gray-800 group-hover:text-indigo-700 transition-colors">{{ $cat->name }}</h3>
                            <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <span class="text-sm font-bold text-indigo-600 flex items-center gap-1">Eksplorasi <i class="fa-solid fa-chevron-right text-[10px]"></i></span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
            </div>
        </div>
    </section>

    <section class="w-full bg-amber-50 border-t border-amber-100">
        <div class="max-w-7xl mx-auto px-8 py-24 relative">
            <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Buku <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">Terlaris</span></h2>
                <p class="text-gray-500 text-lg">Pilihan favorit pembaca minggu ini</p>
            </div>
        </div>
        <div class="flex gap-6 overflow-x-auto pb-10 pt-4 snap-x snap-mandatory scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
            @forelse($bestSellers as $book)
                <div class="group shrink-0 w-[240px] md:w-[280px] snap-start">
                    <div class="relative overflow-hidden rounded-[2rem] aspect-[3/4.2] bg-gray-50 mb-5 shadow-sm group-hover:shadow-xl transition-all duration-500 border border-gray-100/50">
                        @if($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                        @else
                            <img src="https://via.placeholder.com/300x400?text=No+Image" alt="No Image"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                        @endif

                        <div class="absolute top-4 left-4 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg border border-white/20 uppercase tracking-widest z-10 backdrop-blur-sm">
                            <i class="fa-solid fa-fire mr-1"></i> Terlaris
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/40 to-transparent
                                               opacity-0 group-hover:opacity-100 transition-all duration-300
                                               flex items-center justify-center gap-4 backdrop-blur-[2px]">

                            @if($book->stok <= 0)
                                <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                                    Stok Habis
                                </span>
                            @else
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
                            @endif

                            <a href="/book/{{ $book->slug }}" class="p-4 rounded-full bg-white/90 backdrop-blur
                                                   text-gray-800 shadow-xl
                                                   hover:bg-indigo-600 hover:text-white
                                                   hover:scale-110 active:scale-95
                                                   transition-all duration-300">
                                <i class="fa-solid fa-eye text-lg"></i>
                            </a>
                        </div>
                    </div>

                    <div class="px-2">
                        <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">{{ $book->author->name ?? 'Penulis Anonim' }}</p>
                        <h3 class="font-extrabold text-lg leading-tight mb-3 text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 h-12">
                            {{ $book->name }}
                        </h3>

                        <div class="flex justify-between items-end">
                            <p class="text-indigo-600 font-black text-xl">
                                Rp {{ number_format($book->price, 0, ',', '.') }}
                            </p>
                            <span class="text-[10px] bg-emerald-50 text-emerald-600 border border-emerald-200 px-2.5 py-1 rounded-md font-black uppercase tracking-wider">Tersedia</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fa-solid fa-book-open text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-500 italic">Belum ada data penjualan untuk ditampilkan.</p>
                </div>
            @endforelse
            </div>
        </div>
    </section>

    <section class="w-full bg-gradient-to-b from-emerald-50 to-teal-50 py-24 border-t border-emerald-100">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Koleksi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500">Terbaru</span></h2>
                    <p class="text-gray-500 text-lg">Karya segar yang baru saja tiba di rak kami</p>
                </div>
            </div>

            <div class="flex gap-6 overflow-x-auto pb-10 pt-4 snap-x snap-mandatory scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                @forelse($featuredBooks as $book)
                    <div class="group shrink-0 w-[240px] md:w-[280px] snap-start">
                        <div class="relative overflow-hidden rounded-[2rem] aspect-[3/4.2] bg-gray-50 mb-5 shadow-sm group-hover:shadow-xl transition-all duration-500 border border-gray-100/50">
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-emerald-400 to-teal-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg border border-white/20 uppercase tracking-widest z-10 backdrop-blur-sm">
                                Baru
                            </div>
                            @if($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                            @else
                                <img src="https://via.placeholder.com/300x400?text=No+Image" alt="No Image"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent
                                                   opacity-0 group-hover:opacity-100 transition-all duration-300
                                                   flex items-center justify-center gap-4 backdrop-blur-[2px]">

                                @if($book->stok <= 0)
                                    <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Stok Habis
                                    </span>
                                @else
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
                                @endif

                                <a href="/book/{{ $book->slug }}" class="p-4 rounded-full bg-white/90 backdrop-blur
                                                       text-gray-800 shadow-xl
                                                       hover:bg-indigo-600 hover:text-white
                                                       hover:scale-110 active:scale-95
                                                       transition-all duration-300">
                                    <i class="fa-solid fa-eye text-lg"></i>
                                </a>
                            </div>

                        </div>

                        <div class="px-2">
                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">{{ $book->author->name ?? 'Penulis Anonim' }}</p>
                            <h3 class="font-extrabold text-lg leading-tight mb-3 text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 h-12">
                                {{ $book->name }}
                            </h3>

                            <div class="flex justify-between items-end">
                                <p class="text-indigo-600 font-black text-xl">
                                    Rp {{ number_format($book->price, 0, ',', '.') }}
                                </p>
                                <span class="text-[10px] bg-emerald-50 text-emerald-600 border border-emerald-200 px-2.5 py-1 rounded-md font-black uppercase tracking-wider">Tersedia</span>
                            </div>
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
                <h2 class="flex items-center gap-2 text-2xl font-black italic">
                    <x-application-logo class="w-8 h-8 text-indigo-500" />
                    Libris.
                </h2>
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

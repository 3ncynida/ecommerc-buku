@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-20">
        <!-- Header Banner -->
        <div class="relative bg-gradient-to-r from-indigo-900 to-purple-800 mb-12 overflow-hidden shadow-xl border-b border-indigo-900">
            <!-- Decorative Blobs -->
            <div class="absolute inset-0 opacity-30 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-80 h-80 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
                <div class="absolute top-10 right-20 w-96 h-96 bg-indigo-400 rounded-full mix-blend-overlay filter blur-3xl"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-8 py-16 z-10 flex flex-col items-center text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-indigo-100 text-[11px] font-black uppercase tracking-widest mb-6 border border-white/20 shadow-inner">
                    <i class="fa-solid fa-fire text-amber-400"></i> Pilihan Favorit
                </div>
                <h1 class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-indigo-100 tracking-tight mb-4 drop-shadow-lg">Koleksi Buku Terpopuler</h1>
                <p class="text-indigo-100/80 text-lg md:text-xl font-medium max-w-2xl">Temukan berbagai genre menarik dan cerita memukau untuk menemani hari-hari Anda.</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-8 space-y-24">
            {{-- Loop Berdasarkan Kategori --}}
            @foreach($categories as $category)
                <section>
                    <div class="flex items-end justify-between mb-10 pb-4 border-b-2 border-gray-100/50 relative">
                        <!-- Header Accent -->
                        <div class="absolute -bottom-[2px] left-0 w-24 h-[2px] bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
                        
                        <div class="relative">
                            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight flex items-center group-hover:text-indigo-600 transition-colors">
                                {{ $category->name }}
                            </h2>
                            <p class="text-[11px] text-indigo-500 font-black uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                                <span class="w-4 h-[1px] bg-indigo-500 inline-block"></span> Koleksi Terpilih
                            </p>
                        </div>

                        <a href="{{ route('category.show', $category->slug) }}"
                            class="group flex items-center text-sm font-bold text-indigo-600 hover:text-purple-600 transition-all duration-300 bg-indigo-50 px-5 py-2.5 rounded-full hover:bg-purple-50">
                            <span class="mr-2">Lihat Semua</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($category->items->take(6) as $book) {{-- Ambil 6 buku per kategori --}}
                            <div class="group relative bg-white/80 backdrop-blur-sm border border-gray-100/80 rounded-[1.5rem] p-3 shadow-sm hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 hover:-translate-y-1">

                                <button onclick="toggleFavorite(this, {{ $book->id }})"
                                    class="absolute top-5 right-5 z-20 bg-white/90 backdrop-blur-md w-7 h-7 flex items-center justify-center rounded-full text-red-500 shadow-md hover:scale-110 active:scale-95 transition-all">
                                    <i class="{{ $book->isFavorited() ? 'fa-solid' : 'fa-regular' }} fa-heart text-xs"></i>
                                </button>

                                <div class="relative aspect-[3/4.2] mb-4 overflow-hidden rounded-[1.25rem] bg-gray-50 border border-gray-100/50 group-hover:border-indigo-100 transition-colors">
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">

                                    {{-- Overlay Stok Habis --}}
                                    @if($book->stok <= 0)
                                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4">
                                            <span class="bg-red-500 text-white text-[9px] font-black px-3 py-1.5 rounded-full shadow-2xl border border-red-400/50 uppercase tracking-widest">
                                                Habis
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="px-1 pb-1">
                                    <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1 truncate">
                                        {{ $book->author->name ?? 'Unknown Author' }}
                                    </p>
                                    <h3 class="text-sm font-extrabold text-gray-900 line-clamp-2 leading-snug group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-600 transition-all h-10">
                                        {{ $book->name }}
                                    </h3>

                                    <div class="pt-2 mt-auto">
                                        <p class="text-[13px] font-black text-indigo-600">
                                            Rp {{ number_format($book->price, 0, ',', '.') }}
                                        </p>
                                        {{-- Contoh Harga Diskon --}}
                                        @if($book->old_price)
                                            <div class="flex items-center space-x-1 mt-0.5">
                                                <span class="text-[9px] text-gray-400 line-through font-bold">Rp {{ number_format($book->old_price, 0, ',', '.') }}</span>
                                                <span class="text-[9px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded-md font-black">10%</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <a href="/book/{{ $book->slug }}" class="absolute inset-0 z-10"></a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
@endsection
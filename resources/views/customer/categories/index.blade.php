@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-20">
        <div class="bg-white border-b border-gray-100 mb-8">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <h1 class="text-2xl font-bold text-gray-800">Koleksi Buku Terpopuler</h1>
                <p class="text-gray-500 text-sm">Temukan berbagai genre menarik untuk menemani hari Anda.</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 space-y-16">
            {{-- Loop Berdasarkan Kategori --}}
            @foreach($categories as $category)
                <section>
                    <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                        <div class="relative">
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight flex items-center">
                                {{ $category->name }}
                            </h2>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-1">Koleksi Terpilih</p>
                        </div>

                        <a href="{{ route('category.show', $category->slug) }}"
                            class="group flex items-center text-sm font-bold text-gray-400 hover:text-indigo-600 transition-all duration-300">
                            <span class="mr-2">Lihat Semua</span>
                            <div
                                class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center group-hover:border-indigo-600 group-hover:bg-indigo-50 transition-all">
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </div>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($category->items->take(6) as $book) {{-- Ambil 6 buku per kategori --}}
                            <div
                                class="group relative bg-white border border-gray-100 rounded-2xl p-3 shadow-sm hover:shadow-xl transition-all duration-300">

                                <button onclick="toggleFavorite(this, {{ $book->id }})"
                                    class="absolute top-4 right-4 z-10 bg-white/80 backdrop-blur-sm p-1.5 rounded-full text-red-500 shadow-sm">
                                    <i class="{{ $book->isFavorited() ? 'fa-solid' : 'fa-regular' }} fa-heart text-sm"></i>
                                </button>

                                <div class="relative aspect-[3/4] mb-4 overflow-hidden rounded-xl bg-gray-100">
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                                    {{-- Overlay Stok Habis (Contoh Logika) --}}
                                    @if($book->stok <= 0)
                                        <div
                                            class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center p-4">
                                            <span
                                                class="bg-white/90 text-red-600 text-xs font-black px-3 py-1.5 rounded-full shadow-lg uppercase tracking-tighter">
                                                Stok Habis
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    <p class="text-[11px] text-gray-400 font-medium truncate uppercase tracking-tighter">
                                        {{ $book->author->name ?? 'Unknown Author' }}
                                    </p>
                                    <h3
                                        class="text-sm font-bold text-gray-900 line-clamp-2 leading-tight group-hover:text-indigo-600 transition h-10">
                                        {{ $book->name }}
                                    </h3>

                                    <div class="pt-2">
                                        <p class="text-sm font-black text-gray-900">
                                            Rp {{ number_format($book->price, 0, ',', '.') }}
                                        </p>
                                        {{-- Contoh Harga Diskon --}}
                                        @if($book->old_price)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[10px] text-gray-400 line-through">Rp
                                                    {{ number_format($book->old_price, 0, ',', '.') }}</span>
                                                <span class="text-[10px] text-red-500 font-bold">10%</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <a href="/book/{{ $book->slug }}" class="absolute inset-0 z-0"></a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
@endsection
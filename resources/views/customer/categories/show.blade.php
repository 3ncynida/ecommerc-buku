@extends('customer.layouts.app')

@section('content')
    <div class="bg-slate-50 min-h-screen pb-20">
        
        <!-- Hero Banner Kategori -->
        <div class="relative bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 py-16 mb-10 overflow-hidden shadow-xl">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-30 pointer-events-none">
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
                <div class="absolute top-20 right-10 w-96 h-96 bg-indigo-500 rounded-full mix-blend-overlay filter blur-3xl"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-8 z-10">
                <div class="flex flex-col md:flex-row items-end justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-indigo-100 text-[11px] font-black uppercase tracking-widest mb-4 border border-white/20 shadow-inner">
                            <i class="fa-solid fa-layer-group"></i> Kategori Koleksi
                        </div>
                        <h1 class="text-5xl md:text-6xl font-black text-white tracking-tight drop-shadow-2xl">{{ $category->name }}</h1>
                    </div>
                    
                    <form action="{{ url()->current() }}" method="GET" id="sortForm" class="w-full md:w-auto mt-4 md:mt-0">
                        @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                        <div class="relative inline-block w-full md:w-64">
                            <select name="sort" onchange="this.form.submit()"
                                class="block w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-xl py-3 px-5 text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-white/50 appearance-none shadow-xl cursor-pointer hover:bg-white/20 transition-colors">
                                <option value="terbaru" class="text-gray-900" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="termurah" class="text-gray-900" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Termurah</option>
                                <option value="termahal" class="text-gray-900" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Termahal</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white">
                                <i class="fa-solid fa-sort text-[12px]"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-8">

            <div class="flex items-center gap-3 mb-10">
                {{-- Tombol Reset: Menghapus filter tapi mempertahankan sort --}}
                <a href="{{ route('category.show', ['category' => $category->slug, 'sort' => request('sort')]) }}"
                    class="w-12 h-12 flex items-center justify-center border border-gray-200/80 rounded-full hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all duration-300 bg-white/80 backdrop-blur-sm shadow-sm group">
                    <i class="fa-solid fa-rotate-left text-gray-400 group-hover:text-red-500 transition-colors"></i>
                </a>

                {{-- Tombol Stok Tersedia --}}
                <a href="{{ route('category.show', ['category' => $category->slug, 'filter' => 'stok', 'sort' => request('sort')]) }}"
                    class="inline-flex items-center px-6 py-3 rounded-full text-sm font-black transition-all duration-300 shadow-sm uppercase tracking-wide
                           {{ request('filter') == 'stok' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-indigo-200/50' : 'bg-white/80 backdrop-blur-sm text-gray-600 border border-gray-200/80 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200' }}">
                    <i class="fa-solid fa-box-open mr-2 {{ request('filter') == 'stok' ? 'text-white' : 'text-gray-400' }}"></i>
                    Stok Tersedia
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 lg:gap-8">
                @forelse($items as $book)
                    <div class="group relative bg-white/80 backdrop-blur-sm border border-gray-100/80 rounded-[2rem] p-4 shadow-sm hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 hover:-translate-y-1">

                        <button onclick="toggleFavorite(this, {{ $book->id }})"
                            class="absolute top-6 right-6 z-20 bg-white/90 backdrop-blur-md w-8 h-8 flex items-center justify-center rounded-full text-red-500 shadow-md hover:scale-110 active:scale-95 transition-all">
                            <i class="{{ $book->isFavorited() ? 'fa-solid' : 'fa-regular' }} fa-heart text-sm"></i>
                        </button>

                        <div class="relative aspect-[3/4.2] mb-5 overflow-hidden rounded-[1.5rem] bg-gray-100/50 border border-gray-100 group-hover:border-indigo-100 transition-colors">
                            <img src="{{ asset('storage/' . $book->image) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-out">

                            {{-- Overlay Stok Habis --}}
                            @if($book->stok <= 0)
                                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center p-4">
                                    <span class="bg-red-500 text-white text-[10px] font-black px-4 py-2 rounded-full shadow-2xl border border-red-400/50 uppercase tracking-widest">
                                        Stok Habis
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="px-2 pb-2">
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1 truncate">
                                {{ $book->author->name ?? 'Penulis' }}
                            </p>
                            <h3 class="text-sm font-extrabold text-gray-900 line-clamp-2 leading-snug h-10 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-600 transition-all">
                                {{ $book->name }}
                            </h3>
                            <div class="pt-3 flex justify-between items-end">
                                <p class="text-lg font-black text-indigo-600">
                                    Rp {{ number_format($book->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Link ke halaman detail buku --}}
                        <a href="/book/{{ $book->slug }}" class="absolute inset-0 z-10"></a>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gray-400 font-medium italic">Tidak ada buku yang sesuai dengan kriteria ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
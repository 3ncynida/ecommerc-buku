@extends('customer.layouts.app')

@section('content')
<div class="bg-white min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $category->name }}</h1>
            
            <form action="{{ url()->current() }}" method="GET" id="sortForm">
                @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                <div class="relative inline-block w-64">
                    <select name="sort" onchange="this.form.submit()" 
                        class="block w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none shadow-sm cursor-pointer">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Termurah</option>
                        <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Termahal</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </form>
        </div>

        <div class="flex items-center gap-3 mb-12">
            {{-- Tombol Reset: Menghapus filter tapi mempertahankan sort --}}
            <a href="{{ route('category.show', ['id' => $category->id, 'sort' => request('sort')]) }}" 
               class="w-11 h-11 flex items-center justify-center border border-gray-200 rounded-full hover:bg-gray-50 transition bg-white shadow-sm">
                <i class="fa-solid fa-xmark text-gray-400"></i>
            </a>

            {{-- Tombol Stok Tersedia --}}
            <a href="{{ route('category.show', ['id' => $category->id, 'filter' => 'stok', 'sort' => request('sort')]) }}" 
               class="inline-flex items-center px-6 py-2.5 border rounded-full text-sm font-bold transition shadow-sm
               {{ request('filter') == 'stok' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                <i class="fa-solid fa-circle-check mr-2 {{ request('filter') == 'stok' ? 'text-indigo-400' : 'text-gray-300' }}"></i>
                Stok Tersedia
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            @forelse($items as $book)
            <div class="group relative bg-white border border-gray-100 rounded-[2rem] p-4 shadow-sm hover:shadow-2xl transition-all duration-500">
                
                <div class="absolute top-6 left-6 z-10">
                    <span class="bg-indigo-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-sm">
                        <i class="fa-solid fa-id-badge mr-1"></i> ID
                    </span>
                </div>
                <button class="absolute top-6 right-6 z-10 text-gray-300 hover:text-red-500 transition">
                    <i class="fa-solid fa-heart"></i>
                </button>

                <div class="relative aspect-[3/4.2] mb-5 overflow-hidden rounded-[1.5rem] bg-gray-50">
                    <img src="{{ asset('storage/' . $book->image) }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    
                    {{-- Overlay Stok Habis --}}
                    @if($book->Stok <= 0)
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center p-4">
                        <span class="bg-white text-red-600 text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg border border-red-100 uppercase tracking-widest">
                            Stok Habis
                        </span>
                    </div>
                    @endif
                </div>

                <div class="space-y-1">
                    <p class="text-[10px] text-gray-400 font-bold uppercase truncate tracking-tight">
                        {{ $book->author->name ?? 'Penulis' }}
                    </p>
                    <h3 class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug h-10 group-hover:text-indigo-600 transition">
                        {{ $book->name }}
                    </h3>
                    <div class="pt-2">
                        <p class="text-base font-black text-gray-900">
                            Rp {{ number_format($book->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Link ke halaman detail buku --}}
                <a href="{{ route('book.show', $book->id) }}" class="absolute inset-0 z-0"></a>
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
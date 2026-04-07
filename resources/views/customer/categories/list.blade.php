@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-8">

            {{-- Header Section --}}
            <div class="text-center mb-16 relative">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -z-10 animate-pulse"></div>
                <h1 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-600 mb-4 tracking-tight">Jelajahi Kategori</h1>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Temukan ribuan koleksi buku terbaik yang dikelompokkan berdasarkan minat dan kebutuhan membaca Anda.</p>
            </div>

            {{-- Category Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" class="group h-full">
                        <div class="relative bg-white/60 backdrop-blur-md p-8 rounded-[2.5rem] border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(99,102,241,0.15)] transition-all duration-500 flex flex-col items-center text-center h-full hover:-translate-y-2 overflow-hidden">
                            
                            <!-- background decoration -->
                            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full group-hover:scale-[2] transition-transform duration-700 ease-in-out opacity-50 z-0"></div>

                            {{-- Icon --}}
                            <div class="relative z-10 w-24 h-24 bg-gradient-to-tr from-indigo-50 to-purple-50 rounded-[1.5rem] flex items-center justify-center mb-6 group-hover:from-indigo-500 group-hover:to-purple-600 transition-all duration-500 shadow-inner group-hover:shadow-indigo-500/40 group-hover:-rotate-6">
                                <i class="{{ $category->icon ?? 'fa-solid fa-book-open' }} text-3xl text-indigo-500 group-hover:text-white transition-colors duration-500"></i>
                            </div>

                            {{-- Category Info --}}
                            <h3 class="relative z-10 text-xl font-extrabold text-gray-800 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-600 transition-all">
                                {{ $category->name }}
                            </h3>

                            {{-- Menampilkan jumlah buku dalam kategori --}}
                            <p class="relative z-10 text-xs font-bold text-gray-400 mt-2 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors uppercase tracking-widest">
                                {{ $category->items_count ?? '0' }} Koleksi
                            </p>

                            <div class="relative z-10 mt-6 text-indigo-600 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center text-sm font-bold transform translate-y-4 group-hover:translate-y-0">
                                Eksplorasi <i class="fa-solid fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
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
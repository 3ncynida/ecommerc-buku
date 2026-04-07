@extends('customer.layouts.app')

@section('content')
    <div class="bg-slate-50 min-h-screen relative overflow-hidden">
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -z-10 animate-blob"></div>
        <div class="absolute top-40 left-0 w-[400px] h-[400px] bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -z-10 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 py-8 relative z-10">

            {{-- Breadcrumbs --}}
            <nav class="flex items-center mb-10 text-sm text-gray-500 overflow-x-auto whitespace-nowrap bg-white/60 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/60 shadow-sm w-max">
                <a href="/" class="hover:text-indigo-600 font-bold transition-colors">Home</a>
                <span class="mx-3 text-gray-300"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
                
                {{-- Mengambil kategori pertama sebagai path breadcrumb --}}
                @if($item->categories->isNotEmpty())
                    <a href="{{ route('category.show', $item->categories->first()->slug) }}"
                        class="hover:text-indigo-600">{{ $item->categories->first()->name }}</a>
                @else
                    <span class="text-gray-400">Kategori</span>
                @endif
                
                <span class="mx-3 text-gray-300"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
                <span class="text-indigo-600 font-black truncate">{{ $item->name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-12">

                {{-- Bagian Kiri: Gambar (Sticky) --}}
                <div class="md:w-1/3 relative">
                    <div class="sticky top-28">
                        <div class="relative bg-white p-3 rounded-[2.5rem] shadow-xl shadow-indigo-100 border border-white overflow-hidden group">
                            <!-- Glow effect -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-100 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2.5rem] z-0"></div>
                            
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-auto object-cover rounded-[2rem] transform group-hover:scale-[1.02] transition-all duration-700 relative z-10 shadow-sm border border-gray-100/50">
                        </div>
                    </div>
                </div>

                {{-- Bagian Kanan: Informasi Produk --}}
                <div class="md:w-2/3 md:pl-6">
                    <div class="mb-4">
                        <a href="{{ route('author.show', $item->author->slug) }}"
                            class="inline-block text-xs font-black text-indigo-500 hover:text-indigo-700 transition tracking-widest uppercase mb-2 bg-indigo-50 px-3 py-1 rounded-md border border-indigo-100">
                            {{ $item->author->name ?? 'Penulis Anonim' }}
                        </a>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-indigo-900 leading-[1.2] mt-1">{{ $item->name }}</h1>
                        
                        {{-- Bintang & Rating --}}
                        <div class="flex items-center gap-3 mt-3">
                            <div class="flex text-amber-400 text-[15px]">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= round($item->average_rating) ? '' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                            <span class="text-[14px] font-black text-slate-800">{{ number_format($item->average_rating, 1) }}</span>
                            <span class="text-[13px] font-medium text-slate-400 relative top-[1px]">(&bull; {{ $item->review_count }} Ulasan)</span>
                        </div>
                        
                        {{-- LIST BANYAK KATEGORI / GENRE --}}
                        <div class="flex flex-wrap gap-2 mt-4">
                            @foreach ($item->categories as $category)
                                <a href="{{ route('category.show', $category->slug) }}" 
                                   class="px-3 py-1 bg-slate-50 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition duration-300">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8 mb-8 flex items-end gap-4">
                        <span class="text-5xl font-black text-indigo-600 tracking-tight">
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </span>
                        @if($item->old_price)
                            <span class="text-lg text-gray-400 line-through font-bold mb-1">Rp{{ number_format($item->old_price, 0, ',', '.') }}</span>
                        @endif
                    </div>

<div class="flex flex-wrap gap-4 mb-10">
    <button type="button" 
        id="fav-btn-{{ $item->id }}"
        onclick="toggleFavorite(this, {{ $item->id }})"
        class="flex items-center px-4 py-2.5 rounded-xl border {{ $item->isFavorited() ? 'bg-red-50 border-red-200 text-red-500' : 'bg-white border-gray-200 text-gray-600 hover:text-red-500 hover:border-red-200 hover:bg-red-50' }} font-bold transition-all shadow-sm">
        <i class="{{ $item->isFavorited() ? 'fa-solid fa-heart' : 'fa-regular fa-heart' }} mr-2 text-lg fav-icon"></i>
        <span class="fav-text">{{ $item->isFavorited()}} Favorit</span>
    </button>

    <button
        id="share-button"
        type="button"
        data-title="{{ $item->name }}"
        data-text="Cek buku ini: {{ $item->name }}"
        data-url="{{ url()->current() }}"
        class="flex items-center px-4 py-2.5 rounded-xl border bg-white border-gray-200 text-gray-600 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 font-bold transition-all shadow-sm">
        <i class="fa-solid fa-share-nodes mr-2 text-lg"></i> Bagikan
    </button>
</div>

                    {{-- Deskripsi/Sinopsis --}}
                    <div class="border-t-2 border-dashed border-gray-200/60 pt-8 relative">
                        <h3 class="font-black text-gray-900 mb-5 flex items-center gap-3">
                            <i class="fa-solid fa-align-left text-indigo-500"></i> Sinopsis Cerita
                        </h3>
                        <div class="text-gray-600 leading-relaxed max-w-none font-medium text-[15px]">
                            {!! nl2br(e($item->description ?? 'Tidak ada deskripsi untuk buku ini.')) !!}
                        </div>
                    </div>

                    {{-- Spesifikasi Detail --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-8 gap-x-6 mt-12 p-8 bg-white/60 backdrop-blur-sm rounded-[2rem] border border-white shadow-[0_4px_20px_rgb(0,0,0,0.03)] relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-50 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
                        @if ($item->publisher)
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Penerbit</p>
                                <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-building text-gray-300 text-xs"></i>
                                    {{ $item->publisher }}
                                </p>
                            </div>
                        @endif

                        @if ($item->isbn)
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">ISBN</p>
                                <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-barcode text-gray-300 text-xs"></i>
                                    {{ $item->isbn }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Ketersediaan</p>
                            <p class="text-sm font-bold flex items-center gap-2 {{ $item->stok <= 5 ? 'text-rose-600' : 'text-emerald-600' }}">
                                <i class="fa-solid {{ $item->stok <= 5 ? 'fa-box-open' : 'fa-boxes-stacked' }} text-xs opacity-70"></i>
                                {{ $item->stok > 0 ? $item->stok . ' Unit Tersedia' : 'Stok Habis' }}
                            </p>
                        </div>

                        @if ($item->pages)
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Halaman</p>
                                <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-book-open text-gray-300 text-xs"></i>
                                    {{ $item->pages }} Halaman
                                </p>
                            </div>
                        @endif

                        @if ($item->publication_year)
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Tahun Terbit</p>
                                <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-gray-300 text-xs"></i>
                                    {{ $item->publication_year }}
                                </p>
                            </div>
                        @endif

                        @if ($item->language)
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Bahasa</p>
                                <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-globe text-gray-300 text-xs"></i>
                                    {{ $item->language }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="sticky bottom-6 mt-12 md:relative md:bottom-0 bg-white/80 backdrop-blur-xl p-4 md:p-0 md:bg-transparent md:backdrop-blur-none border-t border-gray-200 md:border-none -mx-4 md:mx-0 px-4 z-40 rounded-t-3xl md:rounded-none">
                        @auth
                            @if($item->stok > 0)
                                <form action="{{ route('cart.add', $item->id) }}" method="POST" class="flex flex-col sm:flex-row gap-4 add-to-cart-form">
                                    @csrf
                                    <div class="flex items-center bg-white border-2 border-indigo-100 rounded-2xl overflow-hidden shrink-0 shadow-sm">
                                        <button type="button" onclick="changeQuantity(this, -1)"
                                            class="px-5 py-3 hover:bg-indigo-50 text-indigo-600 font-black text-xl transition-colors">-</button>
                                        <input type="number" name="quantity" value="1" max="{{ $item->stok }}"
                                            class="w-14 text-center border-none focus:ring-0 font-black text-gray-800 text-lg p-0" min="1">
                                        <button type="button" onclick="changeQuantity(this, 1)"
                                            class="px-5 py-3 hover:bg-indigo-50 text-indigo-600 font-black text-xl transition-colors">+</button>
                                    </div>
                                    <button type="submit"
                                        class="group relative flex-1 overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200/50 hover:shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-1">
                                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0"></div>
                                        <div class="relative z-10 flex items-center justify-center gap-3 w-full h-full py-4">
                                            <i class="fa-solid fa-cart-shopping text-xl group-hover:rotate-12 transition-transform"></i>
                                            Tambah ke Keranjang
                                        </div>
                                    </button>
                                </form>
                            @else
                                <div class="text-center w-full bg-red-50 border border-red-200 rounded-2xl py-4 font-black text-red-600 shadow-sm flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-face-frown"></i> Maaf, Stok Kosong
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('login') }}"
                                    class="group relative flex-1 overflow-hidden bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200/50 hover:-translate-y-1 transition-all duration-300 transform">
                                    <div class="absolute inset-0 bg-white/20 translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out z-0"></div>
                                    <div class="relative z-10 flex items-center justify-center gap-3 w-full h-full py-4">
                                        <i class="fa-solid fa-lock text-lg"></i>
                                        Login untuk Membeli
                                    </div>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Ulasan Pembeli Section --}}
            <div class="mt-24 pt-16 border-t-2 border-dashed border-gray-200/60 relative">
                <!-- Anchor offset for decoration -->
                <div class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 bg-slate-50 px-4 text-indigo-200 text-xl">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="flex flex-col md:flex-row gap-12">
                    
                    {{-- Rekap & Form Review --}}
                    <div class="md:w-1/3">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Ulasan Pembeli</h2>
                        
                        {{-- Ringkasan Besar --}}
                        <div class="flex items-center gap-4 mb-6">
                            <span class="text-6xl font-black text-slate-900">{{ number_format($item->average_rating, 1) }}</span>
                            <div>
                                <div class="flex text-amber-400 text-lg mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= round($item->average_rating) ? '' : 'text-slate-200' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-[13px] font-medium text-slate-500">Berdasarkan {{ $item->review_count }} ulasan</p>
                            </div>
                        </div>

                        {{-- Alert Session --}}
                        @if(session('error'))
                            <div class="bg-rose-50 text-rose-600 p-4 rounded-xl text-sm font-medium mb-6">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm font-medium mb-6">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Form Review Logic --}}
                        @auth
                            @if($canReview)
                                <div class="bg-gray-50/50 p-6 rounded-[1.25rem] border border-gray-100">
                                    <h4 class="font-bold text-gray-900 mb-4 text-[13px]">{{ $existingReview ? 'Ubah Ulasan Anda' : 'Tulis Ulasan Anda' }}</h4>
                                    <form action="{{ route('reviews.store', $item->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-4" x-data="{ currentRating: {{ $existingReview?->rating ?? 0 }}, hoverRating: 0 }">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Beri Bintang</label>
                                            <div class="flex gap-2" @mouseleave="hoverRating = 0">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="cursor-pointer" @mouseenter="hoverRating = {{ $i }}" @click="currentRating = {{ $i }}">
                                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required {{ ($existingReview?->rating == $i) ? 'checked' : '' }} x-model="currentRating">
                                                        <div class="w-8 h-8 rounded-lg bg-white border flex items-center justify-center transition-all shadow-sm"
                                                             :class="(hoverRating >= {{ $i }} || (hoverRating === 0 && currentRating >= {{ $i }})) ? 'border-amber-400 text-amber-400' : 'border-gray-200 text-gray-300'">
                                                            <i class="fa-solid fa-star text-xs"></i>
                                                        </div>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Ceritakan Pengalamanmu</label>
                                            <textarea name="comment" rows="3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-[13px] shadow-sm text-gray-700" placeholder="Bagaimana isi bukunya? (Opsional)">{{ $existingReview?->comment ?? '' }}</textarea>
                                        </div>

                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg transition text-[12px] shadow-sm {{ $existingReview ? 'mb-2' : '' }}">
                                            {{ $existingReview ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                                        </button>
                                    </form>

                                    @if($existingReview)
                                    <form action="{{ route('reviews.destroy', $existingReview->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan Anda secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 font-bold py-2.5 rounded-lg transition text-[12px] shadow-sm">
                                            Hapus Ulasan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            @else
                                <div class="bg-gray-50/50 p-6 rounded-[1.25rem] border border-gray-100 text-center">
                                    <h4 class="font-bold text-gray-900 mb-2 text-[13px]"><i class="fa-solid fa-lock text-gray-400 mr-2"></i>  Ulasan Terkunci</h4>
                                    <p class="text-[12px] text-gray-500">Hanya Pembeli Terverifikasi yang bisa mengulas buku ini.</p>
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-50/50 p-6 rounded-[1.25rem] border border-gray-100 text-center">
                                <p class="text-[12px] text-gray-600 mb-4">Ingin mengulas buku ini?</p>
                                <a href="{{ route('login') }}" class="inline-block bg-white border border-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-lg hover:bg-gray-50 transition text-[12px] shadow-sm">
                                    Login Sekarang
                                </a>
                            </div>
                        @endauth
                    </div>

                    {{-- List Ulasan --}}
                    <div class="md:w-2/3">
                        @if($item->reviews->isEmpty())
                            <div class="h-full flex flex-col items-center justify-center text-center p-12 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                                <i class="fa-regular fa-comment-dots text-4xl text-slate-300 mb-4"></i>
                                <h4 class="font-bold text-slate-700 mb-1">Belum Ada Ulasan</h4>
                                <p class="text-sm font-medium text-slate-500">Jadilah yang pertama me-review buku ini!</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($item->reviews->sortByDesc('created_at') as $review)
                                    <div class="bg-white p-5 rounded-[1.25rem] border border-gray-100 shadow-sm flex gap-4">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center font-black text-sm shrink-0">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-1">
                                                <div class="flex items-center gap-2">
                                                    <h5 class="font-bold text-gray-900 text-[13px]">{{ $review->user->name }}</h5>
                                                    <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded flex items-center gap-1">
                                                        <i class="fa-solid fa-circle-check"></i> Pembeli
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 text-amber-400 text-[10px] mb-2">
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-[13px] text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Produk Terkait (Berdasarkan Kategori yang Sama) --}}
            @if($relatedBooks->count() > 0)
                <div class="mt-24">
                    <div class="flex justify-between items-end mb-10">
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Eksplorasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Buku Terkait</span></h2>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 lg:gap-8">
                        @foreach ($relatedBooks as $related)
                            <div class="group relative bg-white/80 backdrop-blur-sm border border-gray-100/80 rounded-[1.5rem] p-3 shadow-sm hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 hover:-translate-y-2">
                                <div class="relative aspect-[3/4.2] mb-4 overflow-hidden rounded-[1.25rem] bg-gray-50 border border-gray-100/50 group-hover:border-indigo-100 transition-colors">
                                    <img src="{{ asset('storage/' . $related->image) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                                </div>
                                <div class="px-1 pb-1">
                                    <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1 truncate">
                                        {{ $related->author->name ?? 'Penulis' }}
                                    </p>
                                    <h4 class="text-sm font-extrabold text-gray-900 line-clamp-2 leading-snug group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-600 transition-all h-10">
                                        {{ $related->name }}
                                    </h4>
                                    <div class="pt-2 mt-auto">
                                        <p class="text-[13px] font-black text-indigo-600">
                                            Rp{{ number_format($related->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('book.show', $related->slug) }}" class="absolute inset-0 z-10"></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function changeQuantity(button, delta) {
            const input = button.parentElement.querySelector('input[name="quantity"]');
            let val = parseInt(input.value) + delta;
            const max = parseInt(input.getAttribute('max')) || Infinity;
            if (val < 1) val = 1;
            if (val > max) val = max;
            input.value = val;
        }

        // Fungsi toggle favorite (jika Anda sudah punya API-nya)
        function toggleFavorite(btn, id) {
            // Logika AJAX Anda di sini
            console.log('Toggled favorite for book ID:', id);
        }

        function toggleFavorite(btn, itemId) {
    // Ambil Token CSRF dari meta tag
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Animasi klik sederhana
    btn.classList.add('scale-90');
    setTimeout(() => btn.classList.remove('scale-90'), 100);

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            item_id: itemId
        })
    })
    .then(response => {
        if (response.status === 401) {
            alert('Silakan login terlebih dahulu untuk menambah favorit.');
            window.location.href = '/login';
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'added') {
            // Ubah tampilan ke Aktif (Merah)
            btn.classList.remove('text-gray-500', 'hover:text-red-500');
            btn.classList.add('text-red-500');
            btn.querySelector('.fav-icon').classList.replace('fa-regular', 'fa-solid');
            btn.querySelector('.fav-text').innerText = 'Favorit';
        } else if (data.status === 'removed') {
            // Ubah tampilan ke Non-aktif (Abu-abu)
            btn.classList.remove('text-red-500');
            btn.classList.add('text-gray-500', 'hover:text-red-500');
            btn.querySelector('.fav-icon').classList.replace('fa-solid', 'fa-regular');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan, coba lagi nanti.');
    });
}

        (function () {
            const shareButton = document.getElementById('share-button');
            if (!shareButton) return;

            shareButton.addEventListener('click', async () => {
                const title = shareButton.dataset.title || document.title;
                const text = shareButton.dataset.text || '';
                const url = shareButton.dataset.url || window.location.href;

                if (navigator.share) {
                    try {
                        await navigator.share({ title, text, url });
                        return;
                    } catch (err) {
                        if (err && err.name !== 'AbortError') {
                            console.error('Share failed:', err);
                        }
                    }
                }

                try {
                    if (navigator.clipboard?.writeText) {
                        await navigator.clipboard.writeText(url);
                        alert('Link berhasil disalin.');
                    } else {
                        prompt('Salin link berikut:', url);
                    }
                } catch (err) {
                    console.error('Copy failed:', err);
                    prompt('Salin link berikut:', url);
                }
            });
        })();
    </script>
@endsection

@extends('customer.layouts.app')

@section('content')
    <div class="bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-6">

            {{-- Breadcrumbs --}}
            <nav class="flex mb-8 text-sm text-gray-500 overflow-x-auto whitespace-nowrap">
                <a href="/" class="hover:text-indigo-600">Home</a>
                <span class="mx-2 text-gray-300">></span>
                
                {{-- Mengambil kategori pertama sebagai path breadcrumb --}}
                @if($item->categories->isNotEmpty())
                    <a href="{{ route('category.show', $item->categories->first()->slug) }}"
                        class="hover:text-indigo-600">{{ $item->categories->first()->name }}</a>
                @else
                    <span class="text-gray-400">Kategori</span>
                @endif
                
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
                            class="block text-lg text-gray-500 hover:text-indigo-600 transition font-medium">
                            {{ $item->author->name ?? 'Penulis' }}
                        </a>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mt-1">{{ $item->name }}</h1>
                        
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

                    <div class="mt-6 mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </span>
                    </div>

<div class="flex space-x-6 mb-8">
    <button type="button" 
        id="fav-btn-{{ $item->id }}"
        onclick="toggleFavorite(this, {{ $item->id }})"
        class="flex items-center transition {{ $item->isFavorited() ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
        
        <i class="{{ $item->isFavorited() ? 'fa-solid fa-heart' : 'fa-regular fa-heart' }} mr-2 text-xl fav-icon"></i>
        <span class="fav-text">{{ $item->isFavorited()}} Favorit</span>
    </button>

    <button
        id="share-button"
        type="button"
        data-title="{{ $item->name }}"
        data-text="Cek buku ini: {{ $item->name }}"
        data-url="{{ url()->current() }}"
        class="flex items-center text-gray-500 hover:text-indigo-600 transition">
        <i class="fa-solid fa-share-nodes mr-2 text-xl"></i> Bagikan
    </button>
</div>

                    {{-- Deskripsi/Sinopsis --}}
                    <div class="border-t pt-8">
                        <h3 class="font-bold text-gray-900 mb-4 uppercase text-xs tracking-widest">Sinopsis</h3>
                        <div class="text-gray-600 leading-relaxed prose prose-indigo max-w-none">
                            {{ $item->description ?? 'Tidak ada deskripsi untuk buku ini.' }}
                        </div>
                    </div>

                    {{-- Spesifikasi Detail --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4 mt-10 p-6 bg-gray-50 rounded-2xl border border-gray-100">
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
                    <div class="sticky bottom-6 mt-12 md:relative md:bottom-0">
                        @auth
                            @if($item->stok > 0)
                                <form action="{{ route('cart.add', $item->id) }}" method="POST" class="flex gap-4 add-to-cart-form">
                                    @csrf
                                    <div class="flex items-center bg-white border-2 border-gray-200 rounded-xl overflow-hidden shrink-0">
                                        <button type="button" onclick="changeQuantity(this, -1)"
                                            class="px-4 py-2 hover:bg-gray-100 font-bold">-</button>
                                        <input type="number" name="quantity" value="1" max="{{ $item->stok }}"
                                            class="w-12 text-center border-none focus:ring-0 font-bold" min="1">
                                        <button type="button" onclick="changeQuantity(this, 1)"
                                            class="px-4 py-2 hover:bg-gray-100 font-bold">+</button>
                                    </div>
                                    <button type="submit"
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold text-lg shadow-xl shadow-indigo-100 transition-all flex items-center justify-center gap-3">
                                        <i class="fa-solid fa-cart-plus text-xl"></i>
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            @else
                                <div class="text-center w-full bg-red-50 border border-red-200 rounded-xl py-4 font-bold text-red-600">
                                    Maaf, Stok Sedang Habis
                                </div>
                            @endif
                        @else
                            <div class="flex gap-4">
                                <a href="{{ route('login') }}"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold text-lg shadow-xl shadow-indigo-100 transition-all flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-right-to-bracket text-xl"></i>
                                    Login untuk Membeli
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Ulasan Pembeli Section --}}
            <div class="mt-20 pt-16 border-t border-slate-100">
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
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                    <h4 class="font-bold text-slate-900 mb-4">{{ $existingReview ? 'Ubah Ulasan Anda' : 'Tulis Ulasan Anda' }}</h4>
                                    <form action="{{ route('reviews.store', $item->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-4">
                                            <label class="block text-[12px] font-bold text-slate-500 uppercase tracking-widest mb-2">Beri Bintang</label>
                                            <div class="flex gap-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" required {{ ($existingReview?->rating == $i) ? 'checked' : '' }}>
                                                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-300 peer-checked:bg-amber-50 peer-checked:border-amber-400 peer-checked:text-amber-400 hover:bg-slate-100 transition-all">
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-[12px] font-bold text-slate-500 uppercase tracking-widest mb-2">Ceritakan Pengalamanmu</label>
                                            <textarea name="comment" rows="3" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-[13px] font-medium" placeholder="Bagaimana isi bukunya? (Opsional)">{{ $existingReview?->comment ?? '' }}</textarea>
                                        </div>

                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition text-[13px] {{ $existingReview ? 'mb-2' : '' }}">
                                            {{ $existingReview ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                                        </button>
                                    </form>

                                    @if($existingReview)
                                    <form action="{{ route('reviews.destroy', $existingReview->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan Anda secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold py-2.5 rounded-xl transition text-[13px]">
                                            Hapus Ulasan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            @else
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-center">
                                    <h4 class="font-bold text-slate-900 mb-1 text-sm"><i class="fa-solid fa-lock text-slate-400 mr-2"></i>  Ulasan Terkunci</h4>
                                    <p class="text-[12px] text-slate-500 font-medium">Hanya Pembeli Terverifikasi yang bisa mengulas buku ini.</p>
                                </div>
                            @endif
                        @else
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-center">
                                <p class="text-[12px] text-slate-600 font-medium mb-3">Ingin mengulas buku ini?</p>
                                <a href="{{ route('login') }}" class="inline-block bg-white border border-slate-200 text-slate-700 font-bold py-2.5 px-6 rounded-xl hover:bg-slate-100 transition text-[12px]">
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
                            <div class="space-y-6">
                                @foreach($item->reviews->sortByDesc('created_at') as $review)
                                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex gap-5">
                                        <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center font-black text-lg shrink-0">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3 mb-1">
                                                <h5 class="font-bold text-slate-900 text-[14px]">{{ $review->user->name }}</h5>
                                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md flex items-center gap-1">
                                                    <i class="fa-solid fa-circle-check"></i> Pembeli
                                                </span>
                                            </div>
                                            <div class="flex text-amber-400 text-[12px] mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'text-slate-200' }}"></i>
                                                @endfor
                                                <span class="text-slate-400 ml-3">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-[14px] font-medium text-slate-600 leading-relaxed">{{ $review->comment }}</p>
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
                    <div class="flex justify-between items-end mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Buku Terkait</h2>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach ($relatedBooks as $related)
                            <a href="{{ route('book.show', $related->slug) }}" class="group">
                                <div class="bg-white rounded-xl overflow-hidden transition group-hover:translate-y-[-5px]">
                                    <div class="relative aspect-[3/4] mb-3">
                                        <img src="{{ asset('storage/' . $related->image) }}"
                                            class="w-full h-full object-cover rounded-xl shadow-sm border border-gray-100">
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                                            {{ $related->author->name ?? 'Penulis' }}
                                        </p>
                                        <h4 class="font-bold text-gray-900 truncate text-sm group-hover:text-indigo-600 transition">
                                            {{ $related->name }}
                                        </h4>
                                        <p class="text-indigo-600 font-black mt-1">
                                            Rp{{ number_format($related->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </a>
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

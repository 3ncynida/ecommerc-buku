<nav class="bg-white py-4 px-8 flex justify-between items-center shadow-sm sticky top-0 z-50">
    <div class="text-2xl font-bold text-indigo-600">Libris.</div>

    <div class="hidden md:flex space-x-8 font-medium">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Beranda</a>
        <a href="{{ route('category.index') }}" class="hover:text-indigo-600 transition">Kategori</a>
        <a href="#" class="hover:text-indigo-600 transition">Terlaris</a>
        <a href="#" class="hover:text-indigo-600 transition">Promo</a>
    </div>

    <div class="flex items-center space-x-5">
        <div class="relative flex-1 max-w-lg" x-data="{ open: false, search: '' }">
            {{-- Search Bar --}}
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="search-input"
                    class="w-full bg-gray-50 border border-gray-200 rounded-full py-2.5 pl-11 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm"
                    placeholder="Cari buku atau penulis..." autocomplete="off">
                {{-- Tombol Close (x) --}}
                <button id="clear-search"
                    class="absolute inset-y-0 right-0 pr-4 text-gray-400 hover:text-gray-600 hidden">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>

            {{-- Dropdown Hasil Pencarian --}}
            <div id="search-results"
                class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 hidden overflow-hidden">
                <div id="results-container" class="py-2 max-h-80 overflow-y-auto">
                </div>
            </div>
        </div>

        <div class="relative">
            <a href="{{ route('cart.index') }}" class="relative">
                <i class="fa-solid fa-cart-shopping text-xl text-gray-600"></i>
                @if(session('cart'))
                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] rounded-full px-1.5 animate-bounce">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        </div>

        @guest
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition">
                Masuk
            </a>
            <a href="{{ route('register') }}"
                class="bg-indigo-600 text-white px-5 py-2 rounded-full hover:bg-indigo-700 transition">
                Daftar
            </a>
        @endguest

        {{-- Logika Autentikasi --}}
        @auth
            <div class="relative" x-data="{ open: false }">
                {{-- Tombol Pemicu --}}
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center space-x-2 group focus:outline-none">
                    <div
                        class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-indigo-600 transition duration-300">
                        <i class="fa-solid fa-user text-gray-500 group-hover:text-white transition"></i>
                    </div>
                    <span class="hidden lg:block text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                    {{-- Icon Panah yang Berputar --}}
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown Card --}}
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-3 w-72 bg-white rounded-[25px] shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-50 py-4 z-50">

                    {{-- Info User --}}
                    <div class="px-6 py-4 flex items-center space-x-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-user text-2xl text-indigo-300"></i>
                        </div>
                        <div class="overflow-hidden text-left">
                            <h4 class="font-bold text-gray-900 truncate">{{ auth()->user()->name }}</h4>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <hr class="my-2 border-gray-50">

                    {{-- List Menu --}}
                    <div class="px-2 space-y-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-gray-50 group transition text-left">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600">Akun</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 group-hover:text-indigo-600"></i>
                        </a>
                        @if (auth()->user()->role === 'customer')
                            <a href="{{ route('orders.index') }}"
                                class="flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-gray-50 group transition text-left">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600">Transaksi</span>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 group-hover:text-indigo-600"></i>
                            </a>
                            <a href="{{ route('wishlist.index') }}"
                                class="flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-gray-50 group transition text-left">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600">Wishlist</span>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 group-hover:text-indigo-600"></i>
                            </a>
                        @endif
                    </div>

                    <hr class="my-2 border-gray-50">

                    {{-- Logout --}}
                    <div class="px-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-red-50 group transition text-left">
                                <div class="flex items-center space-x-3">
                                    <i class="fa-solid fa-right-from-bracket text-gray-400 group-hover:text-red-500"></i>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-500">Keluar
                                        Akun</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 group-hover:text-red-500"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</nav>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('search-input');
            const resultsDropdown = document.getElementById('search-results');
            const container = document.getElementById('results-container');
            const clearBtn = document.getElementById('clear-search');
            let debounceTimer;

            input.addEventListener('input', function () {
                const query = this.value;
                clearBtn.classList.toggle('hidden', query.length === 0);

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (query.length < 2) {
                        resultsDropdown.classList.add('hidden');
                        return;
                    }

                    fetch(`/api/search?q=${query}`)
                        .then(res => res.json())
                        .then(data => {
                            container.innerHTML = '';

                            if (data.items.length === 0 && data.authors.length === 0) {
                                container.innerHTML = '<p class="px-5 py-3 text-sm text-gray-500">Tidak ditemukan hasil.</p>';
                            } else {
                                // Render Buku
                                data.items.forEach(item => {
                                    container.innerHTML += `
                                <a href="/book/${item.slug}" class="flex items-center px-5 py-3 hover:bg-gray-50 transition">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400 mr-3 text-xs"></i>
                                    <span class="text-sm font-medium text-gray-700">${item.name}</span>
                                    <span class="ml-auto text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full font-bold">BUKU</span>
                                </a>`;
                                });
                                // Render Penulis
                                data.authors.forEach(author => {
                                    container.innerHTML += `
                                <a href="/author/${author.id}" class="flex items-center px-5 py-3 hover:bg-gray-50 transition">
                                    <i class="fa-solid fa-user text-gray-400 mr-3 text-xs"></i>
                                    <span class="text-sm font-medium text-gray-700">${author.name}</span>
                                    <span class="ml-auto text-[10px] bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full font-bold">PENULIS</span>
                                </a>`;
                                });
                            }
                            resultsDropdown.classList.remove('hidden');
                        });
                }, 300); // Tunggu 300ms setelah berhenti mengetik
            });

            // Sembunyikan dropdown saat klik di luar
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !resultsDropdown.contains(e.target)) {
                    resultsDropdown.classList.add('hidden');
                }
            });
        });
    </script>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Libris | Toko Buku Online' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans text-gray-900">

    <nav class="bg-white py-4 px-8 flex justify-between items-center shadow-sm sticky top-0 z-50">
        <a href="/" class="text-2xl font-bold text-indigo-600">Libris.</a>
        <div class="hidden md:flex space-x-8 font-medium text-gray-600">
            <a href="/" class="hover:text-indigo-600 transition">Beranda</a>
            <a href="#" class="hover:text-indigo-600 transition">Kategori</a>
            <a href="#" class="hover:text-indigo-600 transition">Terlaris</a>
        </div>
        <div class="flex items-center space-x-5">
            <button class="text-gray-600 hover:text-indigo-600"><i
                    class="fa-solid fa-magnifying-glass text-xl"></i></button>
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
            <a href="/admin/dashboard"
                class="bg-indigo-600 text-white px-5 py-2 rounded-full hover:bg-indigo-700 transition text-sm font-bold">Admin</a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white pt-16 pb-8 mt-20">
        <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 md:grid-cols-3 gap-12 mb-10">
            <div class="space-y-4">
                <h2 class="text-2xl font-bold italic">Libris.</h2>
                <p class="text-gray-400">Jendela dunia dalam genggaman Anda. Kami menyediakan koleksi literatur terbaik
                    untuk setiap pembaca.</p>
            </div>
            <div>
                <h4 class="font-bold mb-6">Tautan</h4>
                <ul class="space-y-4 text-gray-400">
                    <li><a href="#" class="hover:text-white">Kategori Buku</a></li>
                    <li><a href="#" class="hover:text-white">Syarat & Ketentuan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">Kontak</h4>
                <p class="text-gray-400">support@libris.com</p>
                <div class="flex space-x-4 mt-4 text-xl">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <p class="text-center text-gray-500 text-sm border-t border-gray-800 pt-8">© 2026 Libris E-commerce.</p>
    </footer>

</body>

</html>
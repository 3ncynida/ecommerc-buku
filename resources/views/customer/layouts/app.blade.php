<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Update CSP untuk mengizinkan Vite Lokal (Port 5173) --}}
    <meta http-equiv="Content-Security-Policy"
        content="script-src 'self' 'unsafe-inline' 'unsafe-eval' http://127.0.0.1:5173 https://app.sandbox.midtrans.com https://api.sandbox.midtrans.com https://snap-assets.al-pc-id-b.cdn.gtflabs.io; 
        connect-src 'self' http://127.0.0.1:5173 ws://127.0.0.1:5173 https://app.sandbox.midtrans.com https://api.sandbox.midtrans.com;">

    <title>{{ $title ?? 'Libris | Toko Buku Online' }}</title>

    {{-- Gaya agar dropdown tidak berkedip saat refresh --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans text-gray-900">

    @include('layouts.navigation')

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
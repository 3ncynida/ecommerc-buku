<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Libris | Portal Kurir' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" referrerpolicy="no-referrer" />

    {{-- Font Inter untuk kesan minimalis --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-[#FAFAFA] text-gray-900 antialiased selection:bg-black selection:text-white">

    {{-- HEADER MINIMALIS MOBILE-FIRST --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-2xl mx-auto px-4 h-16 flex items-center justify-between">

            {{-- Brand / Logo --}}
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-black text-white rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-cube text-sm"></i>
                </div>
                <div>
                    <h1 class="font-bold text-sm leading-none tracking-tight">Libris.</h1>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mt-0.5">Kurir</p>
                </div>
            </div>

            {{-- Profil & Logout --}}
            <div class="flex items-center gap-3">
                {{-- Nama user disembunyikan di layar sangat kecil agar tidak memotong logo --}}
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-gray-900">{{ auth()->user()->name }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex">
                    @csrf
                    <button type="submit"
                        class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-colors duration-200 active:scale-95"
                        title="Logout">
                        <i class="fa-solid fa-power-off text-sm"></i>
                    </button>
                </form>
            </div>

        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="w-full">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>

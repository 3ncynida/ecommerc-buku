<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Libris Courier' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" referrerpolicy="no-referrer" />
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-indigo-50 via-white to-white text-gray-900">
    <header class="bg-white/60 backdrop-blur border-b border-indigo-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-2">
                <span class="text-indigo-600 font-black text-xl">Libris Kurir</span>
                <span class="text-xs uppercase tracking-[0.4em] text-gray-400">operasional</span>
            </div>

            <nav class="flex flex-wrap gap-2 text-sm font-semibold text-gray-500 md:gap-4">
                <a href="{{ route('courier.dashboard', ['tab' => 'available']) }}"
                   class="px-4 py-2 rounded-full transition hover:bg-indigo-50 hover:text-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-500">
                   <i class="fa-solid fa-inbox mr-1"></i> Tersedia
                </a>
                <a href="{{ route('courier.dashboard', ['tab' => 'tasks']) }}"
                   class="px-4 py-2 rounded-full transition hover:bg-indigo-50 hover:text-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-500">
                   <i class="fa-solid fa-truck-fast mr-1"></i> Tugas Saya
                </a>
            </nav>

            <div class="flex items-center gap-3 justify-between text-sm">
                <div class="text-right hidden md:block">
                    <p class="font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded-full bg-rose-50 text-rose-600 font-semibold border border-rose-100 hover:bg-rose-100 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-rose-500">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-6 space-y-6">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>

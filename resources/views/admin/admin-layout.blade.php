<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libris Admin Workspace</title>
    <!-- Basic CSP -->
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' 'unsafe-eval' http://127.0.0.1:5173 http://localhost:5173 https://cdn.jsdelivr.net; connect-src 'self' http://127.0.0.1:5173 ws://127.0.0.1:5173;">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans overflow-hidden" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen w-full">
        <!-- Sidebar -->
        <aside class="bg-white border-r border-slate-200 flex-shrink-0 transition-all duration-300 relative z-20"
            :class="sidebarOpen ? 'w-72' : 'w-20'">
            
            <div class="h-16 flex items-center px-6 border-b border-slate-100">
                <div class="flex items-center gap-3 w-full overflow-hidden">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shrink-0 shadow-md shadow-indigo-600/20">
                        <i class="fa-solid fa-book-open text-sm"></i>
                    </div>
                    <span class="font-black text-lg text-slate-900 tracking-tight whitespace-nowrap transition-opacity duration-300" 
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Libris <span class="text-indigo-600">OS</span></span>
                </div>
            </div>

            <div class="p-4 h-[calc(100vh-4rem)] overflow-y-auto no-scrollbar pb-10">
                <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-3 px-3 mt-4 transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Analytics</div>
                <nav class="space-y-1.5 mb-8">
                    <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard.index') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Dashboard">
                        <i class="fa-solid fa-chart-pie w-5 text-center text-lg {{ request()->routeIs('admin.dashboard.index') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Laporan & Analitik">
                        <i class="fa-solid fa-chart-line w-5 text-center text-lg {{ request()->routeIs('admin.reports.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Laporan Keuangan</span>
                    </a>
                </nav>

                <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-3 px-3 transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Data Master</div>
                <nav class="space-y-1.5 mb-8">
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('categories.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Kategori">
                        <i class="fa-solid fa-folder-tree w-5 text-center text-lg {{ request()->routeIs('categories.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Kategori</span>
                    </a>
                    <a href="{{ route('authors.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('authors.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Penulis">
                        <i class="fa-solid fa-feather-pointed w-5 text-center text-lg {{ request()->routeIs('authors.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Penulis</span>
                    </a>
                    <a href="{{ route('items.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('items.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Katalog Buku">
                        <i class="fa-solid fa-book-bookmark w-5 text-center text-lg {{ request()->routeIs('items.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Katalog Buku</span>
                    </a>
                </nav>

                <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-3 px-3 transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Operasional</div>
                <nav class="space-y-1.5 mb-8">
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Pesanan">
                        <i class="fa-solid fa-cart-flatbed w-5 text-center text-lg {{ request()->routeIs('admin.orders.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Pesanan</span>
                    </a>
                    <a href="{{ route('couriers.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('couriers.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Kurir & Logistik">
                        <i class="fa-solid fa-truck-fast w-5 text-center text-lg {{ request()->routeIs('couriers.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Kurir & Logistik</span>
                    </a>
                    <a href="{{ route('stock-logs.index') }}" class="flex items-center gap-4 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('stock-logs.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}" title="Log Stok">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center text-lg {{ request()->routeIs('stock-logs.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="whitespace-nowrap font-semibold text-sm transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Log Stok</span>
                    </a>
                </nav>
            </div>
            
            <!-- Toggle Button Bottom -->
            <button @click="sidebarOpen = !sidebarOpen" class="absolute -right-3 top-20 w-6 h-6 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-colors shadow-sm z-50">
                <i class="fa-solid text-[10px]" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 md:px-10 flex-shrink-0 z-10 relative">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-black tracking-tight text-slate-800">@yield('title', 'Workspace')</h2>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="relative" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex items-center gap-3 hover:bg-slate-50 p-1.5 rounded-full pl-4 transition-colors border border-transparent hover:border-slate-200 focus:outline-none">
                            <span class="text-sm font-bold text-slate-700 hidden md:block">{{ auth()->user()->name ?? 'Administrator' }}</span>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=4f46e5&color=fff" class="w-8 h-8 rounded-full shadow-sm border border-slate-200">
                        </button>
                        
                        <div x-show="userMenu" x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 origin-top-right">
                            
                            <div class="px-5 py-3 border-b border-slate-100 mb-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Logged in as</p>
                                <p class="text-sm font-semibold text-slate-800 mt-1 truncate">{{ auth()->user()->email ?? 'admin@libris.com' }}</p>
                            </div>

                            <a href="{{ route('admin.profile.edit') }}" class="group flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <i class="fa-solid fa-user-gear w-4 text-slate-400 group-hover:text-indigo-500 transition"></i> Pengaturan Profil
                            </a>
                            <div class="h-px bg-slate-100 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group w-full flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-rose-400 group-hover:text-rose-600 transition"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#f8fafc] relative">
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4"
                    class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-[20px] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-sm"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-700 transition"><i class="fa-solid fa-xmark"></i></button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" 
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4"
                    class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-[20px] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bolt text-sm"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-700 transition"><i class="fa-solid fa-xmark"></i></button>
                </div>
                @endif

                <!-- Yield Content -->
                <div class="animate-fade-in-up">
                    @yield('content')
                </div>
                
            </div>
            
            <style>
                .animate-fade-in-up {
                    animation: fadeInUp 0.4s ease-out;
                }
                @keyframes fadeInUp {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </main>
    </div>

</body>
</html>

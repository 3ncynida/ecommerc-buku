<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libris Admin Workspace</title>
    <!-- Basic CSP -->
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' 'unsafe-eval' http://127.0.0.1:5173 http://localhost:5173 https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; connect-src 'self' http://127.0.0.1:5173 ws://127.0.0.1:5173;">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Style for NProgress Minimal Loading */
        #nprogress .bar { background: #4f46e5 !important; height: 3px !important; }
        #nprogress .peg { box-shadow: 0 0 10px #4f46e5, 0 0 5px #4f46e5 !important; }
        #nprogress .spinner-icon { border-top-color: #4f46e5 !important; border-left-color: #4f46e5 !important; }
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
                
                @php
                    use App\Models\Order;
                    use Illuminate\Support\Facades\Cache;

                    $clearedAt = Cache::get('admin_cleared_notifications_at_' . auth()->id(), now()->subHours(12));
                    $queryTime = max(now()->subHours(12), $clearedAt);

                    $adminNotifications = Order::where('created_at', '>=', $queryTime)
                        ->orderByDesc('created_at')
                        ->take(5)
                        ->get();
                    $notificationCount = $adminNotifications->count();
                @endphp

                <div class="flex items-center gap-6">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="relative inline-flex items-center justify-center w-11 h-11 rounded-full border border-slate-200/70 hover:border-indigo-200 bg-white shadow-sm hover:shadow-indigo-100/50 text-slate-500 hover:text-indigo-600 transition-all duration-300 focus:outline-none overflow-visible group">
                            <i class="fa-solid fa-bell group-hover:rotate-[15deg] transition-transform duration-300"></i>
                            @if($notificationCount)
                                <div class="absolute top-0 right-0 -translate-y-1 translate-x-1 flex items-center justify-center">
                                    <span class="absolute w-full h-full rounded-full bg-rose-400 opacity-50 animate-ping"></span>
                                    <span class="relative inline-flex items-center justify-center w-5 h-5 rounded-full bg-gradient-to-br from-rose-500 to-pink-500 border-2 border-white text-[10px] text-white font-black shadow-sm z-10">
                                        {{ $notificationCount }}
                                    </span>
                                </div>
                            @endif
                        </button>
                        <div x-show="open" x-cloak
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-3"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition duration-200 ease-in"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-3"
                            class="absolute right-0 mt-4 w-[24rem] bg-white/95 backdrop-blur-2xl rounded-3xl shadow-[0_20px_40px_-15px_rgba(0,0,0,0.15)] border border-white/60 z-[100] overflow-hidden flex flex-col">
                            
                            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50/50 to-white/50 border-b border-indigo-50/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-inner">
                                        <i class="fa-solid fa-bell text-sm"></i>
                                    </div>
                                    <p class="text-xs font-black tracking-widest text-slate-800 uppercase">Notifikasi</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($notificationCount > 0)
                                        <span class="px-3 py-1 bg-rose-100/80 text-rose-600 text-[10px] font-black rounded-lg uppercase tracking-widest border border-rose-200/50 shadow-sm">{{ $notificationCount }} Baru</span>
                                        <form action="{{ route('admin.notifications.clear') }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-rose-100/80 text-rose-400 hover:text-rose-600 border border-transparent hover:border-rose-200/50 transition-colors" title="Bersihkan Notifikasi">
                                                <i class="fa-solid fa-check-double text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black rounded-lg uppercase tracking-widest border border-slate-200 shadow-sm">Kosong</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="max-h-[320px] overflow-y-auto no-scrollbar bg-slate-50/30">
                                @forelse($adminNotifications as $notification)
                                    <a href="{{ route('admin.orders.show', $notification->id) }}"
                                        class="group px-6 py-4 flex gap-4 hover:bg-white transition-all duration-300 border-b border-slate-100/60 last:border-b-0 relative overflow-hidden">
                                        <div class="absolute left-0 top-0 h-full w-1 bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        
                                        <div class="w-10 h-10 rounded-2xl bg-white border border-slate-100 group-hover:border-indigo-100 group-hover:bg-indigo-50 text-slate-400 group-hover:text-indigo-600 flex items-center justify-center shrink-0 transition-all shadow-sm group-hover:shadow group-hover:-translate-y-0.5">
                                            <i class="fa-solid fa-cart-shopping text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <p class="text-sm font-bold text-slate-800 truncate group-hover:text-indigo-700 transition-colors">#{{ $notification->order_number }}</p>
                                                <span class="text-[10px] font-bold text-slate-400 shrink-0 flex items-center gap-1 group-hover:text-indigo-400 transition-colors"><i class="fa-regular fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-1 flex items-center justify-between">
                                                <p class="text-xs font-medium text-slate-500 w-full truncate pr-4">Ada pesanan baru masuk nih!</p>
                                            </div>
                                            <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-emerald-50 border border-emerald-100/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span class="text-[10px] font-black text-emerald-700">Rp{{ number_format($notification->total_price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-6 py-10 flex flex-col items-center justify-center text-center">
                                        <div class="w-16 h-16 bg-white border border-slate-100 shadow-sm rounded-full flex items-center justify-center mb-4 text-slate-300">
                                            <i class="fa-regular fa-bell-slash text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600">Belum ada pesanan baru</p>
                                        <p class="text-xs font-medium text-slate-400 mt-1 max-w-[200px]">Semua notifikasi pesanan pelanggan akan muncul di sini.</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            <div class="px-2 py-2 bg-slate-50/80 border-t border-slate-100/80 backdrop-blur-md">
                                <a href="{{ route('admin.orders.index') }}" class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-indigo-600 hover:text-white hover:bg-indigo-600 flex items-center justify-center gap-2 transition-all duration-300 group shadow-sm bg-white border border-slate-100 hover:border-indigo-600 relative overflow-hidden">
                                    <span class="relative z-10 flex items-center gap-2">Lihat Semua Pesanan <i class="fa-solid fa-arrow-right-long group-hover:translate-x-1 transition-transform"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="relative" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex items-center gap-3 hover:bg-slate-50 p-1.5 rounded-full pl-4 transition-colors border border-transparent hover:border-slate-200 focus:outline-none">
                            <span class="text-sm font-bold text-slate-700 hidden md:block">{{ auth()->user()->name ?? 'Administrator' }}</span>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=4f46e5&color=fff" class="w-8 h-8 rounded-full shadow-sm border border-slate-200">
                        </button>
                        
                        <div x-show="userMenu" x-cloak
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-3"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition duration-200 ease-in"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-3"
                            class="absolute right-0 mt-4 w-64 bg-white/95 backdrop-blur-2xl rounded-3xl shadow-[0_20px_40px_-15px_rgba(0,0,0,0.15)] border border-white/60 z-[100] overflow-hidden origin-top-right flex flex-col">
                            
                            <div class="bg-gradient-to-r from-indigo-50/50 to-white/50 border-b border-indigo-50/80 px-6 py-5">
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1.5">Logged in as</p>
                                <p class="text-sm font-bold text-slate-800 truncate" title="{{ auth()->user()->email ?? 'admin@libris.com' }}">{{ auth()->user()->email ?? 'admin@libris.com' }}</p>
                            </div>

                            <div class="p-2 space-y-1 bg-slate-50/30">
                                <a href="{{ route('admin.profile.edit') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-white hover:text-indigo-700 hover:shadow-sm border border-transparent hover:border-slate-100 transition-all duration-300">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-indigo-50 text-slate-400 group-hover:text-indigo-500 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-user-gear"></i>
                                    </div>
                                    Pengaturan Profil
                                </a>
                                
                                <div class="h-px bg-slate-100/80 my-2 mx-4"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full group flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 border border-transparent hover:border-rose-100 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-lg bg-rose-50/50 group-hover:bg-rose-100 text-rose-400 group-hover:text-rose-600 flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        </div>
                                        Keluar
                                    </button>
                                </form>
                            </div>
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

    <!-- NProgress Minimal Loading Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <style>
        #nprogress .bar { height: 4px !important; z-index: 999999 !important; }
        #nprogress .spinner { z-index: 999999 !important; top: 18px !important; right: 18px !important; }
        #nprogress .spinner-icon { width: 22px !important; height: 22px !important; border-width: 3px !important; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            NProgress.configure({ showSpinner: true, trickleSpeed: 100, minimum: 0.1 });

            // start loading when navigating away
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || this.target === '_blank' || e.defaultPrevented) {
                        return;
                    }
                    NProgress.start();
                });
            });

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!e.defaultPrevented) {
                        NProgress.start();
                    }
                });
            });

            window.addEventListener('beforeunload', function () {
                NProgress.start();
            });

            // Stop loading when page is fully loaded
            window.addEventListener('load', function () {
                NProgress.done();
            });
        });
    </script>
</body>
</html>

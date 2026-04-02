@extends('admin.admin-layout')
@section('title', 'Pengaturan Profil Admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-6xl mx-auto">
        
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-black tracking-widest uppercase mb-3 border border-indigo-100">
                    <i class="fa-solid fa-user-astronaut"></i> Workspace
                </span>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Profil & Keamanan</h1>
                <p class="text-slate-500 mt-2 text-sm md:text-base max-w-xl">Kelola informasi personal, preferensi keamanan, dan kredensial akses dashboard Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Left column - Profile Form --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- Personal Information Card --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/70 overflow-hidden relative group transition-all hover:shadow-md">
                    {{-- Banner --}}
                    <div class="h-40 bg-gradient-to-br from-violet-600 via-indigo-600 to-blue-600 relative overflow-hidden">
                        {{-- Decorative background elements --}}
                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/20 rounded-full blur-3xl mix-blend-overlay"></div>
                        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-black/10 rounded-full blur-3xl mix-blend-overlay"></div>
                    </div>
                    
                    <div class="px-8 sm:px-10 pb-10">
                        {{-- Avatar Setup --}}
                        <div class="relative -mt-16 mb-8 flex flex-col sm:flex-row items-center sm:items-end gap-5 text-center sm:text-left">
                            <div class="relative inline-block group-hover:scale-105 transition-transform duration-500">
                                <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-[2rem] p-1.5 bg-white shadow-xl shadow-indigo-100/50">
                                    <div class="w-full h-full bg-slate-100 rounded-[1.75rem] overflow-hidden relative">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff&size=200&bold=true" alt="Profile" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="absolute bottom-1 right-1 w-6 h-6 sm:w-7 sm:h-7 bg-emerald-500 border-4 border-white rounded-full"></div>
                            </div>
                            <div class="pb-3">
                                <h2 class="text-2xl font-black text-slate-800">{{ auth()->user()->name }}</h2>
                                <p class="text-indigo-600 font-semibold text-sm flex items-center justify-center sm:justify-start gap-1.5 mt-1 bg-indigo-50 px-2 py-1 rounded-md inline-flex">
                                    <i class="fa-solid fa-shield-check"></i> Super Administrator
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Name Input --}}
                                <div class="space-y-2 group/input">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-500 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal">
                                    </div>
                                </div>

                                {{-- Email Input --}}
                                <div class="space-y-2 group/input">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                        <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/15 focus:border-indigo-500 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal">
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 mt-6 border-t border-slate-100 gap-4">
                                <p class="text-[13px] text-slate-500 font-medium flex items-center gap-2">
                                    <i class="fa-solid fa-circle-info text-blue-500"></i> Pastikan email Anda aktif untuk notifikasi sistem.
                                </p>
                                <button type="submit"
                                    class="w-full sm:w-auto relative inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white transition-all duration-300 bg-indigo-600 rounded-2xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Security Section --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/70 overflow-hidden relative group transition-all hover:shadow-md">
                    <div class="px-8 sm:px-10 py-10">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                                    <i class="fa-solid fa-fingerprint text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Kemanan Akun</h3>
                                    <p class="text-sm text-slate-500 mt-1">Perbarui kredensial password Anda.</p>
                                </div>
                            </div>
                        </div>

                        <form action="#" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 md:col-span-2 group/input">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Password Saat Ini</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-slate-900 transition-colors">
                                            <i class="fa-solid fa-lock"></i>
                                        </div>
                                        <input type="password" name="current_password" placeholder="••••••••" required
                                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-slate-900/10 focus:border-slate-900 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal">
                                    </div>
                                </div>

                                <div class="space-y-2 group/input">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Password Baru</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-slate-900 transition-colors">
                                            <i class="fa-solid fa-key"></i>
                                        </div>
                                        <input type="password" name="password" placeholder="Minimal 8 karakter" required
                                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-slate-900/10 focus:border-slate-900 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal">
                                    </div>
                                </div>

                                <div class="space-y-2 group/input">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-slate-900 transition-colors">
                                            <i class="fa-solid fa-check-double"></i>
                                        </div>
                                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru" required
                                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-slate-900/10 focus:border-slate-900 transition-all outline-none text-slate-700 font-bold placeholder:text-slate-400 placeholder:font-normal">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6 mt-6 border-t border-slate-100">
                                <button type="submit"
                                    class="w-full sm:w-auto px-8 py-3.5 text-sm font-bold text-white transition-all duration-300 bg-slate-900 rounded-2xl hover:bg-black hover:shadow-lg hover:shadow-slate-500/30 focus:outline-none focus:ring-4 focus:ring-slate-500/20 active:scale-[0.98]">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            {{-- Right column - Stats / Extra Actions --}}
            <div class="lg:col-span-4 space-y-8">
                
                {{-- Quick Info Widget --}}
                <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-black rounded-[2rem] p-8 text-white shadow-xl shadow-indigo-900/20 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                        <i class="fa-solid fa-server text-9xl"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-sm border border-white/10">
                            <i class="fa-solid fa-bolt"></i> Status Sistem
                        </div>
                        
                        <h3 class="text-2xl font-black mb-2 tracking-tight">Libris Admin</h3>
                        <p class="text-indigo-200 text-sm mb-8 leading-relaxed">Sistem toko buku online berjalan optimal. Tidak ada peringatan keamanan saat ini.</p>
                        
                        <div class="space-y-3">
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/10 backdrop-blur-md">
                                <p class="text-indigo-300/80 text-[10px] font-black uppercase tracking-widest mb-1.5">Akses Terakhir</p>
                                <p class="font-bold flex items-center gap-2 text-sm">
                                    <i class="fa-regular fa-clock text-indigo-400"></i> Hari ini, {{ date('H:i') }}
                                </p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/10 backdrop-blur-md">
                                <p class="text-indigo-300/80 text-[10px] font-black uppercase tracking-widest mb-1.5">Peran Akses</p>
                                <p class="font-bold flex items-center gap-2 text-sm text-emerald-400">
                                    <i class="fa-solid fa-key"></i> Hak Akses Penuh (Root)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-rose-200/50 overflow-hidden relative group hover:shadow-md transition-all">
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 border border-rose-100 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-power-off text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Akhiri Sesi</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1">Keluar dari dashboard</p>
                            </div>
                        </div>

                        <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                            Pastikan Anda telah menyimpan semua perubahan sebelum keluar dari dashboard admin.
                        </p>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-6 py-3.5 text-sm font-bold text-rose-600 transition-all duration-300 bg-rose-50 border border-rose-100 rounded-2xl hover:bg-rose-600 hover:text-white hover:border-rose-600 focus:outline-none focus:ring-4 focus:ring-rose-500/20 active:scale-[0.98] flex items-center justify-center gap-2">
                                Logout Sekarang
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Styling for smoother input autofill background */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active{
        -webkit-box-shadow: 0 0 0 30px #f8fafc inset !important;
        -webkit-text-fill-color: #334155 !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>
@endsection
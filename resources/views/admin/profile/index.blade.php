@extends('admin.admin-layout')
@section('title', 'Pengaturan Profil Admin')

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header Section --}}
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Profil</h1>
                <p class="text-slate-500 mt-1">Kelola kredensial dan preferensi keamanan akun admin Anda.</p>
            </div>
            <div class="hidden md:block">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-widest">
                    <i class="fa-solid fa-shield-check mr-2"></i> Mode Administrator
                </span>
            </div>
        </div>

        <div class="space-y-8">
            
            {{-- Personal Information Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden transition-all hover:shadow-md">
                <div class="h-32 bg-gradient-to-r from-indigo-600 to-violet-600 relative">
                    <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
                </div>
                
                <div class="px-8 pb-10">
                    <div class="relative -mt-16 mb-8 flex items-end gap-6">
                        <div class="relative group">
                            <div class="w-32 h-32 bg-white rounded-3xl p-1.5 shadow-xl transition-transform group-hover:scale-[1.02]">
                                <div class="w-full h-full bg-slate-100 rounded-[22px] flex items-center justify-center overflow-hidden border border-slate-100">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=128" alt="Profile" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                        <div class="pb-2">
                            <h2 class="text-xl font-bold text-slate-900">{{ auth()->user()->name }}</h2>
                            <p class="text-sm text-slate-500 font-medium">Administrator Utama</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </span>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fa-solid fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium">
                            </div>
                        </div>

                        <div class="md:col-span-2 flex items-center justify-between pt-4 border-t border-slate-50 mt-4">
                            <p class="text-xs text-slate-400 font-medium italic">* Perubahan email akan memerlukan verifikasi ulang.</p>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-10 py-3.5 rounded-2xl font-bold hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-200 transition-all duration-300 transform active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Security Section --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 p-8 transition-all hover:shadow-md">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 shadow-sm shadow-rose-100">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Keamanan & Password</h3>
                        <p class="text-sm text-slate-500 font-medium tracking-tight uppercase tracking-wider opacity-70">Pembaruan berkala disarankan</p>
                    </div>
                </div>

                <form action="#" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="••••••••"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Baru</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Konfirmasi</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="bg-slate-900 text-white px-10 py-3.5 rounded-2xl font-bold hover:bg-black hover:shadow-xl hover:shadow-slate-200 transition-all duration-300 transform active:scale-95 flex items-center gap-3">
                            <i class="fa-solid fa-key text-slate-400 text-xs"></i>
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Danger Zone / Session --}}
            <div class="bg-rose-50/50 rounded-3xl border border-rose-100 p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-rose-500 shadow-sm border border-rose-100">
                        <i class="fa-solid fa-power-off"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="font-bold text-slate-900">Akhiri Sesi</h3>
                        <p class="text-sm text-slate-500 font-medium">Keluar dari dashboard admin dengan aman.</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full md:w-56 bg-white text-rose-600 border border-rose-200 px-6 py-3.5 rounded-2xl font-bold hover:bg-rose-600 hover:text-white hover:shadow-lg hover:shadow-rose-100 transition-all duration-300 flex items-center justify-center gap-3 group">
                        <span>Logout Sekarang</span>
                        <i class="fa-solid fa-arrow-right-from-bracket transition-transform group-hover:translate-x-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
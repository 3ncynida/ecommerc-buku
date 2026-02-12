@extends('admin.admin-layout')
@section('title', 'Pengaturan Profil Admin')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10 px-8">
        <div class="max-w-4xl mx-auto">

            {{-- Header & Breadcrumb --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pengaturan Profil</h1>
                <p class="text-gray-500">Kelola informasi akun dan keamanan panel admin Anda</p>
            </div>

            <div class="grid grid-cols-1 gap-8">

                {{-- Kartu Informasi Utama --}}
                <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-32 bg-indigo-600"></div> {{-- Banner Kecil --}}
                    <div class="px-10 pb-10">
                        <div class="relative -mt-16 mb-6">
                            <div class="w-32 h-32 bg-white rounded-[30px] p-2 shadow-lg inline-block">
                                <div
                                    class="w-full h-full bg-gray-100 rounded-[22px] flex items-center justify-center overflow-hidden">
                                    <i class="fa-solid fa-user-shield text-5xl text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat
                                    Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <div class="md:col-span-2 flex justify-end">
                                <button type="submit"
                                    class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kartu Keamanan (Password) --}}
                <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-lock text-red-500"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Keamanan Akun</h3>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-tighter">Kelola Password Anda</p>
                        </div>
                    </div>

                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <input type="password" name="current_password" placeholder="Password Saat Ini"
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 transition">

                            <input type="password" name="password" placeholder="Password Baru"
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 transition">

                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 transition">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-gray-900 text-white px-8 py-3 rounded-2xl font-bold hover:bg-black transition shadow-lg">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>

                <div class="max-w-7xl mx-auto px-8 mt-12 mb-20">
                    <div
                        class="bg-white p-6 rounded-[30px] border border-gray-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid fa-right-from-bracket text-red-500 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Sesi Akun</h3>
                                <p class="text-sm text-gray-500">Keluar dari akun Anda dengan aman</p>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full md:w-64 bg-red-500 text-white px-8 py-4 rounded-2xl font-bold hover:bg-red-600 hover:shadow-lg hover:shadow-red-100 transition-all duration-300 flex items-center justify-center gap-2">
                                <span>Keluar dari Akun</span>
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
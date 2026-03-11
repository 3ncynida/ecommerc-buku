<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Libris</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#e0f2fe] via-[#f8fafc] to-[#ffedd5] p-4 md:p-8">
        
        {{-- Main Card --}}
        <div class="bg-white rounded-[40px] shadow-2xl shadow-blue-900/10 flex flex-col md:flex-row w-full max-w-5xl overflow-hidden min-h-[600px]">
            
            {{-- Bagian Kiri (Branding & Ilustrasi) --}}
            <div class="hidden md:flex flex-col items-center justify-between w-1/2 p-12 bg-white relative">
                <div class="flex items-center gap-3 w-full justify-center mt-4">
                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">L</div>
                    <span class="text-2xl font-black text-slate-800 tracking-tight">Libris.com</span>
                </div>

                <div class="w-full max-w-sm mt-8">
                    <img src="https://illustrations.popsy.co/blue/student-going-to-school.svg" alt="Ilustrasi Mendaftar" class="w-full h-auto drop-shadow-sm">
                </div>

                <div class="text-center w-full mb-4">
                    <div class="flex justify-center gap-5 mb-5 text-slate-800 text-lg">
                        <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="hover:text-indigo-600 transition-colors"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                    <a href="mailto:customercare@libris.id" class="text-[11px] text-slate-500 font-bold hover:text-indigo-600 underline decoration-slate-300 underline-offset-4 mb-1 block">customercare@libris.id</a>
                    <p class="text-[11px] text-slate-400 font-medium">&copy; {{ date('Y') }} PT Libris Media</p>
                </div>
            </div>

            {{-- Bagian Kanan (Form Daftar) --}}
            <div class="w-full md:w-1/2 p-10 md:p-14 flex flex-col justify-center bg-white z-10">
                <div class="text-center mb-8">
                    <h2 class="text-[22px] font-extrabold text-slate-900">Daftar Akun Baru</h2>
                    <p class="text-slate-500 text-[12px] font-medium mt-1">Bergabung dengan komunitas Libris sekarang</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" 
                            class="w-full px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm placeholder:text-slate-400 font-medium" 
                            placeholder="Nama Lengkap" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-500" />
                    </div>

                    <div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm placeholder:text-slate-400 font-medium" 
                            placeholder="Email" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-500" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Kolom Password --}}
                        <div class="flex flex-col">
                            <input id="password" type="password" name="password" 
                                class="w-full px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm placeholder:text-slate-400 font-medium" 
                                placeholder="Kata Sandi" required />
                            
                            {{-- Info Syarat Password --}}
                            <div class="mt-3 ml-1 space-y-1.5">
                                <div class="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                    <i class="fa-solid fa-circle-check mr-2 text-indigo-500/50"></i>
                                    Min. 8 Karakter
                                </div>
                                <div class="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                    <i class="fa-solid fa-circle-check mr-2 text-indigo-500/50"></i>
                                    Huruf Besar & Kecil
                                </div>
                                <div class="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                    <i class="fa-solid fa-circle-check mr-2 text-indigo-500/50"></i>
                                    Angka & Simbol
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-500" />
                        </div>

                        {{-- Kolom Konfirmasi Password --}}
                        <div>
                            <input id="password_confirmation" type="password" name="password_confirmation" 
                                class="w-full px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm placeholder:text-slate-400 font-medium" 
                                placeholder="Konfirmasi Sandi" required />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3.5 rounded-2xl hover:bg-indigo-700 transition-all duration-300 shadow-lg shadow-indigo-200 mt-2">
                        Daftar Sekarang
                    </button>

                    <div class="mt-8 text-center pt-2">
                        <p class="text-[12px] font-medium text-slate-500">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline underline-offset-2">Masuk</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
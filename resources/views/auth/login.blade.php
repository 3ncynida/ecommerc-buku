<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Libris</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#e0f2fe] via-[#f8fafc] to-[#ffedd5] p-4 md:p-8">
        
        {{-- Main Card --}}
        <div class="bg-white rounded-[40px] shadow-2xl shadow-blue-900/10 flex flex-col md:flex-row w-full max-w-5xl overflow-hidden min-h-[600px]">
            
            {{-- Bagian Kiri (Branding & Ilustrasi) --}}
            <div class="hidden md:flex flex-col items-center justify-between w-1/2 p-12 bg-white relative">
                {{-- Logo --}}
                <div class="flex items-center gap-3 w-full justify-center mt-4">
                    <x-application-logo class="w-10 h-10 text-indigo-600 drop-shadow-md" />
                    <span class="text-2xl font-black text-slate-800 tracking-tight">Libris.com</span>
                </div>

                {{-- Ilustrasi (Menggunakan placeholder unDraw) --}}
                <div class="w-full max-w-sm mt-8">
                    <img src="https://illustrations.popsy.co/blue/student-going-to-school.svg" alt="Ilustrasi Membaca" class="w-full h-auto drop-shadow-sm">
                </div>

                {{-- Footer & Social Media --}}
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

            {{-- Bagian Kanan (Form Login) --}}
            <div class="w-full md:w-1/2 p-10 md:p-16 flex flex-col justify-center bg-white z-10">
                <div class="text-center mb-10">
                    <h2 class="text-[22px] font-extrabold text-slate-900">Masuk Akun Libris</h2>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm placeholder:text-slate-400 font-medium" 
                            placeholder="Email" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-500" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <input id="password" type="password" name="password" 
                            class="w-full px-5 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm placeholder:text-slate-400 font-medium" 
                            placeholder="Kata Sandi" required />
                        
                        <div class="flex justify-end mt-3">
                            @if (Route::has('password.request'))
                                <a class="text-[11px] font-bold text-slate-600 hover:text-indigo-600 transition-colors" href="{{ route('password.request') }}">
                                    Lupa Kata Sandi
                                </a>
                            @endif
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-500" />
                    </div>

                    {{-- Tombol Masuk --}}
                    <button type="submit" class="w-full bg-slate-200 text-slate-500 font-bold py-3.5 rounded-2xl hover:bg-indigo-600 hover:text-white transition-all duration-300 mt-2">
                        Masuk
                    </button>

                    <div class="mt-5 flex items-center gap-3">
                        <span class="flex-1 h-px bg-slate-200"></span>
                        <span class="text-[11px] text-slate-400 uppercase tracking-widest">atau</span>
                        <span class="flex-1 h-px bg-slate-200"></span>
                    </div>

                    <a href="{{ route('login.google') }}" class="mt-4 inline-flex items-center justify-center gap-3 w-full border border-slate-200 rounded-2xl py-3.5 font-bold text-slate-600 hover:border-indigo-500 hover:text-indigo-600 transition-all">
                        <i class="fa-brands fa-google text-lg"></i>
                        Masuk dengan Google
                    </a>
                    <p class="text-[11px] text-slate-400 mt-2 text-center">Akun baru otomatis dibuat dengan kata sandi acak (dapat diubah nanti).</p>

                    {{-- Daftar Link --}}
                    <div class="mt-8 text-center pt-4">
                        <p class="text-[12px] font-medium text-slate-500">Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline underline-offset-2">Daftar</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

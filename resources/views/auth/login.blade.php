<x-guest-layout>
    <div class="text-center mb-10">
        <h2 class="text-2xl font-extrabold text-gray-900">Masuk Akun Libris</h2>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <input id="email" type="email" name="email" :value="old('email')" 
                class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition" 
                placeholder="Email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <input id="password" type="password" name="password" 
                class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition" 
                placeholder="Kata Sandi" required />
            
            <div class="flex justify-end mt-2">
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-gray-400 hover:text-indigo-600" href="{{ route('password.request') }}">
                        Lupa Kata Sandi?
                    </a>
                @endif
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-gray-200 text-gray-500 font-bold py-4 rounded-2xl hover:bg-indigo-600 hover:text-white transition duration-300">
            Masuk
        </button>

        <div class="text-center py-4">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Atau</span>
        </div>

        {{-- Social Login Button (image_b1eb82.png) --}}
        <button type="button" class="w-full border border-gray-200 text-gray-700 font-bold py-3 rounded-2xl hover:bg-gray-50 transition flex items-center justify-center gap-3">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5" alt="Google">
            <span class="text-sm">Masuk dengan Google</span>
        </button>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Belum punya akun? 
                <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Daftar</a>
            </p>
        </div>
    </form>
</x-guest-layout>
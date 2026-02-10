<x-guest-layout>
    <div class="text-center mb-10">
        <h2 class="text-2xl font-extrabold text-gray-900">Daftar Akun Baru</h2>
        <p class="text-gray-500 text-sm mt-2">Bergabung dengan komunitas Libris sekarang</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <input id="name" type="text" name="name" :value="old('name')" 
            class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition" 
            placeholder="Nama Lengkap" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />

        <input id="email" type="email" name="email" :value="old('email')" 
            class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition" 
            placeholder="Email" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        <input id="password" type="password" name="password" 
            class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition" 
            placeholder="Kata Sandi" required />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />

        <input id="password_confirmation" type="password" name="password_confirmation" 
            class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition" 
            placeholder="Konfirmasi Kata Sandi" required />

        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 mt-4">
            Daftar Sekarang
        </button>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Masuk</a>
            </p>
        </div>
    </form>
</x-guest-layout>
@extends('admin.admin-layout')
@section('title', 'Tambah Author Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('authors.index') }}" class="text-gray-500 hover:text-emerald-600 flex items-center text-sm transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Author
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-xl">Tambah Author</h3>
            <p class="text-sm text-gray-500">Daftarkan author atau penyedia item baru di sini.</p>
        </div>

        <form action="{{ route('authors.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Author</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" name="name" id="name" 
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama author..." 
                        value="{{ old('name') }}" required>
                </div>
                
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-50 mt-6">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-emerald-700 transition shadow-md shadow-emerald-100">
                    <i class="fa-solid fa-check mr-2"></i> Daftarkan Author
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
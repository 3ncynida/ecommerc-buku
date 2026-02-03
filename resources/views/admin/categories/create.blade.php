@extends('admin.admin-layout')
@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center text-sm transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Kategori
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-xl">Tambah Kategori</h3>
            <p class="text-sm text-gray-500">Masukkan nama kategori baru untuk mengelompokkan item.</p>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" id="name" 
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Elektronik, Pakaian, Jasa..." 
                    value="{{ old('name') }}" required>
                
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('admin.admin-layout')

@section('content')
<div class="bg-gray-50 min-h-screen py-10 px-8">
    <div class="max-w-2xl mx-auto">
        
        {{-- Header & Kembali --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('categories.index') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-gray-100 shadow-sm hover:text-indigo-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
                <p class="text-sm text-gray-400 font-medium">Ubah informasi kategori buku</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-10">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="Contoh: Manga, Novel, Edukasi" required>
                        @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('categories.index') }}" class="px-8 py-3 rounded-2xl font-bold text-gray-400 hover:text-gray-600 transition">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                            Perbarui Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
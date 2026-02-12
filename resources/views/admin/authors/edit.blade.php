@extends('admin.admin-layout')

@section('content')
<div class="bg-gray-50 min-h-screen py-10 px-8">
    <div class="max-w-3xl mx-auto">
        
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('authors.index') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-gray-100 shadow-sm hover:text-indigo-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Penulis</h1>
                <p class="text-sm text-gray-400 font-medium">Manajemen informasi profil penulis</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
            <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            
            <form action="{{ route('authors.update', $author->id) }}" method="POST" class="px-10 pb-10">
                @csrf
                @method('PUT')

                <div class="relative -mt-12 mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-white rounded-[25px] p-1.5 shadow-lg">
                        <div class="w-full h-full bg-gray-100 rounded-[20px] flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-user-pen text-3xl text-gray-300"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Nama Lengkap Penulis</label>
                        <input type="text" name="name" value="{{ old('name', $author->name) }}" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="Masukkan nama penulis" required>
                        @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Contoh Field Tambahan: Bio (Jika Ada) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Biografi Singkat</label>
                        <textarea name="bio" rows="4" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="Tuliskan biografi singkat...">{{ old('bio', $author->bio ?? '') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-bold hover:bg-black transition shadow-xl">
                            Simpan Perubahan Penulis
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
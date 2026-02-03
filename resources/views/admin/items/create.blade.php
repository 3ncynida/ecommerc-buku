@extends('admin.admin-layout')
@section('title', 'Tambah Item Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('items.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center text-sm transition font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Item
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-xl">Tambah Item Baru</h3>
            <p class="text-sm text-gray-500 font-medium">Lengkapi detail informasi buku atau produk di bawah ini.</p>
        </div>

        @if ($errors->any())
            <div class="m-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-bold text-uppercase">Terjadi Kesalahan:</p>
                        <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Buku / Item</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none"
                        placeholder="Masukkan judul buku lengkap" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Penulis / Author</label>
                    <select name="author_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                        <option value="">-- Pilih Author --</option>
                        @foreach ($author as $a)
                            <option value="{{ $a->id }}" {{ old('author_id') == $a->id ? 'selected' : '' }}>
                                {{ $a->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 font-medium">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                            placeholder="0" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Stok</label>
                    <input type="number" name="stok" value="{{ old('stok') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="Contoh: 50" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Penerbit</label>
                    <input type="text" name="publisher" value="{{ old('publisher') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="Nama penerbit">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Terbit</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="Contoh: 2024">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="ISBN (opsional)">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Halaman</label>
                    <input type="number" name="pages" value="{{ old('pages') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="Contoh: 300">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Bahasa</label>
                    <select name="language" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="">-- Pilih Bahasa --</option>
                        <option value="Indonesia" {{ old('language') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                        <option value="Inggris" {{ old('language') == 'Inggris' ? 'selected' : '' }}>Inggris</option>
                        <option value="Jepang" {{ old('language') == 'Jepang' ? 'selected' : '' }}>Jepang</option>
                        <option value="Mandarin" {{ old('language') == 'Mandarin' ? 'selected' : '' }}>Mandarin</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                    <textarea name="description" rows="4" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="Tuliskan sinopsis atau deskripsi barang di sini...">{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sampul / Gambar Produk</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition cursor-pointer bg-gray-50">
                        <div class="space-y-1 text-center">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                    <span>Upload file gambar</span>
                                    <input id="file-upload" name="image" type="file" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center gap-4 mt-10 pt-6 border-t border-gray-100">
                <button type="reset" class="text-gray-500 hover:text-gray-700 font-medium transition">
                    Reset Form
                </button>
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Simpan Data Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('file-upload').onchange = function () {
        const fileName = this.files[0].name;
        alert('File terpilih: ' + fileName);
    };
</script>
@endsection
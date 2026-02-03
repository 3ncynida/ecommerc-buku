@extends('admin.admin-layout')
@section('title', 'Manajemen Item & Master Data')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-tags text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Master Data</p>
                <h4 class="text-lg font-bold text-gray-800">Kategori</h4>
            </div>
        </div>
        <a href="{{ route('categories.create') }}" class="bg-white border border-indigo-600 text-indigo-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-50 transition">
            + Tambah Kategori
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user-pen text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Master Data</p>
                <h4 class="text-lg font-bold text-gray-800">Author</h4>
            </div>
        </div>
        <a href="{{ route('authors.create') }}" class="bg-white border border-emerald-600 text-emerald-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-50 transition">
            + Tambah Author
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="font-bold text-gray-800 text-xl">Daftar Item</h3>
            <p class="text-sm text-gray-500">Kelola semua produk dan inventaris Anda di sini.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('items.index') }}">
                <select name="category_id" onchange="this.form.submit()" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('items.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                + Tambah Item Baru
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Gambar</th>
                    <th class="px-6 py-4">Detail Barang</th>
                    <th class="px-6 py-4">Author</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($items as $item)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">#{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">
                        @if ($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" class="w-14 h-14 object-cover rounded-xl shadow-sm border border-white">
                        @else
                            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-image text-lg"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $item->name }}</div>
                        <div class="text-xs text-indigo-600 font-medium px-2 py-0.5 bg-indigo-50 rounded-full inline-block mt-1">
                            {{ $item->category->name ?? 'Tanpa Kategori' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                            <span>{{ $item->author->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center space-x-1">
                            <a href="{{ route('items.edit', $item->id) }}" class="text-blue-500 hover:bg-blue-50 p-2.5 rounded-xl transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:bg-red-50 p-2.5 rounded-xl transition" onclick="return confirm('Hapus item ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-inbox text-4xl text-gray-200 mb-3"></i>
                            <p class="text-gray-400 text-sm">Belum ada data item yang tersedia</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
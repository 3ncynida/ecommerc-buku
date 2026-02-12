@extends('admin.admin-layout')
@section('title', 'Manajemen Item')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="font-bold text-gray-800">Daftar Item</h3>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('items.index') }}">
                    <select name="category_id" onchange="this.form.submit()"
                        class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('items.create') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center">
                    <span class="mr-1">+</span> Tambah Item
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Gambar</th>
                        <th class="px-6 py-4">Nama Item</th>
                        <th class="px-6 py-4">Author</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-100 shadow-sm">
                                @else
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-[10px]">
                                        No Img
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->author->name }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-emerald-600">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('items.edit', $item->id) }}"
                                        class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition" title="Edit Item">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition"
                                            onclick="return confirm('Hapus item ini?')" title="Hapus Item">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                <i class="fa-solid fa-box-open block text-2xl mb-2"></i>
                                Data item belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-8">
                {{-- Menampilkan navigasi halaman --}}
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }}
                        buku
                    </p>

                    {{-- Tombol Navigasi --}}
                    <div class="pagination-wrapper">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
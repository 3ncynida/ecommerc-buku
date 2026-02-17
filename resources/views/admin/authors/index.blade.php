@extends('admin.admin-layout')
@section('title', 'Manajemen Author')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Daftar Author</h3>
            <a href="{{ route('authors.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                    + Tambah Author
                </button>
            </a>
        </div>

        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama Author</th>
                    <th class="px-6 py-4">Biografi</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($authors as $author)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $author->name }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $author->bio ? Str::limit($author->bio, 50) : 'tidak diisi' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('authors.edit', $author->id) }}"
                                    class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('authors.destroy', $author->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition"
                                        onclick="return confirm('Hapus data ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-8 px-4">
            {{ $authors->links() }} {{-- Ganti variabel sesuai context: $categories atau $authors --}}
        </div>
    </div>
@endsection


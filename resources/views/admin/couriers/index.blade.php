@extends('admin.admin-layout')
@section('title', 'Manajemen Kurir')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Daftar Kurir</h3>
            <a href="{{ route('couriers.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                    + Tambah Kurir
                </button>
            </a>
        </div>

        @if (session('success'))
            <div class="px-6 pt-4 text-sm text-emerald-600 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="px-6 pt-4 text-sm text-rose-600 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($couriers as $courier)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $courier->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $courier->email }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('couriers.show', $courier->id) }}"
                                    class="text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('couriers.edit', $courier->id) }}"
                                    class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('couriers.destroy', $courier->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition"
                                        onclick="return confirm('Hapus kurir ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">
                            Belum ada kurir yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-8 px-4">
            {{ $couriers->links() }}
        </div>
    </div>
@endsection

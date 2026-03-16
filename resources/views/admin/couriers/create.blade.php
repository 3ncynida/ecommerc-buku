@extends('admin.admin-layout')
@section('title', 'Tambah Kurir')

@section('content')
    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-800 mb-6">Tambah Kurir Baru</h3>

        <form action="{{ route('couriers.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                @error('email')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-indigo-700">
                    Simpan
                </button>
                <a href="{{ route('couriers.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

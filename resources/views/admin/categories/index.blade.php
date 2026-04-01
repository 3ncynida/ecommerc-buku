@extends('admin.admin-layout')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="bg-white rounded-[24px] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.06)] border border-slate-200/60 overflow-hidden mb-8">
    
    {{-- Header --}}
    <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Kategori</h3>
            <p class="text-[12px] font-medium text-slate-500 mt-1">Kelola kategori pengelompokan buku dalam katalog</p>
        </div>
        
        <a href="{{ route('categories.create') }}">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </button>
        </a>
    </div>

    {{-- Tabel Kategori --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100" width="5%">No</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Nama Kategori</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($categories as $index => $category)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <span class="text-[13px] font-bold text-slate-400">{{ (isset($categories) && method_exists($categories, 'firstItem') ? $categories->firstItem() + $index : $index + 1) }}</span>
                        </td>

                        <td class="px-8 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                    <i class="fa-solid fa-folder"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 text-[14px]">{{ $category->name }}</span>
                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">Slug: {{ $category->slug ?? \Str::slug($category->name) }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-4">
                            <div class="flex justify-center items-center space-x-2">
                                <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-500 hover:bg-indigo-50 hover:text-indigo-700 w-8 h-8 flex items-center justify-center rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:bg-rose-50 hover:text-rose-700 w-8 h-8 flex items-center justify-center rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-[13px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 border border-slate-100">
                                <i class="fa-solid fa-folder-open text-2xl text-slate-300"></i>
                            </div>
                            <h4 class="text-slate-900 font-bold mb-1">Data Kategori Kosong</h4>
                            <p class="text-sm text-slate-500 text-center mx-auto max-w-[250px]">Belum ada data kategori. Silakan tambahkan kategori baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer & Pagination --}}
    @if(method_exists($categories, 'hasPages') && $categories->hasPages())
    <div class="p-6 border-t border-slate-100 bg-slate-50/30">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

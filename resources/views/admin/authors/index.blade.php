@extends('admin.admin-layout')
@section('title', 'Manajemen Penulis')

@section('content')
<div class="bg-white rounded-[24px] shadow-sm border border-slate-200/60 overflow-hidden mb-8">
    
    {{-- Header --}}
    <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Penulis</h3>
            <p class="text-[12px] font-medium text-slate-500 mt-1">Kelola direktori penulis dan kontributor buku</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <form action="{{ route('authors.index') }}" method="GET" class="relative w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penulis..." class="w-full sm:w-64 pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </form>
            <a href="{{ route('authors.create') }}" class="w-full sm:w-auto">
                <button class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Penulis
                </button>
            </a>
        </div>
    </div>

    {{-- Tabel Penulis --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100" width="5%">No</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Nama Penulis</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Biografi Singkat</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($authors as $index => $author)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <span class="text-[13px] font-bold text-slate-400">{{ (isset($authors) && method_exists($authors, 'firstItem') ? $authors->firstItem() + $index : $index + 1) }}</span>
                        </td>

                        <td class="px-8 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                                    <i class="fa-solid fa-feather-pointed"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 text-[14px]">{{ $author->name }}</span>
                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">Author ID: #{{ str_pad($author->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-4 max-w-[250px] truncate">
                            <span class="text-[13px] font-medium {{ $author->bio ? 'text-slate-600' : 'text-slate-400 italic' }}">
                                {{ $author->bio ? Str::limit($author->bio, 50) : 'Bio tidak tersedia' }}
                            </span>
                        </td>

                        <td class="px-8 py-4">
                            <div class="flex justify-center items-center space-x-2">
                                <a href="{{ route('authors.edit', $author->id) }}" class="text-indigo-500 hover:bg-indigo-50 hover:text-indigo-700 w-8 h-8 flex items-center justify-center rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </a>
                                <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penulis ini?')">
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
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 border border-slate-100">
                                <i class="fa-solid fa-feather-pointed text-2xl text-slate-300"></i>
                            </div>
                            <h4 class="text-slate-900 font-bold mb-1">Data Penulis Kosong</h4>
                            <p class="text-sm text-slate-500 text-center mx-auto max-w-[250px]">Belum ada entri penulis. Mulai tambahkan penulis baru ke perpustakaan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer & Pagination --}}
    @if(method_exists($authors, 'hasPages') && $authors->hasPages())
    <div class="p-6 border-t border-slate-100 bg-slate-50/30">
        {{ $authors->links() }}
    </div>
    @endif
</div>
@endsection

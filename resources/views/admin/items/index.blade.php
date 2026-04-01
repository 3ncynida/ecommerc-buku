@extends('admin.admin-layout')
@section('title', 'Manajemen Item')

@section('content')
    {{--
    WRAPPER UTAMA: x-data harus membungkus tombol pemicu DAN modalnya
    --}}
    <div x-data="stockModal">
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
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center shadow-md">
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
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Author</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Stok</th>
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
                                <td class="px-6 py-4">
    <div class="flex flex-wrap gap-1">
        @foreach($item->categories as $cat)
            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[10px] font-bold uppercase border border-indigo-100">
                {{ $cat->name }}
            </span>
        @endforeach
    </div>
</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->author->name }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-emerald-600">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm font-bold {{ $item->stok < 5 ? 'text-red-500' : 'text-gray-600' }}">
                                    {{ $item->stok }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <button
                                            @click="openStockModal({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->stok }})"
                                            class="text-emerald-500"
                                            title="Penyesuaian Stok">
                                            <i class="fa-solid fa-boxes-stacked text-[18px]"></i>
                                        </button>

                                        <a href="{{ route('items.show', $item->id) }}"
                                            class="text-slate-500 hover:bg-slate-100 p-2 rounded-lg transition" title="Lihat Detail Item">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

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
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    <i class="fa-solid fa-box-open block text-2xl mb-2"></i>
                                    Data item belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }}
                        buku
                    </p>
                    <div class="pagination-wrapper">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{--
        MODAL SECTION
        --}}
        <div x-show="showStockModal" x-cloak
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity"
            @click.self="closeStockModal">

            <div class="bg-white rounded-[30px] p-8 w-full max-w-md mx-4 shadow-2xl transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-slate-800">Penyesuaian Stok</h3>
                    <button @click="closeStockModal" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <p class="text-sm text-slate-600 mb-1"><strong>Produk:</strong> <span x-text="selectedItemName"
                            class="text-indigo-600 font-bold"></span></p>
                    <p class="text-sm text-slate-600"><strong>Stok Saat Ini:</strong> <span x-text="currentStock"
                            class="font-bold"></span></p>
                </div>

                <form @submit.prevent="updateStock" class="space-y-5">

                    {{-- PILIHAN AKSI: TAMBAH ATAU KURANG --}}
                    <div class="flex gap-4 mb-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" x-model="actionType" value="add" class="peer sr-only">
                            <div
                                class="p-3 text-center rounded-xl border-2 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 text-slate-400 peer-checked:text-emerald-600 font-bold transition">
                                <i class="fa-solid fa-plus mr-1"></i> Tambah
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" x-model="actionType" value="reduce" class="peer sr-only">
                            <div
                                class="p-3 text-center rounded-xl border-2 peer-checked:border-rose-500 peer-checked:bg-rose-50 text-slate-400 peer-checked:text-rose-600 font-bold transition">
                                <i class="fa-solid fa-minus mr-1"></i> Kurangi
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            Jumlah <span x-text="actionType === 'add' ? 'Ditambahkan' : 'Dikurangi'"></span>
                        </label>
                        <input type="number" x-model="quantityToUpdate" min="1" required autofocus
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-xl font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan
                            (Opsional)</label>
                        <textarea x-model="notes" rows="2"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm"
                            placeholder="Misal: Restock atau barang rusak..."></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="closeStockModal"
                            class="flex-1 px-6 py-4 text-slate-400 font-bold rounded-2xl hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" :disabled="isLoading"
                            class="flex-1 px-6 py-4 text-white font-bold rounded-2xl shadow-lg disabled:opacity-50 transition-all flex items-center justify-center"
                            :class="actionType === 'add' ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-200' : 'bg-rose-500 hover:bg-rose-600 shadow-rose-200'">
                            <span x-show="!isLoading" x-text="actionType === 'add' ? 'Simpan' : 'Kurangi'"></span>
                            <span x-show="isLoading" x-cloak class="flex items-center">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CSS UNTUK MENCEGAH MODAL MUNCUL SEKEJAP SAAT LOAD --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- SCRIPT ALPINE.JS --}}
    <script>
        document.addEventListener('alpine:init', () => {
            // ... dalam blok x-data="stockModal"
            Alpine.data('stockModal', () => ({
                showStockModal: false,
                selectedItemId: null,
                selectedItemName: '',
                currentStock: 0,
                quantityToUpdate: 1, // Ubah nama variabel
                actionType: 'add',   // Tambahkan state untuk tipe aksi
                notes: '',
                isLoading: false,

                openStockModal(itemId, itemName, currentStock) {
                    this.selectedItemId = itemId;
                    this.selectedItemName = itemName;
                    this.currentStock = currentStock;
                    this.quantityToUpdate = 1;
                    this.actionType = 'add'; // Default ke tambah
                    this.notes = '';
                    this.showStockModal = true;
                },

                closeStockModal() {
                    this.showStockModal = false;
                    this.isLoading = false;
                },

                async updateStock() {
                    if (this.quantityToUpdate <= 0) {
                        alert('Jumlah stok harus lebih dari 0');
                        return;
                    }

                    if (this.actionType === 'reduce' && this.quantityToUpdate > this.currentStock) {
                        alert('Tidak bisa mengurangi stok melebihi stok yang ada saat ini.');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const response = await fetch(`/admin/items/${this.selectedItemId}/stock`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                quantity: this.quantityToUpdate,
                                action_type: this.actionType, // Kirim tipe aksi ke backend
                                notes: this.notes
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert(data.message || 'Stok berhasil diperbarui!');
                            location.reload();
                        } else {
                            // Tampilkan pesan error dari backend (misal error 422)
                            alert(data.message || 'Gagal memperbarui stok');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan koneksi ke server');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }));
        });
    </script>
@endsection
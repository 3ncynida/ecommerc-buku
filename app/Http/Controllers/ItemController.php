<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Author;
use App\Models\Category;
use App\Models\StockLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // menampilkan semua item
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk dropdown filter
        $categories = Category::all();

        // 2. Query utama dengan eager loading 'categories' agar tidak berat
        $items = Item::with('categories')
            ->when($request->category_id, function ($query) use ($request) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('categories.id', $request->category_id);
                });
            })
            ->latest()
            ->paginate(10); // Menggunakan pagination (10 data per halaman)

        // 3. Tambahkan parameter query ke link pagination
        $items->appends($request->all());

        return view('admin.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $author = Author::all();
        $categories = Category::all();
        return view('admin.items.create', compact('categories', 'author'));
    }


    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_ids' => 'required|array', // Harus array (karena banyak kategori)
            'category_ids.*' => 'exists:categories,id', // Setiap ID kategori harus valid
            'author_id' => 'required|exists:authors,id',
            'stok' => 'required|numeric',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|numeric|min:1000|max:2999',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|numeric|min:1',
            'language' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        // 2. Olah Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        // 3. Simpan Data Buku Utama (Tanpa category_id tunggal)
        $item = Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'stok' => $request->stok,
            'author_id' => $request->author_id,
            'publisher' => $request->publisher,
            'publication_year' => $request->publication_year,
            'isbn' => $request->isbn,
            'pages' => $request->pages,
            'language' => $request->language,
            'image' => $imagePath,
        ]);

        // 4. Simpan Relasi Kategori ke Tabel Pivot
        // Method sync() otomatis mengisi tabel category_item
        $item->categories()->sync($request->category_ids);

        return redirect()->route('items.index')->with('success', 'Buku dan kategori berhasil ditambahkan');
    }

    // Update stok item via AJAX/modal
    public function updateStock(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
            'action_type' => 'required|in:add,reduce', // Tambahkan validasi tipe aksi
        ]);

        $oldStock = $item->stok;
        $quantity = $request->input('quantity');
        $notes = $request->input('notes');
        $actionType = $request->input('action_type');

        // Validasi agar stok tidak minus jika dikurangi
        if ($actionType === 'reduce' && $item->stok < $quantity) {
            return response()->json([
                'message' => 'Gagal: Jumlah pengurangan melebihi stok saat ini.',
            ], 422);
        }

        // Update stok berdasarkan aksi
        if ($actionType === 'add') {
            $item->stok += $quantity;
            // Agar quantity_added di tabel log bernilai positif
            $logQuantity = $quantity;
        } else {
            $item->stok -= $quantity;
            // Agar quantity_added di tabel log bernilai negatif (opsional, tergantung struktur DB Anda)
            // Jika Anda punya kolom 'quantity_reduced', Anda bisa sesuaikan di sini.
            $logQuantity = -$quantity;
        }

        $item->save();

        // Log perubahan stok
        StockLog::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'quantity_added' => $logQuantity, // Akan bernilai negatif jika dikurangi
            'previous_stock' => $oldStock,
            'new_stock' => $item->stok,
            'notes' => $notes,
        ]);

        return response()->json([
            'message' => 'Stok berhasil diperbarui',
            'new_stock' => $item->stok,
        ]);
    }

    // Tampilkan form edit
    public function edit(Item $item)
    {
        $author = Author::all();
        $categories = Category::all();

        // Mengambil ID kategori yang sudah dimiliki buku ini untuk di-check di view
        $selectedCategoryIds = $item->categories->pluck('id')->toArray();

        return view('admin.items.edit', compact('item', 'categories', 'author', 'selectedCategoryIds'));
    }

    public function update(Request $request, Item $item)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'stok' => 'required|numeric',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|numeric|min:1000|max:2999',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|numeric|min:1',
            'language' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        // 2. Update Gambar jika ada file baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage
            if ($item->image) {
                \Storage::disk('public')->delete($item->image);
            }
            $item->image = $request->file('image')->store('items', 'public');
        }

        // 3. Perbarui Data Buku Utama
        $item->update([
            'name' => $request->name,
            'slug' => str($request->name)->slug(), // Update slug jika nama berubah
            'price' => $request->price,
            'description' => $request->description,
            'stok' => $request->stok,
            'author_id' => $request->author_id,
            'publisher' => $request->publisher,
            'publication_year' => $request->publication_year,
            'isbn' => $request->isbn,
            'pages' => $request->pages,
            'language' => $request->language,
            'image' => $item->image,
        ]);

        // 4. Sinkronisasi Kategori (Many-to-Many)
        // sync() akan otomatis menghapus yang tidak dipilih dan menambah yang baru
        $item->categories()->sync($request->category_ids);

        return redirect()->route('items.index')->with('success', 'Data buku dan kategori berhasil diperbarui');
    }

    public function destroy(Item $item)
    {
        // Cek apakah item sedang terkait dengan sebuah order
        if (\App\Models\OrderItem::where('item_id', $item->id)->exists()) {
            return back()->with('error', 'Item tidak dapat dihapus karena tercatat dalam riwayat pesanan (Order).');
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();
        return back()->with('success', 'Item berhasil dihapus');
    }

    public function show(Item $item)
    {
        // Ambil buku lain dengan kategori yang sama sebagai rekomendasi
        $relatedBooks = Item::where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->take(4)
            ->get();

        return view('admin.items.show', compact('item', 'relatedBooks'));
    }

    public function liveSearch(Request $request)
    {
        $query = $request->get('q');
        if (empty($query)) {
            return response()->json([]);
        }

        // Cari Buku
        $items = \App\Models\Item::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'slug']);

        // Cari Penulis
        $authors = \App\Models\Author::where('name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'items' => $items,
            'authors' => $authors,
        ]);
    }
}

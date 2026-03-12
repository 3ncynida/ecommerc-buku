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

        // 2. Query utama dengan eager loading 'category' agar tidak berat
        $items = Item::with('category')
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
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


    // simpan item baru + upload gambar
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'stok' => 'required|numeric',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|numeric|min:1000|max:2999',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|numeric|min:1',
            'language' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
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

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan');
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

    // tampilkan form edit
    public function edit(Item $item)
    {
        $author = Author::all();
        $categories = Category::all();


        return view('admin.items.edit', compact('item', 'categories', 'author'));
    }

    // update item + ganti gambar
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'stok' => 'required|numeric',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|numeric|min:1000|max:2999',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|numeric|min:1',
            'language' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->image = $request->file('image')->store('items', 'public');
        }

        $item->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'stok' => $request->stok,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'publisher' => $request->publisher,
            'publication_year' => $request->publication_year,
            'isbn' => $request->isbn,
            'pages' => $request->pages,
            'language' => $request->language,
            'image' => $item->image,
        ]);

        return redirect()->route('items.index')->with('success', 'Item berhasil diupdate');
    }

    public function destroy(Item $item)
    {
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

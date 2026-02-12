<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // menampilkan semua item
    public function index(Request $request)
    {
        $items = Item::latest()->get();
        $categories = Category::all();

        $items = Item::when($request->category_id, function ($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })->latest()->get();

        return view('admin.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }


    // simpan item baru + upload gambar
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
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
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Item berhasil ditambahkan');
    }

    // tampilkan form edit
    public function edit(Item $item)
    {
        $categories = Category::all();

        return view('admin.items.edit', compact('item', 'categories'));
    }

    // update item + ganti gambar
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
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
            'category_id' => $request->category_id,
            'image' => $item->image,
        ]);

        return redirect()->route('items.index')->with('success', 'Item berhasil diupdate');
    }
}

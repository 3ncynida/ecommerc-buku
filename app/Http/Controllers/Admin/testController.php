<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Author;
use Illuminate\Http\Request;

class testController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        $authors = Author::latest()->get();
        $items = Item::latest()->get();

        return view('admin.test.index', compact('categories', 'authors', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Category::create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus');
    }
}

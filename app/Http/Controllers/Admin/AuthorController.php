<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::latest()->paginate(10);
        return view('admin.authors.index', compact('authors'));
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return back()->with('success', 'Penulis berhasil dihapus');
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);
        Author::create($request->only('name', 'bio'));
        return redirect()->route('authors.index')->with('success', 'Penulis berhasil ditambahkan');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);
        $author->update($request->only('name', 'bio'));
        return redirect()->route('authors.index')->with('success', 'Penulis berhasil diupdate');
    }
}

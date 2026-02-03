<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::latest()->get();
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
        ]);
        Author::create($request->only('name'));
        return redirect()->route('authors.index')->with('success', 'Penulis berhasil ditambahkan');
    }
}

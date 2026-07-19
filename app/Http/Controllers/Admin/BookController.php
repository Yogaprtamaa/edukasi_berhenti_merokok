<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->paginate(20);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['cover_url'] = $this->storeCover($request);

        Book::create($data);

        return redirect()->route('admin.books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $this->validated($request);

        if ($coverUrl = $this->storeCover($request)) {
            $data['cover_url'] = $coverUrl;
        }

        $book->update($data);

        return redirect()->route('admin.books')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'author'      => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:2000'],
            'price'       => ['required', 'numeric', 'min:0'],
            'isbn'        => ['nullable', 'string', 'max:20'],
            'stock'       => ['required', 'integer', 'min:0'],
            'cover'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['is_available'] = $request->boolean('is_available');
        unset($data['cover']);

        return $data;
    }

    private function storeCover(Request $request): ?string
    {
        return $request->hasFile('cover')
            ? Storage::url($request->file('cover')->store('books/covers', 'public'))
            : null;
    }
}

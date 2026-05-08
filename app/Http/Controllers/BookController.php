<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(12);
        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function order(Request $request, Book $book)
    {
        $data = $request->validate([
            'quantity'       => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:transfer,e-wallet,credit_card'],
        ]);

        Order::create([
            'user_id'        => Auth::id(),
            'book_id'        => $book->id,
            'quantity'       => $data['quantity'],
            'total_price'    => $book->price * $data['quantity'],
            'status'         => 'pending',
            'payment_method' => $data['payment_method'],
        ]);

        return redirect()->route('books.index')
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }
}

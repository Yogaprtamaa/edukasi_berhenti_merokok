<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(12);
        return view('books.index', compact('books'));
    }

    public function purchased()
    {
        $orders = Order::with(['book', 'payment'])
            ->where('user_id', Auth::id())
            ->whereHas('payment', fn($query) => $query->where('status', 'success'))
            ->latest()
            ->paginate(12);

        return view('books.purchased', compact('orders'));
    }

    public function show(Book $book)
    {
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereHas('payment', fn($query) => $query->where('status', 'success'))
            ->exists();

        return view('books.show', compact('book', 'hasPurchased'));
    }

    public function read(Book $book)
    {
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereHas('payment', fn($query) => $query->where('status', 'success'))
            ->exists();

        abort_unless($hasPurchased, 403);

        return view('books.read', compact('book'));
    }

    public function order(Request $request, Book $book)
    {
        $data = $request->validate([
            'quantity'       => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:transfer,e-wallet,credit_card'],
        ]);

        $totalPrice = $book->price * $data['quantity'];

        $payment = Payment::create([
            'user_id'        => Auth::id(),
            'reference_id'   => 'BOOK-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
            'amount'         => $totalPrice,
            'duration_hours' => 1,
            'status'         => 'pending',
            'payment_method' => $data['payment_method'],
            'description'    => 'Pembelian buku: ' . $book->title,
        ]);

        Order::create([
            'user_id'        => Auth::id(),
            'book_id'        => $book->id,
            'quantity'       => $data['quantity'],
            'unit_price'     => $book->price,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_id'     => $payment->id,
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }
}

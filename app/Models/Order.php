<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Order yang masih memberi akses ke buku: pembayaran sukses DAN order
     * belum dibatalkan admin. Payment sukses saja tidak cukup — admin bisa
     * membatalkan order tanpa mengubah status payment.
     */
    public function scopeAccessible($query)
    {
        return $query
            ->where('status', '!=', 'cancelled')
            ->whereHas('payment', fn($q) => $q->where('status', 'success'));
    }
}

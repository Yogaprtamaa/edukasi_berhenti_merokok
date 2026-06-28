<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'user_id', 'reference_id', 'amount', 'duration_hours',
        'status', 'refund_percentage', 'refund_amount',
        'payment_method', 'description', 'paid_at', 'refunded_at',
    ];

    protected $casts = [
        'paid_at'     => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}

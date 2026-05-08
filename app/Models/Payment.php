<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'user_id', 'amount', 'duration_hours',
        'status', 'refund_percentage', 'refund_amount',
        'payment_method', 'paid_at', 'refunded_at',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}

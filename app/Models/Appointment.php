<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'professional_id', 'schedule_id',
        'mode', 'status', 'appointment_date',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

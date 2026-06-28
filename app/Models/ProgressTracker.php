<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ProgressTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'quit_date', 'streak_days', 'cigarettes_per_day',
        'cigarettes_avoided', 'money_saved', 'last_check_in',
    ];

    protected $casts = [
        'quit_date'     => 'date',
        'last_check_in' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

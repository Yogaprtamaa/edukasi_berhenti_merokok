<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class DailyCheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'check_in_date',
        'is_smoke_free',
        'cigarettes_avoided',
        'money_saved',
        'notification_id',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'is_smoke_free' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

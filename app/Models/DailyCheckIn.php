<?php

namespace App\Models;

use Carbon\Carbon;
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

    /**
     * Filter check-in pada satu hari penuh.
     * Pakai range query karena whereDate() tidak didukung MongoDB (Jenssegers).
     */
    public function scopeOnDate($query, $date)
    {
        $date = Carbon::parse($date);

        return $query
            ->where('check_in_date', '>=', $date->copy()->startOfDay())
            ->where('check_in_date', '<', $date->copy()->addDay()->startOfDay());
    }
}

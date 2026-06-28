<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ProgressTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'quit_date', 'streak_days', 'cigarettes_per_day',
        'price_per_pack', 'cigarettes_per_pack',
        'cigarettes_avoided', 'money_saved', 'last_check_in',
    ];

    protected $casts = [
        'quit_date'           => 'date',
        'last_check_in'       => 'datetime',
        'cigarettes_per_day'  => 'integer',
        'cigarettes_per_pack' => 'integer',
        'price_per_pack'      => 'float',
        'cigarettes_avoided'  => 'integer',
        'money_saved'         => 'float',
        'streak_days'         => 'integer',
    ];

    /**
     * Uang dihemat dari jumlah rokok yang dihindari.
     */
    public function calculateMoneySaved(int $cigarettesAvoided): float
    {
        $perPack = (int) ($this->cigarettes_per_pack ?: 0);
        if ($perPack <= 0) {
            return 0.0;
        }

        return ($cigarettesAvoided / $perPack) * (float) $this->price_per_pack;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

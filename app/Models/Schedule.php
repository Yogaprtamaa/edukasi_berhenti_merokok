<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['professional_id', 'day_of_week', 'start_time', 'end_time', 'mode'];

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ProfessionalVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'professional_id',
        'admin_id',
        'status',
        'notes',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];
}

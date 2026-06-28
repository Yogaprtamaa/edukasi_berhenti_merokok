<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ContentApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'admin_id',
        'status',
        'notes',
        'processed_at',
        'approved_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

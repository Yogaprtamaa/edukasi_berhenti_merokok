<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author', 'description', 'price',
        'isbn', 'cover_url', 'stock', 'is_available',
    ];

    protected $casts = [
        'price'        => 'float',
        'stock'        => 'integer',
        'is_available' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

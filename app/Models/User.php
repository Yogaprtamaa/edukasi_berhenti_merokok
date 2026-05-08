<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Professional;
use App\Models\ProgressTracker;
use App\Models\DailyCheckIn;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'role',
        'is_email_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function professional()
    {
        return $this->hasOne(Professional::class);
    }

    public function progressTracker()
    {
        return $this->hasOne(ProgressTracker::class);
    }

    public function dailyCheckIns()
    {
        return $this->hasMany(DailyCheckIn::class);
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }
}

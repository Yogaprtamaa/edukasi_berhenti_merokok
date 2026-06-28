<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'content',
        'category',
        'views',
        'views_count',
        'replies_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
    }
}

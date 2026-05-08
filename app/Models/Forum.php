<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'body', 'views'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
    }
}

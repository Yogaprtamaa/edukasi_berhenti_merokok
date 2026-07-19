<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploader_id', 'uploader_role', 'title', 'description', 'body', 'type',
        'thumbnail_url', 'video_url',
        'approval_status', 'is_published', 'published_at',
    ];

    /** Video hasil upload (disimpan di /storage), bukan link eksternal. */
    public function getIsVideoUploadAttribute(): bool
    {
        return $this->video_url && str_starts_with($this->video_url, '/storage/');
    }

    /**
     * URL embed YouTube dari link biasa (watch?v= / youtu.be).
     * Video upload & link non-YouTube dikembalikan apa adanya.
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        return preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/))([\w-]{11})~', $this->video_url, $m)
            ? "https://www.youtube.com/embed/{$m[1]}"
            : $this->video_url;
    }

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}

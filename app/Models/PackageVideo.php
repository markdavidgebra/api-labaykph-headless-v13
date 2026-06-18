<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageVideo extends Model
{
    use HasFactory;

    /**
     * Get the YouTube video ID for embedding (handles full URLs or raw IDs).
     */
    public function getYoutubeVideoIdAttribute(): ?string
    {
        return extract_youtube_video_id($this->video);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}

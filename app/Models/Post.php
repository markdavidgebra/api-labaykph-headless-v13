<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'photo',
        'title',
        'slug',
        'description',
        'short_description',
    ];

    public function blog_category()
    {
        return $this->belongsTo(BlogCategory::class);
    }
}

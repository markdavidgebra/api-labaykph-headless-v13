<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_heading',
        'destination_subheading',
        'destination_status',
        'feature_status',
        'package_heading',
        'package_subheading',
        'package_status',
        'testimonial_heading',
        'testimonial_subheading',
        'testimonial_background',
        'testimonial_status',
        'blog_heading',
        'blog_subheading',
        'blog_status',
        'cta_label',
        'cta_title',
        'cta_text',
        'cta_background',
        'cta_status',
    ];
}

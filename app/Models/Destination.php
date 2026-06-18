<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'country',
        'language',
        'currency',
        'area',
        'timezone',
        'visa_requirement',
        'activity',
        'best_time',
        'health_safety',
        'map',
        'featured_photo',
        'view_count',
    ];

    public function photos()
    {
        return $this->hasMany(DestinationPhoto::class);
    }

    public function videos()
    {
        return $this->hasMany(DestinationVideo::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}

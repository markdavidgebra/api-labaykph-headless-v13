<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'amenity_id',
        'type',
    ];

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}

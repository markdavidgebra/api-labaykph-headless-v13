<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'name',
        'designation',
        'comment',
    ];

    /**
     * Display name masked for privacy: first letter + **** + last letter (e.g. "Anonymous" → "A****s").
     */
    public function getMaskedNameAttribute(): string
    {
        $name = trim((string) $this->name);
        if ($name === '') {
            return '—';
        }
        $len = mb_strlen($name);
        if ($len === 1) {
            return $name . '*';
        }
        return mb_substr($name, 0, 1) . '****' . mb_substr($name, -1);
    }
}

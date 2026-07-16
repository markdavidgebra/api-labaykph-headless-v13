<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'description',
        'button_text',
        'button_link',
        'photo',
        'video',
        'status',
    ];
}

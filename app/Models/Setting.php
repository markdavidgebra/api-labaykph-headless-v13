<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'favicon',
        'banner',
        'payment_qr',
        'payment_qr_note',
        'top_bar_phone',
        'top_bar_email',
        'footer_address',
        'footer_phone',
        'footer_email',
        'facebook',
        'twitter',
        'youtube',
        'linkedin',
        'instagram',
        'copyright',
    ];
}

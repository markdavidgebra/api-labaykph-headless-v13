<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'type', 'message', 'admin_viewed_at'];

    protected $casts = [
        'admin_viewed_at' => 'datetime',
    ];
}

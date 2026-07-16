<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item1_number',
        'item1_text',
        'item2_number',
        'item2_text',
        'item3_number',
        'item3_text',
        'item4_number',
        'item4_text',
        'status',
    ];
}

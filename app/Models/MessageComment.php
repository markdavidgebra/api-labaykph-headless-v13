<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'sender_id',
        'type',
        'comment',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }
}

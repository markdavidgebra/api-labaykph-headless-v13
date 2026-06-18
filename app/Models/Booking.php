<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'package_id',
        'user_id',
        'total_person',
        'paid_amount',
        'payment_method',
        'payment_status',
        'invoice_no',
        'admin_viewed_at',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'featured_photo',
        'banner',
        'name',
        'slug',
        'description',
        'map',
        'price',
        'old_price',
        'total_rating',
        'total_score',
        'sold_out',
    ];

    protected $casts = [
        'sold_out' => 'boolean',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function package_amenities()
    {
        return $this->hasMany(PackageAmenity::class);
    }

    public function package_itineraries()
    {
        return $this->hasMany(PackageItinerary::class);
    }

    public function package_photos()
    {
        return $this->hasMany(PackagePhoto::class);
    }

    public function package_videos()
    {
        return $this->hasMany(PackageVideo::class);
    }

    public function package_faqs()
    {
        return $this->hasMany(PackageFaq::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * True when all active tours (booking_end_date >= today) are fully booked (0 remaining seats).
     * Used to show "SOLD OUT" on package cards automatically.
     */
    public function isFullyBooked(): bool
    {
        $activeTours = $this->tours()
            ->where('booking_end_date', '>=', now()->format('Y-m-d'))
            ->get();

        if ($activeTours->isEmpty()) {
            return false;
        }

        foreach ($activeTours as $tour) {
            $booked = Booking::where('tour_id', $tour->id)
                ->where('package_id', $this->id)
                ->sum('total_person');
            $remaining = $tour->total_seat == -1 ? 1 : ($tour->total_seat - $booked);
            if ($remaining > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Show "SOLD OUT" when manually marked or when all active tours are full.
     */
    public function getShowSoldOutAttribute(): bool
    {
        return $this->sold_out || $this->isFullyBooked();
    }
}

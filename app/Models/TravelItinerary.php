<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelItinerary extends Model
{
    protected $fillable = [
        'user_id',
        'destination',
        'budget',
        'days',
        'itinerary_data',
    ];

    protected $casts = [
        'itinerary_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Event;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'location',
        'description',
        'is_available'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_available' => 'boolean'
    ];

    /**
     * Get the events for the venue
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Check if venue is available for a given time period
     */
    public function isAvailable($startDate, $endDate): bool
    {
        if (!$this->is_available) {
            return false;
        }

        return !$this->events()
            ->where('status', 'approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->exists();
    }

    /**
     * Get upcoming events for the venue
     */
    public function upcomingEvents()
    {
        return $this->events()
            ->where('status', 'approved')
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->with('club');
    }
}

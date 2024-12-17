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
        'facilities'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'facilities' => 'array'
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
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->with('club');
    }

    /**
     * Get venue utilization percentage for a given period
     */
    public function getUtilization($startDate, $endDate): float
    {
        $events = $this->events()
            ->where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get();
            
        if ($events->isEmpty()) {
            return 0;
        }
        
        $avgAttendance = $events->avg('expected_attendance');
        return round(($avgAttendance / $this->capacity) * 100, 2);
    }
}

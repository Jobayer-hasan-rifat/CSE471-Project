<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'club_id',
        'venue_id',
        'start_date',
        'end_date',
        'expected_attendance',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'expected_attendance' => 'integer'
    ];

    /**
     * Get the club that owns the event
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get the venue for the event
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the documents for the event
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EventDocument::class);
    }

    /**
     * Check if there are any scheduling conflicts for a venue
     */
    public static function hasConflicts($venueId, $startDate, $endDate): bool
    {
        return static::where('venue_id', $venueId)
            ->where('status', 'approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })->exists();
    }

    /**
     * Get event statistics
     */
    public static function getStats(): array
    {
        $total = static::count();
        $approved = static::where('status', 'approved')->count();
        $pending = static::where('status', 'pending')->count();
        $rejected = static::where('status', 'rejected')->count();

        return [
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected
        ];
    }

    /**
     * Get upcoming events for a club
     */
    public static function getUpcomingEvents($clubId, $limit = 5)
    {
        return static::where('club_id', $clubId)
            ->where('start_date', '>=', now())
            ->where('status', 'approved')
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get events for calendar
     */
    public static function getCalendarEvents($startDate, $endDate, $venueId = null, $clubId = null)
    {
        $query = static::where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate]);

        if ($venueId) {
            $query->where('venue_id', $venueId);
        }

        if ($clubId) {
            $query->where('club_id', $clubId);
        }

        return $query->with(['club', 'venue'])->get();
    }
}

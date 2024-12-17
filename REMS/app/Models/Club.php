<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Club extends Model
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'description',
        'logo',
        'status'
    ];

    /**
     * Get the events for the club
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the members of the club
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'club_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the executives of the club
     */
    public function executives()
    {
        return $this->members()->wherePivot('role', 'executive');
    }

    /**
     * Get the regular members of the club
     */
    public function regularMembers()
    {
        return $this->members()->wherePivot('role', 'member');
    }

    /**
     * Get club's event statistics
     */
    public function getEventStats(): array
    {
        return [
            'total' => $this->events()->count(),
            'approved' => $this->events()->where('status', 'approved')->count(),
            'pending' => $this->events()->where('status', 'pending')->count(),
            'rejected' => $this->events()->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Get upcoming events for the club
     */
    public function upcomingEvents()
    {
        return $this->events()
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->with('venue');
    }

    /**
     * Check if a user is a member of the club
     */
    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is an executive of the club
     */
    public function isExecutive(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'executive')
            ->exists();
    }
}

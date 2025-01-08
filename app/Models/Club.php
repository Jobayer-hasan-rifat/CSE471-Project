<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use App\Models\User; // Add this line

class Club extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'short_name',
        'description',
        'logo_path',
        'user_id',
        'status',
        'president_name',
        'email',
        'phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $appends = [
        'logo_url'
    ];

    protected $attributes = [
        'status' => 'active',
        'is_active' => true
    ];

    public function getLogoUrlAttribute()
    {
        // Check if the club has a logo file in public/images directory
        $logoPath = public_path('images/' . strtolower($this->short_name) . '.png');
        if (file_exists($logoPath)) {
            return asset('images/' . strtolower($this->short_name) . '.png');
        }
        
        // Generate a default logo with club initials if no logo is found
        return "https://ui-avatars.com/api/?name=" . urlencode($this->short_name) . "&background=4F46E5&color=fff&bold=true&size=128";
    }

    /**
     * Get the user that manages this club.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the events for the club.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the announcements for the club.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Get the chat messages for the club.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the transactions for the club.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the members of the club.
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

    /**
     * Get the chats for the club
     */
    public function chats(): HasMany
    {
        return $this->hasMany(ClubChat::class);
    }

    /**
     * Get the positions for the club
     */
    public function positions()
    {
        return $this->hasMany(ClubPosition::class)->orderBy('order');
    }

    /**
     * Scope a query to only include active clubs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '=', 'active');
    }

    /**
     * Scope a query to only include inactive clubs.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', '=', 'inactive');
    }
}

<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('oca') || $user->hasRole('club');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        if ($user->hasRole('oca')) {
            return true;
        }

        if ($user->hasRole('club')) {
            return $event->club_id === $user->club->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('club');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        if (!$user->hasRole('club')) {
            return false;
        }

        return $event->club_id === $user->club->id && $event->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        if (!$user->hasRole('club')) {
            return false;
        }

        return $event->club_id === $user->club->id && $event->status === 'pending';
    }

    /**
     * Determine whether the user can approve events.
     */
    public function approve(User $user, Event $event): bool
    {
        return $user->hasRole('oca') && $event->status === 'pending';
    }

    /**
     * Determine whether the user can reject events.
     */
    public function reject(User $user, Event $event): bool
    {
        return $user->hasRole('oca') && $event->status === 'pending';
    }
}

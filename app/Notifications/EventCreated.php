<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class EventCreated extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'title' => 'New Event Request',
            'message' => "New event '{$this->event->event_name}' requires approval from {$this->event->club->name}",
            'type' => 'event_approval',
            'club_id' => $this->event->club_id,
            'venue_id' => $this->event->venue_id,
            'start_date' => $this->event->start_date,
            'event_type' => $this->event->event_type
        ];
    }
}

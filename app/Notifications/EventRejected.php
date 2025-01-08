<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventRejected extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Event Booking Rejected')
            ->line('Your event booking has been rejected.')
            ->line('Event: ' . $this->event->name)
            ->line('Venue: ' . $this->event->venue->name)
            ->line('Reason: ' . $this->event->rejection_reason)
            ->action('View Event Details', route('events.show', $this->event))
            ->line('Please review the rejection reason and submit a new booking if needed.');
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
            'message' => 'Your event "' . $this->event->name . '" has been rejected.',
            'reason' => $this->event->rejection_reason,
            'action_url' => route('events.show', $this->event)
        ];
    }
}

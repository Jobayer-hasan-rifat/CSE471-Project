<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event, string $status)
    {
        $this->event = $event;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = ucfirst($this->status);
        $message = $this->status === 'approved' 
            ? "Great news! Your event has been approved."
            : "We regret to inform you that your event has been rejected.";

        return (new MailMessage)
            ->subject("Event {$statusText}: {$this->event->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line($message)
            ->line("Event: {$this->event->title}")
            ->line("Date: {$this->event->start_date->format('F j, Y g:i A')}")
            ->line("Venue: {$this->event->venue->name}")
            ->action('View Event Details', route('club.events.show', $this->event))
            ->line('Thank you for using our event management system!');
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
            'title' => $this->event->title,
            'status' => $this->status,
            'message' => $this->status === 'approved' 
                ? "Your event '{$this->event->title}' has been approved!"
                : "Your event '{$this->event->title}' has been rejected.",
        ];
    }
}

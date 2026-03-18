<?php

namespace App\Notifications;

use App\Models\RaceEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class RaceEventReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly RaceEvent $event) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Carrera mañana: '.$this->event->name)
            ->icon('/icons/icon.svg')
            ->body($this->event->location.' · '.$this->event->event_date->format('d/m/Y'))
            ->action('Ver carrera', route('events.show', $this->event))
            ->data(['url' => route('events.show', $this->event)]);
    }
}

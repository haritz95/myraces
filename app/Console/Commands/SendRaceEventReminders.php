<?php

namespace App\Console\Commands;

use App\Models\RaceEvent;
use App\Notifications\RaceEventReminderNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-race-event-reminders')]
#[Description('Send push notification reminders for race events happening tomorrow')]
class SendRaceEventReminders extends Command
{
    public function handle(): void
    {
        $tomorrow = now()->addDay()->startOfDay();

        $events = RaceEvent::with('attendees')
            ->whereDate('event_date', $tomorrow)
            ->whereIn('status', ['open', 'upcoming'])
            ->get();

        $sent = 0;

        foreach ($events as $event) {
            foreach ($event->attendees as $user) {
                $user->notify(new RaceEventReminderNotification($event));
                $sent++;
            }
        }

        $this->info("Reminders sent: {$sent}");
    }
}

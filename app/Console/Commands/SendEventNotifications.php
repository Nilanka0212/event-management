<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEventNotifications extends Command
{
    protected $signature = 'events:send-notifications';
    protected $description = 'Send notifications for events 7 days and 3 days before event date';

    public function handle()
    {
        $today = Carbon::today();

        // 7-day notifications
        $events7 = Event::with('customer')
            ->whereDate('event_date', $today->copy()->addDays(7))
            ->where('notification_7_sent', false)
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($events7 as $event) {
            Log::info("7-DAY NOTIFICATION: Event '{$event->event_name}' for customer '{$event->customer->name}' is on {$event->event_date->format('Y-m-d')}");
            $event->update(['notification_7_sent' => true]);
        }

        // 3-day notifications
        $events3 = Event::with('customer')
            ->whereDate('event_date', $today->copy()->addDays(3))
            ->where('notification_3_sent', false)
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($events3 as $event) {
            Log::info("3-DAY NOTIFICATION: Event '{$event->event_name}' for customer '{$event->customer->name}' is on {$event->event_date->format('Y-m-d')}");
            $event->update(['notification_3_sent' => true]);
        }

        $total = $events7->count() + $events3->count();
        $this->info("Sent {$total} notifications ({$events7->count()} 7-day, {$events3->count()} 3-day).");

        return Command::SUCCESS;
    }
}

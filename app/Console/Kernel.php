<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send booking reminder emails daily at 9 AM
        $schedule->command('bookings:send-reminders --days=3')
            ->dailyAt('09:00')
            ->description('Send booking reminders 3 days before check-in');

        // Also send 1 day reminder at 9 AM
        $schedule->command('bookings:send-reminders --days=1')
            ->dailyAt('09:00')
            ->description('Send booking reminders 1 day before check-in');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

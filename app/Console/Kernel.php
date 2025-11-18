<?php

namespace App\Console;

use App\Console\Commands\SendPolicyReminders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var array<class-string>
     */
    protected $commands = [
        SendPolicyReminders::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('policies:send-reminders')->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}


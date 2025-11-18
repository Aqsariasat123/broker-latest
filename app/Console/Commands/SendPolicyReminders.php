<?php

namespace App\Console\Commands;

use App\Models\PaymentPlan;
use App\Models\Policy;
use App\Notifications\PolicyReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendPolicyReminders extends Command
{
    protected $signature = 'policies:send-reminders {--days=30} {--payment-days=14}';

    protected $description = 'Dispatch email reminders for upcoming policy renewals and payment deadlines.';

    public function handle(): int
    {
        $renewalWindow = now()->addDays((int) $this->option('days'));
        $paymentWindow = now()->addDays((int) $this->option('payment-days'));

        $renewals = Policy::with('client')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), $renewalWindow])
            ->get();

        $paymentPlans = PaymentPlan::with(['schedule.policy.client'])
            ->where('status', 'pending')
            ->whereBetween('due_date', [now()->startOfDay(), $paymentWindow])
            ->get();

        if ($renewals->isEmpty() && $paymentPlans->isEmpty()) {
            $this->info('No renewals or payment deadlines within the configured window.');
            return Command::SUCCESS;
        }

        $recipient = config('mail.from.address');

        if (!$recipient) {
            $this->warn('No mail.from.address configured; skipping reminder dispatch.');
            return Command::SUCCESS;
        }

        Notification::route('mail', $recipient)
            ->notify(new PolicyReminderNotification($renewals, $paymentPlans));

        $this->info("Policy reminder email sent to {$recipient}.");

        return Command::SUCCESS;
    }
}


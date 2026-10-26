<?php

namespace App\Console\Commands;

use App\Services\ExpiryReminderService;
use Illuminate\Console\Command;

class SendExpiryReminders extends Command
{
    protected $signature = 'documents:send-expiry-reminders';

    protected $description = 'Send configured document expiry reminder emails without duplicates';

    public function handle(ExpiryReminderService $service): int
    {
        $sent = $service->sendForDate();
        $this->info(__('notifications.command.sent', ['count' => $sent]));

        return self::SUCCESS;
    }
}

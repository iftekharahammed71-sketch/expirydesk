<?php

namespace App\Services;

use App\Models\Company;
use App\Models\EmployeeDocument;
use App\Models\ReminderLog;
use App\Models\User;
use App\Notifications\DocumentExpiryReminder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class ExpiryReminderService
{
    public function sendForDate(?Carbon $today = null): int
    {
        $sent = 0;

        Company::query()->where('status', true)->with('reminderSettings')->each(function (Company $company) use ($today, &$sent): void {
            $companyToday = $today?->copy()->setTimezone($company->timezone) ?? Carbon::now($company->timezone)->startOfDay();

            foreach ($company->reminderSettings->where('is_enabled', true) as $setting) {
                $documents = EmployeeDocument::query()
                    ->with(['employee.branch', 'documentType'])
                    ->whereDate('expiry_date', $companyToday->copy()->addDays($setting->days_before))
                    ->whereHas('employee', fn ($query) => $query->where('company_id', $company->id))
                    ->whereDoesntHave('reminderLogs', fn ($query) => $query->where('lead_days', $setting->days_before)->where('status', 'sent'))
                    ->get();

                foreach ($documents as $document) {
                    $recipients = $this->recipients($document);

                    if ($recipients === []) {
                        continue;
                    }

                    try {
                        $log = ReminderLog::firstOrCreate([
                            'employee_document_id' => $document->id,
                            'lead_days' => $setting->days_before,
                        ], [
                            'recipient_emails' => $recipients,
                            'status' => 'sending',
                        ]);
                    } catch (QueryException) {
                        continue;
                    }

                    if ($log->status === 'sent') {
                        continue;
                    }

                    try {
                        Notification::route('mail', $recipients)->notify(new DocumentExpiryReminder($document, $setting->days_before));
                        $log->update([
                            'recipient_emails' => $recipients,
                            'sent_at' => now(),
                            'status' => 'sent',
                            'error_message' => null,
                        ]);
                        $sent++;
                    } catch (\Throwable $exception) {
                        $log->update([
                            'recipient_emails' => $recipients,
                            'status' => 'failed',
                            'error_message' => $exception->getMessage(),
                        ]);
                    }
                }
            }
        });

        return $sent;
    }

    private function recipients(EmployeeDocument $document): array
    {
        $employee = $document->employee;

        return User::query()
            ->where('is_active', true)
            ->where(function ($query) use ($employee): void {
                $query->where(function ($scope) use ($employee): void {
                    $scope->where('role', 'hr')
                        ->where('company_id', $employee->company_id)
                        ->where(function ($branch) use ($employee): void {
                            $branch->whereNull('branch_id')->orWhere('branch_id', $employee->branch_id);
                        });
                })->orWhere(function ($scope) use ($employee): void {
                    $scope->where('role', 'manager')
                        ->where('company_id', $employee->company_id)
                        ->where('branch_id', $employee->branch_id);
                });
            })
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}

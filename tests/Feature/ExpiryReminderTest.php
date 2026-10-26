<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Company;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\ReminderLog;
use App\Models\ReminderSetting;
use App\Models\User;
use App\Notifications\DocumentExpiryReminder;
use App\Services\ExpiryReminderService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ExpiryReminderTest extends TestCase
{
    public function test_a_lead_time_is_not_sent_twice(): void
    {
        Notification::fake();

        $company = Company::factory()->create();
        $branch = Branch::factory()->create(['company_id' => $company->id]);
        $hr = User::factory()->create(['company_id' => $company->id, 'role' => UserRole::HR]);
        User::factory()->create(['company_id' => $company->id, 'branch_id' => $branch->id, 'role' => UserRole::MANAGER]);
        $employee = Employee::factory()->create(['company_id' => $company->id, 'branch_id' => $branch->id]);
        $type = DocumentType::create(['name' => 'Visa', 'code' => 'visa-test', 'is_system' => false, 'is_active' => true, 'company_id' => $company->id]);
        ReminderSetting::create(['company_id' => $company->id, 'days_before' => 7, 'is_enabled' => true]);
        $document = EmployeeDocument::create([
            'employee_id' => $employee->id,
            'document_type_id' => $type->id,
            'expiry_date' => Carbon::today()->addDays(7),
            'file_path' => 'test/document.txt',
            'original_filename' => 'document.txt',
            'mime_type' => 'text/plain',
            'file_size' => 0,
            'uploaded_by' => $hr->id,
        ]);

        $service = app(ExpiryReminderService::class);
        $service->sendForDate(Carbon::today());
        $service->sendForDate(Carbon::today());

        Notification::assertSentOnDemand(DocumentExpiryReminder::class);
        $this->assertSame(1, ReminderLog::query()->where('employee_document_id', $document->id)->where('lead_days', 7)->where('status', 'sent')->count());
    }
}

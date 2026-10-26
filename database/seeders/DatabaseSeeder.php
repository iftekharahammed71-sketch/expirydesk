<?php

namespace Database\Seeders;

use App\Enums\EmployeeStatus;
use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\ReminderSetting;
use App\Models\User;
use App\Models\WhiteLabelSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('local')->put('demo/sample-document.txt', 'This placeholder file is included only for seeded demo records.');

        $company = Company::updateOrCreate(['slug' => 'acme-facilities'], [
            'name' => 'Acme Facilities Services',
            'status' => true,
            'timezone' => 'Asia/Dubai',
        ]);

        $branches = collect([
            ['name' => 'Dubai Operations', 'code' => 'DXB', 'address' => 'Dubai'],
            ['name' => 'Abu Dhabi Operations', 'code' => 'AUH', 'address' => 'Abu Dhabi'],
        ])->mapWithKeys(fn (array $branch) => [$branch['code'] => Branch::updateOrCreate(['company_id' => $company->id, 'code' => $branch['code']], [...$branch, 'is_active' => true])]);

        $departments = collect(['Human Resources', 'Operations', 'Finance', 'Facilities'])->mapWithKeys(fn (string $name) => [$name => Department::updateOrCreate(['company_id' => $company->id, 'name' => $name], ['is_active' => true])]);

        $types = collect([
            ['name' => 'Visa', 'code' => 'visa'],
            ['name' => 'Emirates ID', 'code' => 'emirates_id'],
            ['name' => 'Passport', 'code' => 'passport'],
            ['name' => 'Labour Card', 'code' => 'labour_card'],
            ['name' => 'Licence', 'code' => 'licence'],
            ['name' => 'Insurance', 'code' => 'insurance'],
        ])->map(fn (array $type) => DocumentType::updateOrCreate(['company_id' => null, 'code' => $type['code']], [...$type, 'is_system' => true, 'is_active' => true]));

        $company->whiteLabelSettings()->updateOrCreate([], [
            'company_display_name' => 'Acme Facilities Services',
            'brand_color' => '#2563eb',
            'mail_from_name' => 'Acme Facilities Services',
            'mail_from_address' => (string) config('mail.from.address'),
        ]);

        foreach ([90, 60, 30, 7] as $days) {
            ReminderSetting::updateOrCreate(['company_id' => $company->id, 'days_before' => $days], ['is_enabled' => true]);
        }

        $password = Hash::make((string) env('DEMO_PASSWORD', 'password'));
        $admin = User::updateOrCreate(['email' => (string) env('DEMO_ADMIN_EMAIL', 'admin@expirydesk.test')], [
            'name' => 'Demo Administrator',
            'company_id' => null,
            'branch_id' => null,
            'password' => $password,
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $hr = User::updateOrCreate(['email' => (string) env('DEMO_HR_EMAIL', 'hr@expirydesk.test')], [
            'name' => 'Demo HR Officer',
            'company_id' => $company->id,
            'branch_id' => null,
            'password' => $password,
            'role' => UserRole::HR,
            'is_active' => true,
        ]);

        $manager = User::updateOrCreate(['email' => (string) env('DEMO_MANAGER_EMAIL', 'manager@expirydesk.test')], [
            'name' => 'Demo Branch Manager',
            'company_id' => $company->id,
            'branch_id' => $branches['DXB']->id,
            'password' => $password,
            'role' => UserRole::MANAGER,
            'is_active' => true,
        ]);

        $employees = [
            ['employee_number' => 'EMP-1001', 'first_name' => 'Ahmed', 'last_name' => 'Khan', 'nationality' => 'India', 'job_title' => 'Site Supervisor', 'branch_id' => $branches['DXB']->id, 'department_id' => $departments['Operations']->id, 'expiry_days' => 7, 'type' => 'visa'],
            ['employee_number' => 'EMP-1002', 'first_name' => 'Maria', 'last_name' => 'Santos', 'nationality' => 'Philippines', 'job_title' => 'HR Coordinator', 'branch_id' => $branches['DXB']->id, 'department_id' => $departments['Human Resources']->id, 'expiry_days' => 25, 'type' => 'emirates_id'],
            ['employee_number' => 'EMP-1003', 'first_name' => 'Omar', 'last_name' => 'Hassan', 'nationality' => 'Egypt', 'job_title' => 'Facilities Manager', 'branch_id' => $branches['AUH']->id, 'department_id' => $departments['Facilities']->id, 'expiry_days' => 75, 'type' => 'licence'],
            ['employee_number' => 'EMP-1004', 'first_name' => 'Sanjay', 'last_name' => 'Patel', 'nationality' => 'India', 'job_title' => 'Technician', 'branch_id' => $branches['AUH']->id, 'department_id' => $departments['Operations']->id, 'expiry_days' => -10, 'type' => 'labour_card'],
        ];

        foreach ($employees as $employeeData) {
            $expiryDays = $employeeData['expiry_days'];
            $typeCode = $employeeData['type'];
            unset($employeeData['expiry_days'], $employeeData['type']);

            $employee = Employee::updateOrCreate(['company_id' => $company->id, 'employee_number' => $employeeData['employee_number']], [
                ...$employeeData,
                'company_id' => $company->id,
                'email' => strtolower($employeeData['first_name'].'.'.$employeeData['last_name']).'@example.test',
                'phone' => null,
                'status' => EmployeeStatus::ACTIVE,
            ]);

            $type = $types->firstWhere('code', $typeCode);
            $document = EmployeeDocument::updateOrCreate([
                'employee_id' => $employee->id,
                'document_type_id' => $type->id,
            ], [
                'document_number' => 'DEMO-'.$employee->employee_number,
                'issue_date' => Carbon::today()->subYear(),
                'expiry_date' => Carbon::today()->addDays($expiryDays),
                'file_path' => 'demo/sample-document.txt',
                'original_filename' => 'demo-document.txt',
                'mime_type' => 'text/plain',
                'file_size' => 0,
                'uploaded_by' => $hr->id,
            ]);
        }
    }
}

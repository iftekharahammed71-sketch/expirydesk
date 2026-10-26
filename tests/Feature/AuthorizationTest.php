<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    public function test_manager_cannot_view_an_employee_from_another_branch(): void
    {
        $company = Company::factory()->create();
        $branchA = Branch::factory()->create(['company_id' => $company->id]);
        $branchB = Branch::factory()->create(['company_id' => $company->id]);
        $department = Department::factory()->create(['company_id' => $company->id]);
        $manager = User::factory()->create(['company_id' => $company->id, 'branch_id' => $branchA->id, 'role' => UserRole::MANAGER]);
        $employee = Employee::factory()->create(['company_id' => $company->id, 'branch_id' => $branchB->id, 'department_id' => $department->id]);

        $this->actingAs($manager)->get(route('employees.show', $employee))->assertForbidden();
    }
}

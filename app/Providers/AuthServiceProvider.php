<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\BranchPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DocumentTypePolicy;
use App\Policies\EmployeeDocumentPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Company::class => CompanyPolicy::class,
        \App\Models\Branch::class => BranchPolicy::class,
        Department::class => DepartmentPolicy::class,
        DocumentType::class => DocumentTypePolicy::class,
        Employee::class => EmployeePolicy::class,
        EmployeeDocument::class => EmployeeDocumentPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

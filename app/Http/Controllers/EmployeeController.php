<?php

namespace App\Http\Controllers;

use App\Enums\EmployeeStatus;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Employee::class);

        $user = $request->user();
        $employees = Employee::query()
            ->with(['branch', 'department'])
            ->when(! $user->isAdmin(), fn (Builder $query) => $query->where('company_id', $user->company_id))
            ->when($user->isManager(), fn (Builder $query) => $query->where('branch_id', $user->branch_id))
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = $request->string('search')->trim()->value();
                $query->where(function (Builder $scope) use ($search): void {
                    $scope->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('branch_id'), fn (Builder $query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('department_id'), fn (Builder $query) => $query->where('department_id', $request->integer('department_id')))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(20)
            ->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'branches' => Branch::query()->when(! $user->isAdmin(), fn (Builder $query) => $query->where('company_id', $user->company_id))->when($user->isManager(), fn (Builder $query) => $query->whereKey($user->branch_id))->where('is_active', true)->orderBy('name')->get(),
            'departments' => Department::query()->when(! $user->isAdmin(), fn (Builder $query) => $query->where('company_id', $user->company_id))->when($user->isManager(), fn (Builder $query) => $query->whereHas('employees', fn (Builder $employee) => $employee->where('branch_id', $user->branch_id)))->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Employee::class);

        return view('employees.create', $this->formData($request));
    }

    public function store(EmployeeStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['company_id'] = $request->user()->isAdmin() ? (int) $data['company_id'] : $request->user()->company_id;

        $employee = Employee::create($data);

        return redirect()->route('employees.show', $employee)->with('success', __('employees.messages.created'));
    }

    public function show(Request $request, Employee $employee): View
    {
        $this->authorize('view', $employee);

        $employee->load(['branch', 'department', 'documents.documentType', 'documents.uploader']);

        return view('employees.show', [
            'employee' => $employee,
            'documentTypes' => DocumentType::query()->where('is_active', true)->where(function (Builder $query) use ($employee): void {
                $query->whereNull('company_id')->orWhere('company_id', $employee->company_id);
            })->orderBy('name')->get(),
        ]);
    }

    public function edit(Request $request, Employee $employee): View
    {
        $this->authorize('update', $employee);

        return view('employees.edit', array_merge(['employee' => $employee], $this->formData($request, $employee->company_id)));
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()->route('employees.show', $employee)->with('success', __('employees.messages.updated'));
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->authorize('delete', $employee);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', __('employees.messages.deleted'));
    }

    private function formData(Request $request, ?int $companyId = null): array
    {
        $user = $request->user();
        $companyId ??= $user->isAdmin() ? null : $user->company_id;

        return [
            'branches' => Branch::query()->when($companyId, fn (Builder $query) => $query->where('company_id', $companyId))->where('is_active', true)->orderBy('name')->get(),
            'departments' => Department::query()->when($companyId, fn (Builder $query) => $query->where('company_id', $companyId))->where('is_active', true)->orderBy('name')->get(),
            'statuses' => EmployeeStatus::cases(),
            'companies' => $user->isAdmin() ? \App\Models\Company::query()->where('status', true)->orderBy('name')->get() : collect(),
        ];
    }
}

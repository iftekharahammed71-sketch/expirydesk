<?php

namespace App\Livewire;

use App\Enums\DocumentStatus;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\EmployeeDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $companyId = '';

    public string $branchId = '';

    public string $departmentId = '';

    public string $documentTypeId = '';

    public string $status = '';

    public function updated(string $property): void
    {
        if (in_array($property, ['companyId', 'branchId', 'departmentId', 'documentTypeId', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['companyId', 'branchId', 'departmentId', 'documentTypeId', 'status']);
        $this->resetPage();
    }

    public function render(): View
    {
        $user = auth()->user();
        $query = EmployeeDocument::query()
            ->with(['employee.branch', 'employee.department', 'documentType'])
            ->whereHas('employee', function (Builder $employeeQuery) use ($user): void {
                if (! $user->isAdmin()) {
                    $employeeQuery->where('company_id', $user->company_id);
                }

                if ($user->isManager()) {
                    $employeeQuery->where('branch_id', $user->branch_id);
                }
            });

        $this->applyFilters($query);

        $documents = (clone $query)->orderBy('expiry_date')->paginate(15);

        return view('livewire.dashboard', [
            'documents' => $documents,
            'expiredCount' => (clone $query)->whereDate('expiry_date', '<', today())->count(),
            'expiringSoonCount' => (clone $query)->whereBetween('expiry_date', [today(), today()->addDays(30)])->count(),
            'employeeCount' => (clone $query)->distinct('employee_id')->count('employee_id'),
            'companies' => $user->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
            'branches' => Branch::query()->when(! $user->isAdmin(), fn (Builder $q) => $q->where('company_id', $user->company_id))->when($user->isManager(), fn (Builder $q) => $q->whereKey($user->branch_id))->where('is_active', true)->orderBy('name')->get(),
            'departments' => Department::query()->when(! $user->isAdmin(), fn (Builder $q) => $q->where('company_id', $user->company_id))->where('is_active', true)->orderBy('name')->get(),
            'documentTypes' => DocumentType::query()->where('is_active', true)->where(function (Builder $q) use ($user): void {
                $q->whereNull('company_id');

                if (! $user->isAdmin()) {
                    $q->orWhere('company_id', $user->company_id);
                } else {
                    $q->orWhereNotNull('company_id');
                }
            })->orderBy('name')->get(),
            'statuses' => DocumentStatus::cases(),
        ])->layout('layouts.app', ['heading' => __('dashboard.title')]);
    }

    private function applyFilters(Builder $query): void
    {
        $query->when($this->companyId !== '', fn (Builder $q) => $q->whereHas('employee', fn (Builder $employee) => $employee->where('company_id', (int) $this->companyId)));
        $query->when($this->branchId !== '', fn (Builder $q) => $q->whereHas('employee', fn (Builder $employee) => $employee->where('branch_id', (int) $this->branchId)));
        $query->when($this->departmentId !== '', fn (Builder $q) => $q->whereHas('employee', fn (Builder $employee) => $employee->where('department_id', (int) $this->departmentId)));
        $query->when($this->documentTypeId !== '', fn (Builder $q) => $q->where('document_type_id', (int) $this->documentTypeId));

        if ($this->status !== '') {
            match ($this->status) {
                DocumentStatus::EXPIRED->value => $query->whereDate('expiry_date', '<', today()),
                DocumentStatus::EXPIRING_SOON->value => $query->whereBetween('expiry_date', [today(), today()->addDays(30)]),
                DocumentStatus::VALID->value => $query->whereDate('expiry_date', '>', today()->addDays(30)),
                default => null,
            };
        }
    }
}

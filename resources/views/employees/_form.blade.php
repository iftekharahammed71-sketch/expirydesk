@csrf
<div class="grid gap-5 md:grid-cols-2">
    @if(auth()->user()->isAdmin() && isset($companies))<x-select-field name="company_id" :label="__('ui.companies')" :options="$companies->pluck('name', 'id')" :value="$employee->company_id ?? request('company_id')" required />@endif
    <x-select-field name="branch_id" :label="__('employees.branch')" :options="$branches->pluck('name', 'id')" :value="$employee->branch_id ?? null" required />
    <x-select-field name="department_id" :label="__('employees.department')" :options="$departments->pluck('name', 'id')" :value="$employee->department_id ?? null" :placeholder="__('ui.no')" />
    <x-form-field name="employee_number" :label="__('employees.employee_number')" :value="$employee->employee_number ?? null" required />
    <x-form-field name="first_name" :label="__('employees.first_name')" :value="$employee->first_name ?? null" required />
    <x-form-field name="last_name" :label="__('employees.last_name')" :value="$employee->last_name ?? null" required />
    <x-form-field name="email" :label="__('employees.email')" type="email" :value="$employee->email ?? null" />
    <x-form-field name="phone" :label="__('employees.phone')" :value="$employee->phone ?? null" />
    <x-form-field name="nationality" :label="__('employees.nationality')" :value="$employee->nationality ?? null" />
    <x-form-field name="job_title" :label="__('employees.job_title')" :value="$employee->job_title ?? null" />
    <x-select-field name="status" :label="__('employees.status')" :options="collect($statuses)->mapWithKeys(fn ($status) => [$status->value => $status->label()])" :value="$employee->status?->value ?? 'active'" required />
</div>
<div class="mt-7 flex justify-end gap-3"><a href="{{ isset($employee) ? route('employees.show', $employee) : route('employees.index') }}" class="btn-secondary rounded-xl px-4 py-2.5 text-sm font-bold">{{ __('ui.cancel') }}</a><button class="btn-primary rounded-xl px-4 py-2.5 text-sm font-bold">{{ __('ui.save') }}</button></div>

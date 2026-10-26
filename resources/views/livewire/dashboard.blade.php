<div>
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm text-slate-500">{{ __('dashboard.welcome', ['name' => auth()->user()->name]) }}</p><p class="mt-1 text-sm text-slate-500">{{ now()->format('l, d M Y') }}</p></div>
        @can('create', App\Models\Employee::class)<a href="{{ route('employees.create') }}" class="btn-primary inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-bold">+ {{ __('employees.title') }}</a>@endcan
    </div>
    <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card p-5"><p class="text-sm font-semibold text-slate-500">{{ __('dashboard.expired_documents') }}</p><p class="mt-3 text-3xl font-black text-red-600">{{ $expiredCount }}</p><p class="mt-1 text-xs text-slate-400">{{ __('documents.statuses.expired') }}</p></div>
        <div class="card p-5"><p class="text-sm font-semibold text-slate-500">{{ __('dashboard.expiring_soon') }}</p><p class="mt-3 text-3xl font-black text-amber-600">{{ $expiringSoonCount }}</p><p class="mt-1 text-xs text-slate-400">{{ __('dashboard.upcoming_expiries') }}</p></div>
        <div class="card p-5"><p class="text-sm font-semibold text-slate-500">{{ __('ui.employees') }}</p><p class="mt-3 text-3xl font-black text-slate-900">{{ $employeeCount }}</p><p class="mt-1 text-xs text-slate-400">{{ __('employees.statuses.active') }}</p></div>
        <div class="card p-5"><p class="text-sm font-semibold text-slate-500">{{ __('dashboard.title') }}</p><p class="mt-3 text-3xl font-black text-blue-700">{{ $documents->total() }}</p><p class="mt-1 text-xs text-slate-400">{{ __('documents.title') }}</p></div>
    </div>

    <div class="card mt-7 p-5 sm:p-6">
        <div class="flex items-center justify-between gap-3"><div><h2 class="text-base font-bold text-slate-900">{{ __('dashboard.filters') }}</h2><p class="mt-1 text-xs text-slate-500">{{ __('dashboard.upcoming_expiries') }}</p></div><button type="button" wire:click="clearFilters" class="text-sm font-semibold text-slate-500 hover:text-slate-900">{{ __('dashboard.clear_filters') }}</button></div>
        <div class="mt-5 grid gap-4 md:grid-cols-2 lg:grid-cols-5">
            @if(auth()->user()->isAdmin())<x-select-field name="companyId" :label="__('ui.companies')" :options="$companies->pluck('name', 'id')" :value="$companyId" :placeholder="__('dashboard.all_companies')" wire:model.live="companyId" />@endif
            <x-select-field name="branchId" :label="__('dashboard.branch')" :options="$branches->pluck('name', 'id')" :value="$branchId" :placeholder="__('dashboard.all_branches')" wire:model.live="branchId" />
            <x-select-field name="departmentId" :label="__('dashboard.all_departments')" :options="$departments->pluck('name', 'id')" :value="$departmentId" :placeholder="__('dashboard.all_departments')" wire:model.live="departmentId" />
            <x-select-field name="documentTypeId" :label="__('dashboard.document_type')" :options="$documentTypes->pluck('name', 'id')" :value="$documentTypeId" :placeholder="__('dashboard.all_document_types')" wire:model.live="documentTypeId" />
            <x-select-field name="status" :label="__('dashboard.status')" :options="collect($statuses)->mapWithKeys(fn ($status) => [$status->value => $status->label()])" :value="$status" :placeholder="__('dashboard.all_statuses')" wire:model.live="status" />
        </div>
    </div>

    <div class="card mt-7 overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full min-w-[720px] text-sm"><thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="table-cell">{{ __('dashboard.employee') }}</th><th class="table-cell">{{ __('dashboard.document_type') }}</th><th class="table-cell">{{ __('dashboard.branch') }}</th><th class="table-cell">{{ __('dashboard.expiry_date') }}</th><th class="table-cell">{{ __('dashboard.status') }}</th><th class="table-cell"></th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($documents as $document)<tr class="hover:bg-slate-50"><td class="table-cell"><p class="font-semibold text-slate-900">{{ $document->employee->full_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $document->employee->employee_number }}</p></td><td class="table-cell text-slate-700">{{ $document->documentType->name }}</td><td class="table-cell text-slate-600">{{ $document->employee->branch->name }}</td><td class="table-cell font-medium text-slate-700">{{ $document->expiry_date->format('d M Y') }}</td><td class="table-cell"><span class="badge {{ $document->status_badge_class }}">{{ $document->status_label }}</span></td><td class="table-cell text-right"><a href="{{ route('employees.show', $document->employee) }}" class="font-semibold text-blue-700 hover:underline">{{ __('ui.view') }}</a></td></tr>@empty<tr><td colspan="6" class="px-6 py-14 text-center text-sm text-slate-500">{{ __('dashboard.no_documents') }}</td></tr>@endforelse</tbody></table></div>
        @if($documents->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $documents->links() }}</div>@endif
    </div>
</div>

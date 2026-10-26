@extends('layouts.app')
@section('title', __('companies.title'))
@section('heading', __('companies.title'))
@section('content')
<x-simple-index :title="__('companies.title')" :description="__('companies.name')" create-route="companies.create" :headers="[__('companies.name'), __('companies.timezone'), __('companies.branches'), __('companies.employees'), __('companies.status'), __('ui.actions')]" :rows="[]">
    @forelse($companies as $company)
        <tr class="hover:bg-slate-50">
            <td class="table-cell"><p class="font-semibold text-slate-900">{{ $company->name }}</p><p class="mt-1 text-xs text-slate-500">{{ $company->slug }}</p></td>
            <td class="table-cell text-slate-600">{{ $company->timezone }}</td>
            <td class="table-cell text-slate-600">{{ $company->branches_count }}</td>
            <td class="table-cell text-slate-600">{{ $company->employees_count }}</td>
            <td class="table-cell"><span class="badge {{ $company->status ? 'badge-success' : 'badge-danger' }}">{{ $company->status ? __('ui.active') : __('ui.inactive') }}</span></td>
            <td class="table-cell text-right"><a href="{{ route('companies.edit', $company) }}" class="font-semibold text-blue-700">{{ __('ui.edit') }}</a> <a href="{{ route('settings.white-label.edit', $company) }}" class="ml-3 font-semibold text-slate-600">{{ __('ui.white_label') }}</a> <a href="{{ route('settings.reminders.edit', $company) }}" class="ml-3 font-semibold text-slate-600">{{ __('ui.reminders') }}</a></td>
        </tr>
    @empty
        <tr><td colspan="6" class="px-6 py-14 text-center text-slate-500">{{ __('dashboard.no_documents') }}</td></tr>
    @endforelse
</x-simple-index>
@endsection

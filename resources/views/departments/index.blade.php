@extends('layouts.app')
@section('title', __('departments.title'))
@section('heading', __('departments.title'))
@section('content')
<x-simple-index :title="__('departments.title')" :description="__('departments.name')" create-route="departments.create" :headers="[__('departments.name'), __('departments.company'), __('ui.status'), __('ui.actions')]" :rows="[]">
    @forelse($departments as $department)
        <tr class="hover:bg-slate-50"><td class="table-cell font-semibold text-slate-900">{{ $department->name }}</td><td class="table-cell text-slate-600">{{ $department->company->name }}</td><td class="table-cell"><span class="badge {{ $department->is_active ? 'badge-success' : 'badge-danger' }}">{{ $department->is_active ? __('ui.active') : __('ui.inactive') }}</span></td><td class="table-cell text-right"><a href="{{ route('departments.edit', $department) }}" class="font-semibold text-blue-700">{{ __('ui.edit') }}</a></td></tr>
    @empty
        <tr><td colspan="4" class="px-6 py-14 text-center text-slate-500">{{ __('dashboard.no_documents') }}</td></tr>
    @endforelse
</x-simple-index>
@endsection

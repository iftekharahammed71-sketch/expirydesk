@extends('layouts.app')
@section('title', __('branches.title'))
@section('heading', __('branches.title'))
@section('content')
<x-simple-index :title="__('branches.title')" :description="__('branches.name')" create-route="branches.create" :headers="[__('branches.name'), __('branches.code'), __('branches.company'), __('ui.status'), __('ui.actions')]" :rows="[]">
    @forelse($branches as $branch)
        <tr class="hover:bg-slate-50"><td class="table-cell font-semibold text-slate-900">{{ $branch->name }}</td><td class="table-cell text-slate-600">{{ $branch->code }}</td><td class="table-cell text-slate-600">{{ $branch->company->name }}</td><td class="table-cell"><span class="badge {{ $branch->is_active ? 'badge-success' : 'badge-danger' }}">{{ $branch->is_active ? __('ui.active') : __('ui.inactive') }}</span></td><td class="table-cell text-right"><a href="{{ route('branches.edit', $branch) }}" class="font-semibold text-blue-700">{{ __('ui.edit') }}</a></td></tr>
    @empty
        <tr><td colspan="5" class="px-6 py-14 text-center text-slate-500">{{ __('dashboard.no_documents') }}</td></tr>
    @endforelse
</x-simple-index>
@endsection

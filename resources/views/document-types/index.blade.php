@extends('layouts.app')
@section('title', __('document_types.title'))
@section('heading', __('document_types.title'))
@section('content')
<x-simple-index :title="__('document_types.title')" :description="__('document_types.name')" create-route="document-types.create" :headers="[__('document_types.name'), __('document_types.code'), __('document_types.company'), __('document_types.system'), __('ui.status'), __('ui.actions')]" :rows="[]">
    @forelse($documentTypes as $type)
        <tr class="hover:bg-slate-50"><td class="table-cell font-semibold text-slate-900">{{ $type->name }}</td><td class="table-cell text-slate-600">{{ $type->code }}</td><td class="table-cell text-slate-600">{{ $type->company?->name ?: __('ui.app_name') }}</td><td class="table-cell">{{ $type->is_system ? __('ui.yes') : __('ui.no') }}</td><td class="table-cell"><span class="badge {{ $type->is_active ? 'badge-success' : 'badge-danger' }}">{{ $type->is_active ? __('ui.active') : __('ui.inactive') }}</span></td><td class="table-cell text-right">@if(!$type->is_system)<a href="{{ route('document-types.edit', $type) }}" class="font-semibold text-blue-700">{{ __('ui.edit') }}</a>@endif</td></tr>
    @empty
        <tr><td colspan="6" class="px-6 py-14 text-center text-slate-500">{{ __('dashboard.no_documents') }}</td></tr>
    @endforelse
</x-simple-index>
@endsection

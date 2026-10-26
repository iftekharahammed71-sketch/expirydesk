@extends('layouts.app')
@section('title', __('users.title'))
@section('heading', __('users.title'))
@section('content')
<x-simple-index :title="__('users.title')" :description="__('users.email')" create-route="users.create" :headers="[__('users.name'), __('users.email'), __('users.role'), __('users.company'), __('users.branch'), __('ui.status'), __('ui.actions')]" :rows="[]">
    @forelse($users as $user)
        <tr class="hover:bg-slate-50"><td class="table-cell font-semibold text-slate-900">{{ $user->name }}</td><td class="table-cell text-slate-600">{{ $user->email }}</td><td class="table-cell"><span class="badge badge-success">{{ $user->role->label() }}</span></td><td class="table-cell text-slate-600">{{ $user->company?->name ?: __('ui.app_name') }}</td><td class="table-cell text-slate-600">{{ $user->branch?->name ?: '—' }}</td><td class="table-cell"><span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">{{ $user->is_active ? __('ui.active') : __('ui.inactive') }}</span></td><td class="table-cell text-right"><a href="{{ route('users.edit', $user) }}" class="font-semibold text-blue-700">{{ __('ui.edit') }}</a></td></tr>
    @empty
        <tr><td colspan="7" class="px-6 py-14 text-center text-slate-500">{{ __('dashboard.no_documents') }}</td></tr>
    @endforelse
</x-simple-index>
@endsection

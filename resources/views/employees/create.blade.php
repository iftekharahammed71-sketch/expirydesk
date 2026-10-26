@extends('layouts.app')
@section('title', __('employees.title'))
@section('heading', __('employees.title'))
@section('content')<div class="mx-auto max-w-4xl"><div class="mb-7"><a href="{{ route('employees.index') }}" class="text-sm font-semibold text-blue-700">← {{ __('ui.back') }}</a><h2 class="mt-3 text-2xl font-black text-slate-900">{{ __('ui.create') }} {{ __('employees.employee') }}</h2></div><div class="card p-5 sm:p-7"><form method="POST" action="{{ route('employees.store') }}">@include('employees._form')</form></div></div>@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $brandSettings?->company_display_name ?? config('app.name') }} · @yield('title', $heading ?? __('ui.dashboard'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>:root { --brand-color: {{ $brandSettings?->brand_color ?? '#2563eb' }}; }</style>
</head>
<body class="min-h-screen antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="border-b border-slate-200 bg-white lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between px-6 py-5 lg:block">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-xl font-extrabold tracking-tight text-slate-900">
                    @if($brandSettings?->logo_path)
                        <img src="{{ route('companies.logo', $brandSettings->company_id) }}" alt="{{ $brandSettings->company_display_name }}" class="h-9 w-9 rounded-lg object-cover">
                    @else
                        <span class="grid h-9 w-9 place-items-center rounded-lg text-sm font-black text-white" style="background: var(--brand-color)">ED</span>
                    @endif
                    <span>{{ $brandSettings?->company_display_name ?? __('ui.app_name') }}</span>
                </a>
            </div>
            <nav class="flex gap-1 overflow-x-auto px-4 pb-4 lg:block lg:space-y-1 lg:px-4 lg:pb-0">
                @include('layouts.navigation-link', ['route' => 'dashboard', 'label' => __('ui.dashboard'), 'icon' => '◈'])
                @include('layouts.navigation-link', ['route' => 'employees.index', 'label' => __('ui.employees'), 'icon' => '◉'])
                @if(auth()->user()->isAdmin())
                    @include('layouts.navigation-link', ['route' => 'users.index', 'label' => __('ui.users'), 'icon' => '●'])
                    @include('layouts.navigation-link', ['route' => 'branches.index', 'label' => __('ui.branches'), 'icon' => '⌂'])
                    @include('layouts.navigation-link', ['route' => 'departments.index', 'label' => __('ui.departments'), 'icon' => '▦'])
                    @include('layouts.navigation-link', ['route' => 'document-types.index', 'label' => __('ui.document_types'), 'icon' => '▤'])
                @endif
                @if(auth()->user()->isAdmin())
                    @include('layouts.navigation-link', ['route' => 'companies.index', 'label' => __('ui.companies'), 'icon' => '▣'])
                @endif
            </nav>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="flex items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-4 sm:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ __('ui.app_name') }}</p>
                    <h1 class="mt-1 text-xl font-bold text-slate-900">@yield('heading', $heading ?? __('ui.dashboard'))</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="hidden text-right sm:block">
                        <span class="block text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                        <span class="block text-xs text-slate-500">{{ auth()->user()->role->label() }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn-secondary rounded-xl px-3 py-2 text-sm font-semibold" type="submit">{{ __('ui.sign_out') }}</button>
                    </form>
                </div>
            </header>

            <div class="mx-auto max-w-7xl p-5 sm:p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                        <p class="font-semibold">{{ __('ui.error_heading') }}</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>

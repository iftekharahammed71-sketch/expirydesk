<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.forgot_password') }} · {{ __('ui.app_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-slate-100 p-5">
    <main class="card w-full max-w-md p-7 sm:p-9">
        <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-700">← {{ __('ui.back') }}</a>
        <h1 class="mt-6 text-2xl font-bold text-slate-900">{{ __('auth.reset_heading') }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ __('auth.reset_description') }}</p>
        @if(session('status'))<div class="mt-5 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</div>@endif
        <form method="POST" action="{{ route('password.email') }}" class="mt-7 space-y-5">
            @csrf
            <x-form-field name="email" :label="__('ui.email')" type="email" required autofocus />
            <button type="submit" class="btn-primary w-full rounded-xl px-4 py-3 text-sm font-bold">{{ __('ui.send_reset_link') }}</button>
        </form>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.reset_heading') }} · {{ __('ui.app_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-slate-100 p-5">
    <main class="card w-full max-w-md p-7 sm:p-9">
        <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-700">← {{ __('ui.back') }}</a>
        <h1 class="mt-6 text-2xl font-bold text-slate-900">{{ __('auth.reset_heading') }}</h1>
        <form method="POST" action="{{ route('password.store') }}" class="mt-7 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <x-form-field name="email" :label="__('ui.email')" type="email" :value="$email" required autofocus />
            <x-form-field name="password" :label="__('ui.password')" type="password" required />
            <x-form-field name="password_confirmation" :label="__('users.password_confirmation')" type="password" required />
            <button type="submit" class="btn-primary w-full rounded-xl px-4 py-3 text-sm font-bold">{{ __('auth.reset_password') }}</button>
        </form>
    </main>
</body>
</html>

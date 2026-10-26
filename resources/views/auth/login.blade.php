<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.sign_in') }} · {{ __('ui.app_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
    <main class="mx-auto grid min-h-screen max-w-6xl items-center gap-10 p-5 lg:grid-cols-2 lg:p-10">
        <section class="hidden lg:block">
            <div class="max-w-lg">
                <div class="mb-7 flex items-center gap-3">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-600 font-black text-white">ED</span>
                    <span class="text-2xl font-extrabold text-slate-900">{{ __('ui.app_name') }}</span>
                </div>
                <h1 class="text-5xl font-black leading-tight tracking-tight text-slate-950">{{ __('auth.login_headline') }}</h1>
                <p class="mt-5 text-lg leading-8 text-slate-600">{{ __('auth.login_description') }}</p>
                <div class="mt-8 grid gap-3 text-sm text-slate-600">
                    @foreach(['auth.login_feature_one', 'auth.login_feature_two', 'auth.login_feature_three'] as $feature)
                        <div class="flex items-center gap-3"><span class="grid h-7 w-7 place-items-center rounded-full bg-emerald-100 text-emerald-700">✓</span>{{ __($feature) }}</div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="card mx-auto w-full max-w-md p-7 sm:p-9">
            <div class="mb-7 lg:hidden"><span class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-600 font-black text-white">ED</span></div>
            <h2 class="text-2xl font-bold text-slate-900">{{ __('auth.welcome_back') }}</h2>
            <p class="mt-2 text-sm text-slate-500">{{ __('auth.login_prompt') }}</p>
            @if(session('status'))<div class="mt-5 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</div>@endif
            @if($errors->any())<div class="mt-5 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                @csrf
                <x-form-field name="email" :label="__('ui.email')" type="email" required autofocus />
                <div>
                    <div class="flex items-center justify-between"><label for="password" class="form-label">{{ __('ui.password') }}</label><a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-700 hover:underline">{{ __('ui.forgot_password') }}</a></div>
                    <input id="password" name="password" type="password" required class="form-input">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-blue-600"> {{ __('ui.remember_me') }}</label>
                <button type="submit" class="btn-primary w-full rounded-xl px-4 py-3 text-sm font-bold shadow-sm">{{ __('ui.sign_in') }}</button>
            </form>
            <div class="mt-7 border-t border-slate-200 pt-6">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('auth.demo_credentials') }}</p>
                <div class="mt-3 space-y-2">
                    @foreach($demoCredentials as $credential)
                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-xs">
                            <span class="font-semibold text-slate-700">{{ __("roles.{$credential['role']}") }}</span>
                            <span class="text-slate-500">{{ $credential['email'] }} / {{ $credential['password'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</body>
</html>

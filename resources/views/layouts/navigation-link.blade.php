@php($isActive = request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route)))
<a href="{{ route($route) }}" class="flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition lg:w-full {{ $isActive ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
    <span class="grid h-7 w-7 place-items-center rounded-lg {{ $isActive ? 'bg-blue-100' : 'bg-slate-100' }}">{{ $icon }}</span>
    <span>{{ $label }}</span>
</a>

@php
    $navItems = [
        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ['route' => 'admin.users', 'match' => 'admin.users*', 'label' => 'Users', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['route' => 'admin.subscriptions', 'match' => 'admin.subscriptions', 'label' => 'Subscriptions', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        ['route' => 'admin.plans.index', 'match' => 'admin.plans.*', 'label' => 'Plans', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
    ];
@endphp

<div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 px-5 dark:border-slate-800">
    <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 flex-1 items-center gap-3">
        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/30">A</span>
        <div class="min-w-0">
            <p class="panel-sidebar-brand truncate text-sm font-bold">AI Resume Builder</p>
            <p class="panel-sidebar-sub text-[11px] font-medium">Admin Console</p>
        </div>
    </a>
    @if($mobile ?? false)
        <button type="button" @click="closeSidebar()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>

<nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5" @click="if ($event.target.closest('a')) closeSidebar()">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Menu</p>
    @foreach ($navItems as $item)
        <a href="{{ route($item['route']) }}"
           class="admin-sidebar-link {{ request()->routeIs($item['match']) ? 'admin-sidebar-link-active' : '' }}">
            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
            </svg>
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>

<div class="shrink-0 space-y-1 border-t border-slate-200 px-4 py-4 dark:border-slate-800">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Account</p>
    <a href="{{ route('dashboard') }}" class="admin-sidebar-link">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        User Dashboard
    </a>
    <a href="{{ route('home') }}" class="admin-sidebar-link">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Website
    </a>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="admin-sidebar-link w-full text-left text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-950/40 dark:hover:text-red-300">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </button>
    </form>
</div>

<div class="shrink-0 space-y-3 border-t border-slate-200 p-4 dark:border-slate-800">
    <div class="flex justify-center lg:hidden">
        @include('partials.theme-toggle')
    </div>
    <div class="panel-sidebar-profile flex items-center gap-3">
        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </span>
        <div class="min-w-0 flex-1">
            <p class="panel-sidebar-brand truncate text-sm font-semibold">{{ Auth::user()->name }}</p>
            <p class="panel-sidebar-sub truncate text-xs">{{ Auth::user()->email }}</p>
        </div>
    </div>
</div>

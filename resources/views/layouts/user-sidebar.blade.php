@php
    $navItems = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ['route' => 'themes.index', 'match' => 'themes.*', 'label' => 'Themes', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
        ['route' => 'resumes.index', 'match' => 'resumes.*', 'label' => 'My Resumes', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['route' => 'ats.index', 'match' => 'ats.*', 'label' => 'ATS Checker', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['route' => 'plans.index', 'match' => 'plans.*', 'label' => 'Plans & Pricing', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['route' => 'profile.edit', 'match' => 'profile.*', 'label' => 'Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
    $currentPlan = Auth::user()->currentPlan();
@endphp

<div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 px-5 dark:border-slate-800">
    <a href="{{ route('dashboard') }}" class="flex min-w-0 flex-1 items-center gap-3">
        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-500/30">A</span>
        <div class="min-w-0">
            <p class="panel-sidebar-brand truncate text-sm font-bold">AI Resume Builder</p>
            <p class="panel-sidebar-sub text-[11px] font-medium">My Workspace</p>
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

    @if (Auth::user()->isAdmin())
        <p class="mb-2 mt-5 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Admin</p>
        <a href="{{ route('admin.dashboard') }}"
           class="admin-sidebar-link {{ request()->routeIs('admin.*') ? 'admin-sidebar-link-active' : '' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Admin Panel
        </a>
    @endif
</nav>

<div class="shrink-0 space-y-1 border-t border-slate-200 px-4 py-4 dark:border-slate-800">
    <a href="{{ route('home') }}" class="admin-sidebar-link">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Website
    </a>
    <form method="POST" action="{{ route('logout') }}">
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
    <div class="panel-sidebar-profile">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-white">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="panel-sidebar-brand truncate text-sm font-semibold">{{ Auth::user()->name }}</p>
                <p class="panel-sidebar-sub truncate text-xs">{{ Auth::user()->email }}</p>
            </div>
        </div>
        @if ($currentPlan)
            <div class="mt-3 flex items-center justify-between rounded-xl bg-white/80 px-3 py-2 dark:bg-slate-800/80">
                <span class="panel-sidebar-sub text-xs font-medium">Plan</span>
                <span class="admin-badge bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300">{{ $currentPlan->name }}</span>
            </div>
        @else
            <a href="{{ route('plans.index') }}" class="mt-3 block rounded-xl bg-indigo-600 px-3 py-2 text-center text-xs font-semibold text-white hover:bg-indigo-500">
                Subscribe to a plan
            </a>
        @endif
    </div>
</div>

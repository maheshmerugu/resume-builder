<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Resume Builder') }}</title>
    @include('partials.favicon')
    @include('partials.theme-init')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 font-sans text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100"
      x-data="appShell"
      @keydown.escape.window="closeSidebar()">

    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="closeSidebar()"
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm dark:bg-black/60"
         style="display:none;"></div>

    <aside class="fixed inset-y-0 left-0 z-50 flex w-[270px] flex-col border-r border-slate-200 bg-white shadow-2xl transition-transform duration-300 ease-out dark:border-slate-800 dark:bg-slate-900 sm:w-64"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        @include('layouts.user-sidebar', ['mobile' => true])
    </aside>

    <div class="flex min-h-screen flex-col">
        <header class="sticky top-0 z-[60] border-b border-slate-200 bg-white/90 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/90">
            <div class="flex h-16 items-center gap-3 px-4 sm:gap-4 sm:px-6 lg:px-8">
                <button type="button"
                        @click.stop="toggleSidebar()"
                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-indigo-500/50 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300"
                        :aria-expanded="sidebarOpen"
                        aria-label="Toggle menu">
                    <svg x-show="!sidebarOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="sidebarOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="panel-shell min-w-0 flex-1">
                    @isset($header) {{ $header }} @else <h1 class="text-lg font-bold panel-title">Dashboard</h1> @endisset
                </div>
                @include('partials.theme-toggle')
                <a href="{{ route('resumes.from-jd.create') }}" class="admin-btn-secondary hidden sm:inline-flex">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    From JD
                </a>
                <a href="{{ route('resumes.create') }}" class="admin-btn-primary hidden sm:inline-flex">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Resume
                </a>
            </div>
        </header>

        <main class="panel-shell flex-1 px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">{{ $slot }}</div>
        </main>
    </div>
</body>
</html>

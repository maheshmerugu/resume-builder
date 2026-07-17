<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta')
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.seo-schema')
</head>
<body class="bg-white font-sans text-slate-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white shadow-md shadow-indigo-600/30">A</span>
                <span class="text-lg font-bold tracking-tight text-slate-900">AI Resume Builder</span>
            </a>
            <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
                <a href="{{ route('home') }}" class="transition hover:text-indigo-600">Home</a>
                <a href="{{ route('blog.index') }}" class="transition hover:text-indigo-600">Blog</a>
                <a href="{{ route('home') }}#pricing" class="transition hover:text-indigo-600">Pricing</a>
            </nav>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="landing-btn-primary !px-5 !py-2.5">Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="landing-btn-primary !px-5 !py-2.5">Start free</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="flex flex-col items-center gap-1 sm:items-start">
                    <p class="text-sm text-slate-500">&copy; {{ date('Y') }} AI Resume Builder. All rights reserved.</p>
                    <p class="text-xs text-slate-400">{{ number_format($pageVisits ?? 0) }} page visits</p>
                </div>
                <div class="flex gap-6 text-sm text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-indigo-600">Blog</a>
                    <a href="{{ route('register') }}" class="hover:text-indigo-600">Sign up free</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

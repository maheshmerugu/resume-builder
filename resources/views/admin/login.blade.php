<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — {{ config('app.name', 'AI Resume Builder') }}</title>
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        {{-- Left panel — admin branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white flex-col justify-between p-12 overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <svg class="w-full h-full" viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg">
                    <rect x="100" y="100" width="600" height="600" fill="none" stroke="white" stroke-width="1" rx="20"/>
                    <rect x="200" y="200" width="400" height="400" fill="none" stroke="white" stroke-width="1" rx="15"/>
                    <rect x="300" y="300" width="200" height="200" fill="none" stroke="white" stroke-width="1" rx="10"/>
                    <line x1="100" y1="400" x2="700" y2="400" stroke="white" stroke-width="0.5"/>
                    <line x1="400" y1="100" x2="400" y2="700" stroke="white" stroke-width="0.5"/>
                </svg>
            </div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 backdrop-blur border border-white/20 text-lg font-bold">A</span>
                    <span class="text-2xl font-extrabold tracking-tight">AI Resume Builder</span>
                </a>
            </div>

            <div class="relative z-10 space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 border border-amber-500/30 px-4 py-1.5 text-sm font-medium text-amber-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Admin Access Only
                </div>
                <h2 class="text-4xl font-extrabold leading-tight">Administration<br>Panel</h2>
                <p class="text-lg text-gray-400 max-w-md">Manage users, subscription plans, revenue, and platform analytics from one dashboard.</p>
                <div class="flex items-center gap-6 text-sm text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        User Management
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Analytics
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Revenue
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-sm text-gray-500">
                &copy; {{ date('Y') }} airesumebuilder.co.in — All rights reserved.
            </div>
        </div>

        {{-- Right panel — login form --}}
        <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-gray-50">
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-900 text-white text-sm font-bold">A</span>
                    <span class="text-xl font-extrabold text-gray-900">AI Resume Builder</span>
                </a>
            </div>

            <div class="w-full max-w-md">
                <div class="mb-8">
                    <div class="inline-flex items-center gap-2 rounded-full bg-gray-900/5 border border-gray-200 px-3 py-1 text-xs font-semibold text-gray-600 mb-4">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        ADMIN PANEL
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Admin Login</h1>
                    <p class="mt-2 text-gray-500">Sign in with your administrator credentials.</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/20 placeholder:text-gray-400"
                               placeholder="admin@example.com">
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/20 placeholder:text-gray-400"
                               placeholder="Enter your password">
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="h-4 w-4 rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900/20">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>

                    <button type="submit"
                            class="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-gray-900/20 transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        Sign in to Admin
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-gray-500">
                    Not an admin?
                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Go to user login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

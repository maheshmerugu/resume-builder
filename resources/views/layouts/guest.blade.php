<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AI Resume Builder') }}</title>
        @include('partials.favicon')

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            {{-- Left panel — branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 text-white flex-col justify-between p-12 overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="400" cy="400" r="300" fill="none" stroke="white" stroke-width="1"/>
                        <circle cx="400" cy="400" r="200" fill="none" stroke="white" stroke-width="1"/>
                        <circle cx="400" cy="400" r="100" fill="none" stroke="white" stroke-width="1"/>
                        <line x1="100" y1="400" x2="700" y2="400" stroke="white" stroke-width="0.5"/>
                        <line x1="400" y1="100" x2="400" y2="700" stroke="white" stroke-width="0.5"/>
                    </svg>
                </div>

                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur text-lg font-bold">A</span>
                        <span class="text-2xl font-extrabold tracking-tight">AI Resume Builder</span>
                    </a>
                </div>

                <div class="relative z-10 space-y-6">
                    <h2 class="text-4xl font-extrabold leading-tight">Build resumes that<br>get you hired.</h2>
                    <p class="text-lg text-indigo-100 max-w-md">Beautiful templates, built-in ATS checker, and one-click PDF export. Everything you need to land your dream job.</p>
                    <div class="flex items-center gap-6 text-sm text-indigo-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            ATS-optimized
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Affordable plans
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            PDF export
                        </div>
                    </div>
                </div>

                <div class="relative z-10 text-sm text-indigo-200">
                    &copy; {{ date('Y') }} airesumebuilder.co.in — All rights reserved.
                </div>
            </div>

            {{-- Right panel — form --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-gray-50">
                <div class="lg:hidden mb-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white text-sm font-bold">A</span>
                        <span class="text-xl font-extrabold text-gray-900">AI Resume Builder</span>
                    </a>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

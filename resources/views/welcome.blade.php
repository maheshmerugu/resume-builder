<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AI Resume Builder') }} — Create a resume from any job description</title>
    <meta name="description" content="Paste any job description and get a tailored ATS resume in minutes. AI writing, {{ $themeCount }}+ templates, job-match scoring, and one-click PDF export.">
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-slate-900 antialiased" x-data="{ mobileOpen: false, faqOpen: null }">
@php
    $jdCreateUrl = auth()->check() ? route('resumes.from-jd.create') : route('register');
    $jdCtaLabel = auth()->check() ? 'Create resume from JD' : 'Sign up — paste a JD free';
@endphp

    {{-- Nav --}}
    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white shadow-md shadow-indigo-600/30">A</span>
                <span class="text-lg font-bold tracking-tight text-slate-900">AI Resume Builder</span>
            </a>
            <nav class="hidden items-center gap-7 text-sm font-medium text-slate-600 lg:flex">
                <a href="#from-jd" class="transition hover:text-indigo-600">JD → Resume</a>
                <a href="#features" class="transition hover:text-indigo-600">Features</a>
                <a href="#ai-writer" class="transition hover:text-indigo-600">AI Writer</a>
                <a href="#ats" class="transition hover:text-indigo-600">ATS Checker</a>
                <a href="#templates" class="transition hover:text-indigo-600">Templates</a>
                <a href="#pricing" class="transition hover:text-indigo-600">Pricing</a>
            </nav>
            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ url('/dashboard') }}" class="landing-btn-primary !px-5 !py-2.5">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">Log in</a>
                    <a href="{{ route('register') }}" class="landing-btn-primary !px-5 !py-2.5">Start free</a>
                @endauth
            </div>
            <button type="button" class="rounded-lg p-2 text-slate-600 lg:hidden" @click="mobileOpen = !mobileOpen" aria-label="Toggle menu">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div x-show="mobileOpen" x-cloak x-transition class="border-t border-slate-100 bg-white px-4 py-4 lg:hidden">
            <nav class="flex flex-col gap-1 text-sm font-medium text-slate-700">
                @foreach ([['#from-jd','JD → Resume'],['#features','Features'],['#ai-writer','AI Writer'],['#ats','ATS Checker'],['#templates','Templates'],['#pricing','Pricing'],['#faq','FAQ']] as $link)
                    <a href="{{ $link[0] }}" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 hover:bg-slate-50">{{ $link[1] }}</a>
                @endforeach
            </nav>
            <div class="mt-4 flex flex-col gap-2 border-t border-slate-100 pt-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="landing-btn-primary text-center">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="landing-btn-secondary text-center">Log in</a>
                    <a href="{{ route('register') }}" class="landing-btn-primary text-center">Start free</a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="landing-hero-bg relative overflow-hidden">
        <div class="landing-grid-pattern pointer-events-none absolute inset-0 opacity-60"></div>
        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:gap-14 lg:px-8 lg:py-24">
            <div class="landing-fade-up max-w-xl lg:max-w-none">
                <span class="landing-section-label">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    New · Job description → full resume in seconds
                </span>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl lg:text-[3.4rem]">
                    Paste a job description.
                    <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-500 bg-clip-text text-transparent">Get a tailored resume.</span>
                </h1>
                <p class="mt-5 text-lg leading-relaxed text-slate-600">
                    Copy any job posting — we extract keywords, write your summary & bullets, and build an ATS-ready resume you can edit and download as PDF. No blank-page stress.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ $jdCreateUrl }}" class="landing-btn-primary">
                        {{ $jdCtaLabel }}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="#from-jd" class="landing-btn-secondary">See how it works</a>
                </div>
                <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                    @foreach (['Paste any job description → get a full resume', 'AI writes bullet points for you', 'Score resume vs any job description', 'One-click print-ready PDF'] as $item)
                        <li class="landing-check-row">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- JD → Resume hero visual --}}
            <div class="landing-fade-up landing-fade-up-delay-2 relative mx-auto w-full max-w-md lg:max-w-none">
                <div class="landing-float absolute -left-3 top-8 z-20 hidden rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-xl lg:block">
                    <p class="text-xs font-medium text-slate-500">Step 1</p>
                    <p class="text-sm font-bold text-slate-900">Paste job description</p>
                </div>
                <div class="landing-float-delay absolute -right-2 bottom-16 z-20 hidden rounded-2xl border border-emerald-200 bg-white px-4 py-3 shadow-xl lg:block">
                    <p class="text-xs font-medium text-emerald-600">Generated</p>
                    <p class="text-sm font-bold text-slate-900">Tailored resume ready</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xl shadow-slate-900/10 ring-1 ring-slate-900/5">
                    <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-4 py-3">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        <span class="ml-2 text-xs font-medium text-slate-400">Create from Job Description</span>
                    </div>
                    <div class="space-y-4 bg-gradient-to-br from-slate-50 to-white p-5 sm:p-6">
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Job description</p>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">Senior Product Manager — 5+ years, roadmap ownership, stakeholder management, SQL, agile, B2B SaaS, OKRs...</p>
                        </div>
                        <div class="flex justify-center">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-600 px-4 py-1.5 text-xs font-bold text-white">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                AI generates resume
                            </span>
                        </div>
                        <div class="rounded-xl border border-indigo-100 bg-indigo-50/40 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Priya Sharma</p>
                                    <p class="text-xs font-semibold text-indigo-600">Senior Product Manager</p>
                                </div>
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">92% ATS</span>
                            </div>
                            <p class="mt-2 text-[11px] leading-relaxed text-slate-600">Results-driven PM with 6+ years leading cross-functional teams. Skilled in OKRs, SQL, agile delivery...</p>
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach (['OKRs', 'SQL', 'Agile', 'Roadmapping', 'B2B SaaS'] as $kw)
                                    <span class="rounded bg-white px-1.5 py-0.5 text-[10px] font-medium text-indigo-700 ring-1 ring-indigo-100">{{ $kw }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust bar --}}
    <section class="border-y border-slate-100 bg-slate-50/80">
        <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6 text-center md:grid-cols-5">
                @foreach ([['JD → Resume', 'Auto-generate'], [$themeCount . '+', 'Pro themes'], ['1M+', 'Resumes built'], ['92%', 'Avg ATS score'], ['4.8★', 'User rating']] as $stat)
                    <div>
                        <p class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ $stat[0] }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">{{ $stat[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pain → Solution --}}
    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="landing-section-label">Why switch</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">Word docs lose interviews. Smart resumes win them.</h2>
                <p class="mt-4 text-slate-600">Most applications never reach a human. We help your resume clear ATS filters and impress recruiters.</p>
            </div>
            <div class="mt-12 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-rose-100 bg-rose-50/50 p-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Without AI Resume Builder</p>
                    <ul class="mt-5 space-y-3">
                        @foreach (['Manually rewriting resume for every job posting', 'Missing keywords the ATS is scanning for', 'Generic bullets that sound like everyone else', 'Hours in Word with no match score feedback'] as $item)
                            <li class="flex gap-3 text-sm text-slate-700">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/40 p-7 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">With AI Resume Builder</p>
                    <ul class="mt-5 space-y-3">
                        @foreach (['Paste JD → get a full tailored resume', 'Keywords extracted and woven into content', 'AI writes summary, skills & bullet points', 'Instant ATS match score after generation'] as $item)
                            <li class="landing-check-row">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how-it-works" class="border-y border-slate-100 bg-slate-50/60 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="landing-section-label">How it works</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">From job description to PDF in 4 steps</h2>
            </div>
            <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['01', 'Paste the JD', 'Copy the full job posting — title, requirements, responsibilities & skills.'],
                    ['02', 'Add your background', 'Name, experience level, and optional notes about your career.'],
                    ['03', 'AI builds your resume', 'We generate headline, summary, skills & bullets matched to the JD.'],
                    ['04', 'Edit & download PDF', 'Personalize in the editor, check ATS score, export and apply.'],
                ] as $step)
                    <div class="landing-card p-6">
                        <span class="text-3xl font-extrabold text-indigo-100">{{ $step[0] }}</span>
                        <h3 class="mt-3 text-base font-bold text-slate-900">{{ $step[1] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ $jdCreateUrl }}" class="landing-btn-primary">{{ $jdCtaLabel }}</a>
                <a href="{{ route('register') }}" class="landing-btn-secondary">Or create manually</a>
            </div>
        </div>
    </section>

    {{-- Feature grid --}}
    <section id="features" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="landing-section-label">All-in-one toolkit</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">Features that help you get hired faster</h2>
                <p class="mt-4 text-slate-600">Everything from writing to scoring to exporting — built for competitive job markets.</p>
            </div>
            <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $features = [
                        ['M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'Auto Resume from Job Description', 'Paste a job posting and instantly generate a tailored resume with matching keywords, summary, and experience bullets.'],
                        ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'ATS Resume Checker', 'Paste a job description and get a match score plus missing keywords to beat bots.'],
                        ['M13 10V3L4 14h7v7l9-11h-7z', 'AI Writing Assistant', 'Turn rough notes into polished summaries and achievement-focused bullet points.'],
                        ['M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'Live Preview Builder', 'See every edit instantly. What you see is exactly what recruiters download.'],
                        ['M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z', $themeCount.'+ Professional Themes', 'Modern, classic, banner, and executive styles — switch anytime without losing content.'],
                        ['M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'One-Click PDF Export', 'Download a print-ready PDF optimized for email and online applications.'],
                        ['M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'Multiple Resume Versions', 'Create role-specific resumes for every application from one dashboard.'],
                        ['M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'Profile Completeness', 'A clear progress meter shows what’s missing so you never submit a half-finished resume.'],
                        ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'Save Hours of Work', 'Guided forms replace formatting chaos — go from blank page to PDF in minutes.'],
                        ['M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'Private & Secure', 'Your career data stays yours. Export, edit, or delete anytime.'],
                    ];
                @endphp
                @foreach ($features as $f)
                    <div class="landing-feature-tile">
                        <div class="landing-icon-wrap">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f[0] }}"/></svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-slate-900">{{ $f[1] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $f[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AI Writer spotlight --}}
    <section id="ai-writer" class="border-y border-slate-100 bg-gradient-to-b from-indigo-50/80 to-white py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <span class="landing-section-label">AI writing</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">Write like a top candidate — without the blank-page panic</h2>
                <p class="mt-4 text-slate-600">Describe your role in plain language. Our AI turns it into clear, metrics-friendly bullets recruiters love to scan.</p>
                <ul class="mt-8 space-y-4">
                    @foreach ([
                        ['Smarter summaries', 'Professional summaries tuned to your target role.'],
                        ['Achievement bullets', 'Action verbs + measurable outcomes, not vague duties.'],
                        ['Tone that fits', 'Confident and concise — never fluff or buzzword spam.'],
                    ] as $item)
                        <li class="flex gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-indigo-600 shadow-sm ring-1 ring-indigo-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item[0] }}</p>
                                <p class="mt-0.5 text-sm text-slate-600">{{ $item[1] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="landing-btn-primary mt-8">Try AI writing free</a>
            </div>
            <div class="landing-card overflow-hidden p-0 shadow-lg">
                <div class="border-b border-slate-100 bg-slate-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">AI rewrite preview</div>
                <div class="space-y-4 p-5">
                    <div class="rounded-xl border border-rose-100 bg-rose-50/60 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wide text-rose-500">Before</p>
                        <p class="mt-2 text-sm text-slate-600">Responsible for managing team and improving product features for customers.</p>
                    </div>
                    <div class="flex justify-center">
                        <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-bold text-white">AI polish →</span>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-600">After</p>
                        <p class="mt-2 text-sm font-medium text-slate-800">Led a cross-functional team of 8 to ship 12 product features, lifting user retention by 34% in two quarters.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JD → Resume spotlight --}}
    <section id="from-jd" class="border-y border-slate-100 bg-slate-900 py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="order-2 lg:order-1">
                <div class="landing-card overflow-hidden border-slate-700 bg-slate-800/50 p-0 shadow-2xl">
                    <div class="border-b border-slate-700 bg-slate-800 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Job description in → Resume out</div>
                    <div class="space-y-4 p-5">
                        <div class="rounded-xl border border-slate-600 bg-slate-900/80 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-500">Input</p>
                            <p class="mt-2 text-sm text-slate-300">Senior PHP Developer — Laravel, MySQL, REST APIs, 4+ years, agile team, build scalable backend services...</p>
                        </div>
                        <div class="flex justify-center">
                            <span class="rounded-full bg-indigo-500 px-4 py-1.5 text-xs font-bold text-white">Auto-generate →</span>
                        </div>
                        <div class="rounded-xl border border-emerald-500/30 bg-emerald-950/30 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-400">Generated resume</p>
                            <p class="mt-2 text-sm font-semibold text-white">Senior PHP Developer</p>
                            <p class="mt-1 text-xs text-slate-400">Summary + skills + bullets matched to JD keywords</p>
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach (['Laravel', 'PHP', 'MySQL', 'REST APIs', 'Agile'] as $kw)
                                    <span class="rounded-md bg-emerald-500/15 px-2 py-0.5 text-[10px] font-medium text-emerald-300">{{ $kw }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-1 text-white lg:order-2">
                <span class="inline-flex rounded-full border border-slate-700 bg-slate-800 px-3.5 py-1 text-xs font-semibold uppercase tracking-wide text-slate-300">Flagship feature</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight lg:text-4xl">One-click resume from any job posting</h2>
                <p class="mt-4 text-slate-400">Copy a job description, add your background, and get a complete tailored resume in seconds — not hours.</p>
                <ul class="mt-8 space-y-3">
                    @foreach (['Extracts keywords from the JD automatically', 'Writes headline, summary, skills & bullets', 'Opens in the editor ready to personalize', 'Shows estimated ATS match score'] as $item)
                        <li class="flex gap-2.5 text-sm text-slate-300">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ $jdCreateUrl }}" class="landing-btn-primary mt-8 !bg-white !text-indigo-700 hover:!bg-indigo-50">
                    {{ $jdCtaLabel }}
                </a>
            </div>
        </div>
    </section>

    {{-- ATS spotlight --}}
    <section id="ats" class="py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="order-2 lg:order-1">
                <div class="landing-card p-6 shadow-lg sm:p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Job match analysis</p>
                            <p class="text-xs text-slate-500">Senior Product Manager · Fintech</p>
                        </div>
                        <div class="landing-score-ring flex h-16 w-16 items-center justify-center rounded-full">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-sm font-extrabold text-emerald-600">92%</span>
                        </div>
                    </div>
                    <div class="mt-6 space-y-3">
                        @foreach ([['Keywords matched', 88], ['Experience relevance', 95], ['Skills coverage', 90]] as $bar)
                            <div>
                                <div class="mb-1 flex justify-between text-xs font-medium text-slate-600">
                                    <span>{{ $bar[0] }}</span>
                                    <span>{{ $bar[1] }}%</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-emerald-500" style="width: {{ $bar[1] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-100">
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Suggested keywords</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach (['OKRs', 'Stakeholder management', 'A/B testing', 'SQL'] as $kw)
                                <span class="rounded-md bg-white px-2.5 py-1 text-xs font-semibold text-amber-800 ring-1 ring-amber-100">{{ $kw }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <span class="landing-section-label">ATS checker</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">Know your match score before you hit Apply</h2>
                <p class="mt-4 text-slate-600">Paste any job description. Instantly see how well your resume matches — and which keywords to add so you don’t get filtered out.</p>
                <ul class="mt-8 space-y-3">
                    @foreach (['Instant match percentage', 'Missing keyword suggestions', 'Works with every resume you create'] as $item)
                        <li class="landing-check-row">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ auth()->check() ? route('ats.create') : route('register') }}" class="landing-btn-primary mt-8">Check my ATS score</a>
            </div>
        </div>
    </section>

    {{-- Who it's for --}}
    <section class="border-y border-slate-100 bg-slate-50/60 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="landing-section-label">Built for you</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">Whatever stage you’re at</h2>
            </div>
            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Fresh graduates', 'Clean layouts that highlight projects, internships, and skills without looking empty.'],
                    ['Career switchers', 'Reframe experience for a new industry with AI-assisted wording.'],
                    ['Experienced pros', 'Executive themes and multi-version resumes for senior roles.'],
                    ['Active job hunters', 'Tailor fast for every posting and track ATS scores as you go.'],
                ] as $persona)
                    <div class="landing-card p-6">
                        <div class="landing-icon-wrap">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="mt-4 font-bold text-slate-900">{{ $persona[0] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $persona[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Templates --}}
    <section id="templates" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <span class="landing-section-label">Templates</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">{{ $themeCount }}+ themes that look hired — not homemade</h2>
                    <p class="mt-4 text-slate-600">Recruiter-approved designs. Switch themes anytime; your content stays put.</p>
                </div>
                <a href="{{ auth()->check() ? route('themes.index') : route('register') }}" class="landing-btn-primary shrink-0">
                    Browse all templates
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (\App\Support\ResumeThemes::featured(6) as $key => $meta)
                    <div class="group landing-card overflow-hidden">
                        @include('partials.theme-preview', ['theme' => $key])
                        <div class="flex items-center justify-between border-t border-slate-100 p-5">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $meta['label'] }}</p>
                                <p class="text-xs text-slate-500">{{ $meta['tagline'] }}</p>
                            </div>
                            <a href="{{ auth()->check() ? route('resumes.create', ['template' => $key]) : route('register') }}" class="text-sm font-semibold text-indigo-600 transition group-hover:underline">Use →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Guarantee / trust --}}
    <section class="border-y border-slate-100 bg-indigo-600 py-12">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 text-center sm:grid-cols-3 sm:px-6 lg:px-8">
            @foreach ([
                ['No credit card', 'Create and explore free — upgrade only when you need more.'],
                ['ATS-first design', 'Every template is built to parse cleanly in hiring systems.'],
                ['Cancel anytime', 'Flexible plans. No long contracts. You’re in control.'],
            ] as $trust)
                <div class="text-white">
                    <p class="text-lg font-bold">{{ $trust[0] }}</p>
                    <p class="mt-1 text-sm text-indigo-100">{{ $trust[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="landing-section-label">Pricing</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">Simple plans. Serious career ROI.</h2>
                <p class="mt-4 text-slate-600">Less than a coffee a day for tools that can change your next offer.</p>
            </div>
            <div class="mt-14 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($plans as $plan)
                    <div @class([
                        'relative flex flex-col rounded-2xl p-7 transition',
                        'border-2 border-indigo-600 bg-white shadow-xl shadow-indigo-600/10' => $plan->is_featured,
                        'landing-card' => ! $plan->is_featured,
                    ])>
                        @if ($plan->is_featured)
                            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full bg-indigo-600 px-4 py-1 text-xs font-bold uppercase tracking-wide text-white">Most popular</span>
                        @endif
                        <h3 class="text-lg font-bold text-slate-900">{{ $plan->name }}</h3>
                        <p class="mt-1 min-h-[2.5rem] text-sm text-slate-500">{{ $plan->description }}</p>
                        <div class="mt-5 flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold tracking-tight text-slate-900">{{ $plan->priceLabel() }}</span>
                            <span class="text-sm text-slate-500">{{ $plan->intervalLabel() }}</span>
                        </div>
                        <ul class="mt-6 flex-1 space-y-3 text-sm text-slate-600">
                            @foreach (($plan->features ?? []) as $feature)
                                <li class="flex items-start gap-2.5">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('register') }}" @class([
                            'mt-7 block rounded-xl px-4 py-3 text-center text-sm font-semibold transition',
                            'bg-indigo-600 text-white shadow-md shadow-indigo-600/25 hover:bg-indigo-500' => $plan->is_featured,
                            'bg-slate-900 text-white hover:bg-slate-800' => ! $plan->is_featured,
                        ])>
                            Get {{ $plan->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="bg-slate-900 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="inline-flex rounded-full border border-slate-700 bg-slate-800 px-3.5 py-1 text-xs font-semibold uppercase tracking-wide text-slate-300">Success stories</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white lg:text-4xl">Job seekers who stopped getting ghosted</h2>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['Priya S.', 'Product Manager', '3 interviews in a week', 'The ATS checker showed me exactly which keywords I was missing. Game changer for my applications.', 'PS'],
                    ['Arjun K.', 'Software Engineer', 'Hired at a product company', 'Templates looked sharp and the live preview made editing actually enjoyable. Recruiters noticed.', 'AK'],
                    ['Neha R.', 'Marketing Lead', 'Two resumes in 20 minutes', 'I tailored versions for different roles and exported perfect PDFs. Worth every rupee.', 'NR'],
                ] as $r)
                    <div class="rounded-2xl border border-slate-700/80 bg-slate-800/50 p-7">
                        <div class="flex gap-0.5 text-amber-400">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="mt-4 text-lg font-bold text-white">{{ $r[2] }}</p>
                        <p class="mt-3 text-sm leading-relaxed text-slate-400">"{{ $r[3] }}"</p>
                        <div class="mt-6 flex items-center gap-3 border-t border-slate-700/80 pt-5">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600/30 text-sm font-bold text-indigo-300">{{ $r[4] }}</span>
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $r[0] }}</p>
                                <p class="text-xs text-slate-400">{{ $r[1] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="py-20">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="landing-section-label">FAQ</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 lg:text-4xl">Questions, answered</h2>
            </div>
            <div class="mt-10 space-y-3">
                @php
                    $faqs = [
                        ['Can I create a resume from a job description?', 'Yes — paste any job posting on our JD → Resume page. AI extracts keywords and generates a tailored resume with headline, summary, skills, and experience bullets. You can edit everything before downloading.'],
                        ['Is it free to start?', 'Yes. Create an account and build your resume without a credit card. Upgrade when you need more downloads, themes, or AI credits.'],
                        ['Will my resume pass ATS systems?', 'Yes. Templates are designed for clean parsing, and the ATS checker scores your resume against any job description with keyword tips.'],
                        ['Can AI write my entire resume?', 'AI helps with summaries and bullet points from your inputs. You stay in control — edit anything before exporting.'],
                        ['Can I make multiple versions?', 'Absolutely. Create tailored resumes for each role and keep them organized in your dashboard.'],
                        ['Can I cancel anytime?', 'Yes. Cancel from account settings whenever you like — no long-term contracts.'],
                    ];
                @endphp
                @foreach ($faqs as $i => $faq)
                    <details class="group landing-card" @if($i === 0) open @endif>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 font-semibold text-slate-900 [&::-webkit-details-marker]:hidden">
                            {{ $faq[0] }}
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 transition group-open:rotate-45">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </span>
                        </summary>
                        <p class="border-t border-slate-100 px-5 pb-5 pt-4 text-sm leading-relaxed text-slate-600">{{ $faq[1] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="pb-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-700 px-8 py-16 text-center shadow-2xl shadow-indigo-600/30 sm:px-16">
                <div class="landing-grid-pattern pointer-events-none absolute inset-0 opacity-20"></div>
                <div class="relative">
                    <h2 class="text-3xl font-extrabold tracking-tight text-white lg:text-4xl">Got a job posting? Turn it into a resume now.</h2>
                    <p class="mx-auto mt-4 max-w-xl text-lg text-indigo-100">Paste the JD, get a tailored resume in seconds — then edit, score against ATS, and download PDF.</p>
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                        <a href="{{ $jdCreateUrl }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-base font-semibold text-indigo-700 shadow-lg transition hover:bg-indigo-50">
                            {{ $jdCtaLabel }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-8 py-3.5 text-base font-semibold text-white transition hover:bg-white/10">Create manually</a>
                    </div>
                    <p class="mt-6 text-sm text-indigo-200">Free to start · No credit card · Cancel anytime</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-xs font-bold text-white">A</span>
                        <span class="font-bold text-slate-900">AI Resume Builder</span>
                    </a>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-500">ATS optimization, AI writing, live preview, and instant PDF export — built for serious job seekers.</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Product</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-slate-600">
                        <li><a href="{{ $jdCreateUrl }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Create from job description</a></li>
                        <li><a href="#features" class="hover:text-indigo-600">Features</a></li>
                        <li><a href="#from-jd" class="hover:text-indigo-600">JD → Resume</a></li>
                        <li><a href="#ai-writer" class="hover:text-indigo-600">AI Writer</a></li>
                        <li><a href="#ats" class="hover:text-indigo-600">ATS Checker</a></li>
                        <li><a href="#templates" class="hover:text-indigo-600">Templates</a></li>
                        <li><a href="#pricing" class="hover:text-indigo-600">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Account</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-slate-600">
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-600">Log in</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-indigo-600">Sign up free</a></li>
                        <li><a href="#faq" class="hover:text-indigo-600">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Contact</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-slate-600">
                        <li><a href="mailto:support@airesumebuilder.co.in" class="hover:text-indigo-600">support@airesumebuilder.co.in</a></li>
                        <li><span class="text-slate-500">airesumebuilder.co.in</span></li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 flex flex-col items-center justify-between gap-4 border-t border-slate-200 pt-8 text-sm text-slate-500 sm:flex-row">
                <p>&copy; {{ date('Y') }} AI Resume Builder. All rights reserved.</p>
                <p class="text-xs">Made for job seekers in India and worldwide.</p>
            </div>
        </div>
    </footer>
</body>
</html>

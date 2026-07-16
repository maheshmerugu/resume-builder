<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AI Resume Builder') }} — Build a job-winning resume in minutes</title>
    <meta name="description" content="Create a professional, ATS-friendly resume in minutes. Beautiful templates, a built-in ATS checker, and one-click PDF export.">
    @include('partials.favicon')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Nav --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-xl text-indigo-600">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white">A</span>
                AI Resume Builder
            </a>
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="#features" class="hover:text-indigo-600">Features</a>
                <a href="#templates" class="hover:text-indigo-600">Templates</a>
                <a href="#pricing" class="hover:text-indigo-600">Pricing</a>
                <a href="#faq" class="hover:text-indigo-600">FAQ</a>
            </nav>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Get started</a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                    ★ Trusted by job seekers worldwide
                </span>
                <h1 class="mt-5 text-4xl lg:text-6xl font-extrabold tracking-tight text-gray-900 leading-[1.1]">
                    Build a resume that <span class="text-indigo-600">gets you hired</span>
                </h1>
                <p class="mt-5 text-lg text-gray-600 max-w-xl">
                    Create a polished, ATS-friendly resume in minutes. Pick a template, fill in your details, check your ATS score against any job, and download a perfect PDF.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('register') }}" class="rounded-xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-indigo-600/20 hover:bg-indigo-500">
                        Create my resume
                    </a>
                    <a href="#pricing" class="rounded-xl border border-gray-300 px-6 py-3.5 text-base font-semibold text-gray-700 hover:bg-gray-50">
                        View pricing
                    </a>
                </div>
                <div class="mt-8 flex items-center gap-6 text-sm text-gray-500">
                    <div class="flex items-center gap-1">
                        <span class="text-amber-400 text-lg">★★★★★</span>
                        <span class="font-semibold text-gray-700">4.8/5</span>
                    </div>
                    <span>No credit card required</span>
                </div>
            </div>

            {{-- Resume mockup --}}
            <div class="relative">
                <div class="absolute -inset-4 bg-indigo-200/40 blur-3xl rounded-full"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 p-8 rotate-1">
                    <div class="flex items-center gap-4 border-b pb-4">
                        <div class="h-14 w-14 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xl font-bold">MM</div>
                        <div>
                            <div class="h-4 w-40 bg-gray-800 rounded"></div>
                            <div class="mt-2 h-3 w-52 bg-indigo-400 rounded"></div>
                        </div>
                    </div>
                    <div class="mt-5 space-y-2">
                        <div class="h-3 w-24 bg-indigo-500 rounded"></div>
                        <div class="h-2.5 w-full bg-gray-200 rounded"></div>
                        <div class="h-2.5 w-11/12 bg-gray-200 rounded"></div>
                        <div class="h-2.5 w-4/5 bg-gray-200 rounded"></div>
                    </div>
                    <div class="mt-5 space-y-2">
                        <div class="h-3 w-28 bg-indigo-500 rounded"></div>
                        <div class="h-2.5 w-full bg-gray-200 rounded"></div>
                        <div class="h-2.5 w-10/12 bg-gray-200 rounded"></div>
                    </div>
                    <div class="mt-5 flex gap-2 flex-wrap">
                        @foreach (['PHP','Laravel','React','MySQL','AWS'] as $skill)
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ $skill }}</span>
                        @endforeach
                    </div>
                    <div class="absolute -bottom-4 -right-4 rounded-xl bg-green-500 text-white px-4 py-2 text-sm font-bold shadow-lg -rotate-3">
                        ATS Score 92%
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats bar --}}
    <section class="border-y border-gray-100 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @foreach ([[$themeCount . '+', 'Beautiful themes'],['1M+','Resumes built'],['92%','Avg. ATS score'],['4.8★','User rating']] as $stat)
                <div>
                    <p class="text-3xl font-extrabold text-indigo-600">{{ $stat[0] }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900">Everything you need to land the job</h2>
            <p class="mt-4 text-gray-600">From a blank page to a hireable resume — AI Resume Builder guides you the whole way.</p>
        </div>
        <div class="mt-14 grid md:grid-cols-3 gap-8">
            @php
                $features = [
                    ['🎯','ATS Resume Checker','Paste any job description and get an instant match score with keyword suggestions to beat the bots.'],
                    ['🎨','Recruiter-approved templates','Modern, Classic and Minimal templates designed to read well with both humans and ATS software.'],
                    ['⚡','Live preview builder','See your resume update in real time as you type. No fiddling with formatting.'],
                    ['📄','One-click PDF export','Download a crisp, print-ready PDF whenever you want.'],
                    ['🔁','Multiple versions','Tailor a resume for every role and keep them all in one place.'],
                    ['🔒','Private & secure','Your data stays yours. Manage or delete it any time.'],
                ];
            @endphp
            @foreach ($features as $f)
                <div class="rounded-2xl border border-gray-100 bg-white p-7 shadow-sm hover:shadow-md transition">
                    <div class="text-3xl">{{ $f[0] }}</div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">{{ $f[1] }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ $f[2] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Templates --}}
    <section id="templates" class="bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900">50+ themes that make you stand out</h2>
                <p class="mt-4 text-gray-600">Professionally designed and fully ATS-friendly. Pick from {{ $themeCount }} themes — switch anytime with one click.</p>
            </div>
            <div class="mt-8 text-center">
                <a href="{{ auth()->check() ? route('themes.index') : route('register') }}" class="inline-flex rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                    Browse all {{ $themeCount }} themes
                </a>
            </div>
            <div class="mt-10 grid gap-8 sm:grid-cols-3">
                @foreach (\App\Support\ResumeThemes::featured(6) as $key => $meta)
                    <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        @include('partials.theme-preview', ['theme' => $key])
                        <div class="flex items-center justify-between p-5">
                            <div>
                                <span class="font-semibold text-gray-800">{{ $meta['label'] }}</span>
                                <p class="text-xs text-gray-500">{{ $meta['tagline'] }}</p>
                            </div>
                            <a href="{{ auth()->check() ? route('resumes.create', ['template' => $key]) : route('register') }}" class="text-sm font-semibold text-indigo-600 hover:underline">Use theme →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900">Simple, transparent pricing</h2>
            <p class="mt-4 text-gray-600">Affordable plans for every job seeker. Cancel anytime.</p>
        </div>
        <div class="mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($plans as $plan)
                <div class="relative rounded-2xl bg-white border {{ $plan->is_featured ? 'border-indigo-500 ring-2 ring-indigo-200 shadow-lg' : 'border-gray-100 shadow-sm' }} p-6 flex flex-col">
                    @if ($plan->is_featured)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">Most Popular</span>
                    @endif
                    <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500 min-h-[2.5rem]">{{ $plan->description }}</p>
                    <div class="mt-4">
                        <span class="text-4xl font-extrabold text-gray-900">{{ $plan->priceLabel() }}</span>
                        <span class="text-sm text-gray-500">{{ $plan->intervalLabel() }}</span>
                    </div>
                    <ul class="mt-5 space-y-2 text-sm text-gray-600 flex-1">
                        @foreach (($plan->features ?? []) as $feature)
                            <li class="flex items-start gap-2">
                                <svg class="mt-0.5 h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}"
                       class="mt-6 block text-center rounded-lg {{ $plan->is_featured ? 'bg-indigo-600 hover:bg-indigo-500 text-white' : 'bg-gray-900 hover:bg-gray-800 text-white' }} px-4 py-2.5 text-sm font-semibold">
                        Choose {{ $plan->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="bg-indigo-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <h2 class="text-center text-3xl lg:text-4xl font-extrabold text-white">Loved by job seekers</h2>
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                @php
                    $reviews = [
                        ['Priya S.','Landed 3 interviews in a week', 'The ATS checker told me exactly which keywords I was missing. Game changer.'],
                        ['Arjun K.','Got hired at a product company', 'Clean templates and the live preview made building my resume actually enjoyable.'],
                        ['Neha R.','So easy to use', 'I made two tailored resumes in 20 minutes and downloaded perfect PDFs.'],
                    ];
                @endphp
                @foreach ($reviews as $r)
                    <div class="rounded-2xl bg-white p-6 shadow-sm">
                        <div class="text-amber-400 text-lg">★★★★★</div>
                        <p class="mt-3 font-semibold text-gray-900">{{ $r[1] }}</p>
                        <p class="mt-2 text-sm text-gray-600">"{{ $r[2] }}"</p>
                        <p class="mt-4 text-sm font-medium text-gray-500">— {{ $r[0] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-center text-3xl lg:text-4xl font-extrabold text-gray-900">Frequently asked questions</h2>
        <div class="mt-10 divide-y divide-gray-100 rounded-2xl border border-gray-100 bg-white">
            @php
                $faqs = [
                    ['What plans are available?','We offer Starter, Pro, and Lifetime plans with flexible pricing. Choose the plan that fits your job search.'],
                    ['Are the resumes ATS-friendly?','All templates are designed to be parsed correctly by Applicant Tracking Systems, and the built-in checker helps you optimize further.'],
                    ['Can I download my resume as a PDF?','Absolutely. Export a clean, print-ready PDF any time (download limits depend on your plan).'],
                    ['Can I cancel anytime?','Yes — you can cancel your subscription whenever you like.'],
                ];
            @endphp
            @foreach ($faqs as $faq)
                <details class="group p-5">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-800">
                        {{ $faq[0] }}
                        <span class="text-indigo-600 group-open:rotate-45 transition">+</span>
                    </summary>
                    <p class="mt-3 text-sm text-gray-600">{{ $faq[1] }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-14 text-center">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Your next job starts with a great resume</h2>
            <p class="mt-4 text-indigo-100">Join thousands of job seekers building better resumes with AI Resume Builder.</p>
            <a href="{{ route('register') }}" class="mt-8 inline-block rounded-xl bg-white px-8 py-3.5 text-base font-semibold text-indigo-700 hover:bg-indigo-50">
                Get started today
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <div class="flex items-center gap-2 font-extrabold text-indigo-600">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded bg-indigo-600 text-white text-xs">A</span>
                AI Resume Builder
            </div>
            <p>&copy; {{ date('Y') }} airesumebuilder.co.in — All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#features" class="hover:text-indigo-600">Features</a>
                <a href="#pricing" class="hover:text-indigo-600">Pricing</a>
                <a href="{{ route('login') }}" class="hover:text-indigo-600">Log in</a>
            </div>
        </div>
    </footer>
</body>
</html>

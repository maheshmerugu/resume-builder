<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Dashboard</h1>
            <p class="text-sm text-slate-500">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @include('partials.alert')

        @if (config('billing.enabled') && $plan)
            @php $rr = auth()->user()->remainingResumes(); $rd = auth()->user()->remainingDownloads(); @endphp
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 p-6 text-white lg:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-indigo-200">Current plan</p>
                            <p class="mt-1 text-2xl font-extrabold">{{ $plan->name }}</p>
                            <div class="mt-3 flex flex-wrap gap-4 text-sm text-indigo-100">
                                <span>Resumes: {{ is_null($rr) ? 'Unlimited' : $rr . ' left' }}</span>
                                <span>Downloads: {{ is_null($rd) ? 'Unlimited' : $rd . ' left' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('plans.index') }}" class="rounded-xl border border-white/20 bg-white/20 px-5 py-2.5 text-sm font-semibold backdrop-blur hover:bg-white/30">
                            Manage plan
                        </a>
                    </div>
                </div>
            </div>
        @elseif (config('billing.enabled'))
            <div class="admin-card flex flex-wrap items-center justify-between gap-4 border-amber-200 bg-amber-50 p-6">
                <div>
                    <p class="text-sm font-semibold text-amber-800">No active subscription</p>
                    <p class="mt-1 text-sm text-amber-700">Subscribe to a plan to create resumes, run ATS checks, and download PDFs.</p>
                </div>
                <a href="{{ route('plans.index') }}" class="admin-btn-primary">View plans</a>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('resumes.index') }}" class="admin-card group block overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="bg-gradient-to-br from-indigo-500 to-violet-600 p-5 text-white">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-medium text-indigo-100">Total Resumes</p>
                        <svg class="h-4 w-4 text-indigo-200 opacity-0 transition group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold">{{ $stats['resumes'] }}</p>
                    <p class="mt-1 text-xs text-indigo-200">Click to view all resumes</p>
                </div>
            </a>
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-blue-500 to-cyan-600 p-5 text-white">
                    <p class="text-sm font-medium text-blue-100">ATS Checks</p>
                    <p class="mt-2 text-3xl font-extrabold">{{ $stats['checks'] }}</p>
                </div>
            </div>
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white">
                    <p class="text-sm font-medium text-emerald-100">Best ATS Score</p>
                    <p class="mt-2 text-3xl font-extrabold">{{ $stats['best_score'] }}%</p>
                </div>
            </div>
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-purple-500 to-fuchsia-600 p-5 text-white">
                    <p class="text-sm font-medium text-purple-100">Avg. Completeness</p>
                    <p class="mt-2 text-3xl font-extrabold">{{ $stats['avg_completeness'] }}%</p>
                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white/25">
                        <div class="h-full rounded-full bg-white/90 transition-all" style="width: {{ $stats['avg_completeness'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="text-lg font-bold panel-title">Resume Themes</h2>
                <a href="{{ route('themes.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Browse all {{ \App\Support\ResumeThemes::count() }} →</a>
            </div>
            <div class="p-5">
                <p class="mb-4 text-sm panel-muted">Pick a professionally designed theme. Click any card to create a resume with that theme instantly.</p>
                @include('partials.theme-gallery', [
                    'themes' => \App\Support\ResumeThemes::featured(8),
                    'mode' => 'link',
                    'showFilters' => false,
                    'compact' => true,
                ])
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="admin-card xl:col-span-2" id="your-resumes">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold text-slate-900">Your Resumes</h2>
                    <a href="{{ route('resumes.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">View all →</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($resumes as $resume)
                        <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900">{{ $resume->title }}</p>
                                    <p class="text-xs text-slate-500">
                                        @php $themeMeta = \App\Support\ResumeThemes::get($resume->template); @endphp
                                        {{ $themeMeta['label'] }} theme · {{ $resume->updated_at->diffForHumans() }}
                                    </p>
                                    @include('partials.resume-completeness-inline', ['resume' => $resume])
                                </div>
                            </div>
                            @include('partials.resume-actions', ['resume' => $resume, 'compact' => true, 'showAts' => true])
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <p class="text-slate-500">No resumes yet.</p>
                            <a href="{{ route('resumes.create') }}" class="admin-btn-primary mt-4 inline-flex">Create your first resume</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold text-slate-900">Recent ATS Checks</h2>
                    <a href="{{ route('ats.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">All →</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($atsChecks as $check)
                        @php
                            $scoreClass = match($check->scoreColor()) {
                                'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                                'yellow', 'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
                                'red' => 'bg-red-50 text-red-700 ring-red-100',
                                default => 'bg-slate-100 text-slate-700 ring-slate-200',
                            };
                        @endphp
                        <a href="{{ route('ats.show', $check) }}" class="flex items-center justify-between gap-3 px-5 py-4 hover:bg-slate-50">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900">{{ $check->job_title ?: 'Untitled role' }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $check->resume?->title ?? 'Resume deleted' }}</p>
                            </div>
                            <span class="admin-badge shrink-0 ring-1 {{ $scoreClass }}">{{ $check->score }}%</span>
                        </a>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <p class="text-sm text-slate-500">No ATS checks yet.</p>
                            <a href="{{ route('ats.create') }}" class="admin-btn-primary mt-4 inline-flex">Run an ATS check</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

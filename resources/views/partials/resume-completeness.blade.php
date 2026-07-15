@php
    /** @var \App\Models\Resume $resume */
    $c = $resume->completenessBreakdown();
    $editUrl = route('resumes.edit', $resume);
@endphp

<div class="resume-progress-dock sticky top-16 z-20 -mx-4 mb-4 border-b border-slate-200/80 bg-white/95 shadow-sm backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-900/95 sm:-mx-6 sm:px-6 lg:static lg:mx-0 lg:mb-5 lg:rounded-2xl lg:border lg:shadow-none">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-2.5 sm:flex-row sm:items-center sm:gap-4 lg:px-5">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <div class="relative h-11 w-11 shrink-0">
                <svg class="h-11 w-11 -rotate-90" viewBox="0 0 44 44" aria-hidden="true">
                    <circle cx="22" cy="22" r="18" fill="none" stroke-width="4" class="stroke-slate-200 dark:stroke-slate-700"/>
                    <circle cx="22" cy="22" r="18" fill="none" stroke-width="4" stroke-linecap="round"
                            class="transition-all duration-500 {{ \App\Support\ResumeCompleteness::ringClass($c['percent']) }}"
                            stroke-dasharray="{{ $c['percent'] * 1.131 }} 113.1"/>
                </svg>
                <span class="absolute inset-0 flex items-center justify-center text-[11px] font-extrabold tabular-nums {{ \App\Support\ResumeCompleteness::textClass($c['percent']) }}">{{ $c['percent'] }}%</span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">Profile completeness</p>
                    <span class="admin-badge hidden text-[10px] sm:inline-flex {{ \App\Support\ResumeCompleteness::badgeClass($c['percent']) }}">{{ $c['status'] }}</span>
                </div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                    <div class="h-full rounded-full transition-all duration-500 {{ \App\Support\ResumeCompleteness::barClass($c['percent']) }}" style="width: {{ $c['percent'] }}%"></div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 sm:pb-0">
            @foreach ($c['checks'] as $check)
                <a href="{{ $editUrl }}#{{ $check['section'] }}"
                   class="flex shrink-0 items-center gap-1 rounded-full px-2 py-1 text-[10px] font-semibold transition {{ $check['done']
                       ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'
                       : (($c['next']['section'] ?? null) === $check['section']
                           ? 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-300 dark:bg-indigo-500/20 dark:text-indigo-300 dark:ring-indigo-500/40'
                           : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400') }}"
                   title="{{ $check['label'] }}">
                    @if ($check['done'])
                        <span>✓</span>
                    @else
                        <span>{{ strtoupper(substr($check['label'], 0, 1)) }}</span>
                    @endif
                    <span class="hidden sm:inline">{{ $check['label'] }}</span>
                </a>
            @endforeach
        </div>

        @if ($c['next'])
            <a href="{{ $editUrl }}#{{ $c['next']['section'] }}" class="admin-btn-primary shrink-0 py-2 text-xs sm:text-sm">
                Next: {{ $c['next']['label'] }}
            </a>
        @endif
    </div>
</div>

<div class="admin-card overflow-hidden">
    <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:gap-6 sm:p-5">
        <div class="relative mx-auto h-20 w-20 shrink-0 sm:mx-0">
            <svg class="h-20 w-20 -rotate-90" viewBox="0 0 80 80" aria-hidden="true">
                <circle cx="40" cy="40" r="34" fill="none" stroke-width="6" class="stroke-slate-200 dark:stroke-slate-700"/>
                <circle cx="40" cy="40" r="34" fill="none" stroke-width="6" stroke-linecap="round"
                        class="transition-all duration-500 ease-out {{ \App\Support\ResumeCompleteness::ringClass($c['percent']) }}"
                        stroke-dasharray="{{ $c['percent'] * 2.136 }} 213.6"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-xl font-extrabold tabular-nums leading-none {{ \App\Support\ResumeCompleteness::textClass($c['percent']) }}">{{ $c['percent'] }}%</span>
                <span class="mt-0.5 text-[10px] font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">done</span>
            </div>
        </div>

        <div class="min-w-0 flex-1 text-center sm:text-left">
            <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 sm:text-base">Profile completeness</h2>
                <span class="admin-badge text-[11px] {{ \App\Support\ResumeCompleteness::badgeClass($c['percent']) }}">{{ $c['status'] }}</span>
            </div>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 sm:text-sm">{{ $c['label'] }}</p>
            <div class="mt-3">
                @include('partials.resume-progress-bar', ['percent' => $c['percent'], 'showLabel' => false])
            </div>
        </div>
    </div>

    @if ($c['next'])
        <div class="border-t border-indigo-100 bg-gradient-to-r from-indigo-50/80 to-violet-50/50 px-4 py-4 dark:border-indigo-500/20 dark:from-indigo-950/30 dark:to-violet-950/20 sm:px-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">→</span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">Next step</p>
                        <p class="mt-0.5 font-semibold text-slate-900 dark:text-slate-100">Add {{ strtolower($c['next']['label']) }}</p>
                        <p class="mt-0.5 text-sm text-slate-600 dark:text-slate-400">{{ $c['next']['hint'] }}</p>
                    </div>
                </div>
                <a href="{{ $editUrl }}#{{ $c['next']['section'] }}" class="admin-btn-primary shrink-0 justify-center">Continue editing</a>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-2 border-t border-slate-100 bg-slate-50/60 p-4 dark:border-slate-800 dark:bg-slate-800/30 sm:grid-cols-3 sm:p-5">
        @foreach ($c['checks'] as $check)
            <a href="{{ $editUrl }}#{{ $check['section'] }}"
               class="flex items-center gap-2 rounded-xl border px-3 py-2.5 transition-colors hover:shadow-sm {{ $check['done']
                   ? 'border-emerald-200 bg-emerald-50/80 dark:border-emerald-500/30 dark:bg-emerald-500/10'
                   : 'border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900/60' }}">
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold {{ $check['done']
                    ? 'bg-emerald-500 text-white'
                    : 'bg-slate-200 text-slate-400 dark:bg-slate-700 dark:text-slate-500' }}">
                    @if ($check['done'])
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ strtoupper(substr($check['label'], 0, 1)) }}
                    @endif
                </span>
                <span class="text-xs font-medium {{ $check['done'] ? 'text-emerald-800 dark:text-emerald-300' : 'text-slate-600 dark:text-slate-400' }}">{{ $check['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>

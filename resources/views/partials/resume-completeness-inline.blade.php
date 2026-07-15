@php
    /** @var \App\Models\Resume $resume */
    $c = $resume->completenessBreakdown();
@endphp

<div class="mt-3 space-y-2">
    @include('partials.resume-progress-bar', ['percent' => $c['percent'], 'size' => 'sm'])
    @if ($c['next'])
        <a href="{{ route('resumes.edit', $resume) }}#{{ $c['next']['section'] }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            <span class="rounded-full bg-indigo-100 px-1.5 py-0.5 text-[10px] uppercase tracking-wide text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">Next</span>
            {{ $c['next']['label'] }} — {{ $c['next']['hint'] }}
        </a>
    @else
        <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Resume complete — ready to download</p>
    @endif
</div>

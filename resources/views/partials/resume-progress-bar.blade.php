@php
    $percent = $percent ?? 0;
    $showLabel = $showLabel ?? true;
    $size = $size ?? 'md';
    $barClass = \App\Support\ResumeCompleteness::barClass($percent);
    $textClass = \App\Support\ResumeCompleteness::textClass($percent);
    $height = $size === 'sm' ? 'h-1.5' : 'h-2.5';
@endphp

<div class="w-full">
    @if ($showLabel)
        <div class="mb-1 flex items-center justify-between gap-2">
            <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Progress</span>
            <span class="text-xs font-bold tabular-nums {{ $textClass }}">{{ $percent }}%</span>
        </div>
    @endif
    <div class="{{ $height }} overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
        <div class="h-full rounded-full transition-all duration-500 {{ $barClass }}" style="width: {{ $percent }}%"></div>
    </div>
</div>

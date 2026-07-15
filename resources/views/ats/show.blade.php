<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">ATS Result</h1>
                <p class="text-sm text-slate-500">{{ $check->job_title ?: 'Untitled role' }}</p>
            </div>
            <a href="{{ route('ats.create') }}" class="admin-btn-primary">New Check</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6">
        @php
            $c = $check->scoreColor();
            $deg = $check->score * 3.6;
            $ring = ['green' => '#16a34a', 'blue' => '#2563eb', 'yellow' => '#ca8a04', 'red' => '#dc2626'][$c] ?? '#4f46e5';
            $scoreTextClass = match($c) {
                'green' => 'text-emerald-600',
                'blue' => 'text-blue-600',
                'yellow', 'amber' => 'text-amber-600',
                'red' => 'text-red-600',
                default => 'text-indigo-600',
            };
        @endphp

        <div class="admin-card flex flex-col items-center gap-6 p-6 sm:flex-row">
            <div class="relative shrink-0" style="width:130px;height:130px;">
                <div class="rounded-full" style="width:130px;height:130px;background: conic-gradient({{ $ring }} {{ $deg }}deg, #e5e7eb {{ $deg }}deg);"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="flex flex-col items-center justify-center rounded-full bg-white" style="width:96px;height:96px;">
                        <span class="text-3xl font-bold {{ $scoreTextClass }}">{{ $check->score }}</span>
                        <span class="text-xs text-slate-500">/ 100</span>
                    </div>
                </div>
            </div>
            <div class="text-center sm:text-left">
                <p class="text-lg font-semibold {{ $scoreTextClass }}">{{ $check->scoreLabel() }}</p>
                <p class="mt-1 text-sm text-slate-600">Resume: <span class="font-medium">{{ $check->resume?->title ?? 'deleted' }}</span></p>
                <p class="text-sm text-slate-500">{{ count($check->matched_keywords ?? []) }} matched · {{ count($check->missing_keywords ?? []) }} missing keywords</p>
                @if($check->resume)
                    <div class="mt-3 flex justify-center gap-2 sm:justify-start">
                        <a href="{{ route('resumes.edit', $check->resume) }}" class="admin-btn-primary">Improve Resume</a>
                        <a href="{{ route('resumes.pdf', $check->resume) }}" class="admin-btn-secondary">Download PDF</a>
                    </div>
                @endif
            </div>
        </div>

        @if ($check->resume)
            @include('partials.resume-completeness', ['resume' => $check->resume])
        @endif

        @if(!empty($check->suggestions))
            <div class="admin-card p-6">
                <h3 class="mb-3 font-semibold text-slate-900">Suggestions to improve your score</h3>
                <ul class="space-y-2">
                    @foreach($check->suggestions as $s)
                        <li class="flex gap-2 text-sm text-slate-700">
                            <span class="text-indigo-500">→</span><span>{{ $s }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="admin-card p-6">
                <h3 class="mb-3 font-semibold text-emerald-700">Matched keywords ({{ count($check->matched_keywords ?? []) }})</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($check->matched_keywords ?? [] as $kw)
                        <span class="admin-badge bg-emerald-50 text-emerald-700">{{ $kw }}</span>
                    @empty
                        <p class="text-sm text-slate-400">No keywords matched.</p>
                    @endforelse
                </div>
            </div>
            <div class="admin-card p-6">
                <h3 class="mb-3 font-semibold text-red-700">Missing keywords ({{ count($check->missing_keywords ?? []) }})</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($check->missing_keywords ?? [] as $kw)
                        <span class="admin-badge bg-red-50 text-red-700">{{ $kw }}</span>
                    @empty
                        <p class="text-sm text-slate-400">Great — nothing important missing!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <a href="{{ route('ats.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">← Back to all checks</a>
    </div>
</x-app-layout>

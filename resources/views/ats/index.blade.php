<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">ATS Checks</h1>
                <p class="text-sm text-slate-500">Optimize your resume for applicant tracking systems</p>
            </div>
            <a href="{{ route('ats.create') }}" class="admin-btn-primary">+ New Check</a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @include('partials.alert')

        @forelse ($checks as $check)
            @php
                $scoreClass = match($check->scoreColor()) {
                    'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                    'yellow', 'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
                    'red' => 'bg-red-50 text-red-700 ring-red-100',
                    default => 'bg-slate-100 text-slate-700 ring-slate-200',
                };
            @endphp
            <div class="admin-card flex items-center justify-between gap-4 p-5">
                <a href="{{ route('ats.show', $check) }}" class="flex min-w-0 flex-1 items-center gap-4 group">
                    <span class="admin-badge shrink-0 ring-1 {{ $scoreClass }}">{{ $check->score }}%</span>
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-slate-900 group-hover:text-indigo-600">{{ $check->job_title ?: 'Untitled role' }}</p>
                        <p class="truncate text-xs text-slate-500">
                            {{ $check->resume?->title ?? 'Resume deleted' }} · {{ $check->scoreLabel() }} · {{ $check->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>
                <form method="POST" action="{{ route('ats.destroy', $check) }}" onsubmit="return confirm('Delete this check?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Delete</button>
                </form>
            </div>
        @empty
            <div class="admin-card p-12 text-center">
                <p class="text-slate-500">No ATS checks yet.</p>
                <a href="{{ route('ats.create') }}" class="admin-btn-primary mt-4 inline-flex">Run your first ATS check</a>
            </div>
        @endforelse

        @if ($checks->hasPages())
            <div>{{ $checks->links() }}</div>
        @endif
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Dashboard</a>
                <span>/</span>
                <span>My Resumes</span>
            </div>
            <h1 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl dark:text-slate-100">My Resumes</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $resumes->count() }} resume{{ $resumes->count() === 1 ? '' : 's' }}</p>
        </div>
    </x-slot>

    <div class="space-y-4">
        @include('partials.alert')

        @if (config('billing.enabled'))
            @php
                $user = auth()->user();
                $rd = $user->remainingDownloads();
            @endphp
            @if ($user->hasPlanAccess() && ! is_null($rd))
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-300">
                    PDF downloads remaining this period: <strong>{{ $rd }}</strong>
                </div>
            @elseif (! $user->hasPlanAccess())
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-800/50 dark:bg-amber-950/30">
                    <p class="text-sm text-amber-800 dark:text-amber-300">Subscribe to a plan to download PDFs.</p>
                    <a href="{{ route('plans.index') }}" class="admin-btn-primary">View plans</a>
                </div>
            @endif
        @endif

        @forelse ($resumes as $resume)
            <div class="admin-card flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $resume->title }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $resume->full_name }} @if($resume->headline)&middot; {{ $resume->headline }}@endif</p>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        @php
                            $themeMeta = \App\Support\ResumeThemes::get($resume->template);
                            $themeBadge = match ($themeMeta['accent'] ?? 'indigo') {
                                'violet' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300',
                                'blue' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300',
                                'teal' => 'bg-teal-50 text-teal-700 dark:bg-teal-500/15 dark:text-teal-300',
                                'emerald' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
                                'amber' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
                                'rose' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300',
                                'stone' => 'bg-stone-100 text-stone-700 dark:bg-stone-500/15 dark:text-stone-300',
                                'slate' => 'bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-300',
                                default => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300',
                            };
                        @endphp
                        <span class="admin-badge {{ $themeBadge }}">{{ $themeMeta['label'] }}</span>
                        <span class="text-xs text-slate-400">updated {{ $resume->updated_at->diffForHumans() }}</span>
                    </div>
                    @include('partials.resume-completeness-inline', ['resume' => $resume])
                </div>
                @include('partials.resume-actions', [
                    'resume' => $resume,
                    'compact' => false,
                    'showAts' => true,
                    'showDuplicate' => true,
                    'showDelete' => true,
                ])
            </div>
        @empty
            <div class="admin-card p-12 text-center">
                <p class="text-slate-500 dark:text-slate-400">You haven't created any resumes yet.</p>
                <a href="{{ route('resumes.create') }}" class="admin-btn-primary mt-4 inline-flex">Create your first resume</a>
            </div>
        @endforelse
    </div>
</x-app-layout>

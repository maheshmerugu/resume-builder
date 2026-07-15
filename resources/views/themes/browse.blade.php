<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold panel-title sm:text-2xl">Resume Themes</h1>
                <p class="text-sm panel-muted">{{ $themeCount }}+ professionally designed, ATS-friendly themes</p>
            </div>
            <a href="{{ route('resumes.create') }}" class="admin-btn-primary">Create resume</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @include('partials.alert')

        <div class="admin-card p-5">
            <p class="text-sm panel-muted">Click any theme to start a new resume with that design. You can switch themes anytime while editing.</p>
        </div>

        @include('partials.theme-gallery', [
            'themes' => $themes,
            'categories' => $categories,
            'mode' => 'link',
            'showFilters' => true,
        ])
    </div>
</x-app-layout>

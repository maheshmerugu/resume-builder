<x-app-layout>
    <x-slot name="header">
        <div class="flex min-w-0 flex-1 items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="hidden items-center gap-2 text-xs text-slate-500 dark:text-slate-400 sm:flex">
                    <a href="{{ route('resumes.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">My Resumes</a>
                    <span>/</span>
                    <span class="truncate">Edit</span>
                </div>
                <h1 class="truncate text-lg font-bold text-slate-900 dark:text-slate-100 sm:text-xl">{{ $resume->title }}</h1>
            </div>
            <div class="hidden shrink-0 flex-wrap items-center gap-2 lg:flex">
                <a href="{{ route('resumes.show', $resume) }}" class="admin-btn-secondary">Preview</a>
                <a href="{{ route('resumes.pdf', $resume) }}" class="admin-btn-secondary">PDF</a>
                <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="admin-btn-primary">Check ATS</a>
            </div>
        </div>
    </x-slot>

    <div class="mb-4 flex flex-wrap items-center gap-2 lg:hidden">
        <a href="{{ route('resumes.show', $resume) }}" class="admin-btn-secondary flex-1 justify-center sm:flex-none">Preview</a>
        <a href="{{ route('resumes.pdf', $resume) }}" class="admin-btn-secondary flex-1 justify-center sm:flex-none">PDF</a>
        <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="admin-btn-primary flex-1 justify-center sm:flex-none">Check ATS</a>
    </div>

    @include('resumes._form', [
        'action' => route('resumes.update', $resume),
        'method' => 'PUT',
        'resume' => $resume,
        'templates' => $templates,
    ])
</x-app-layout>

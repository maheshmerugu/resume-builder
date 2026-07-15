<x-app-layout>
    <x-slot name="header">
        <div class="min-w-0">
            <h1 class="truncate text-lg font-bold text-slate-900 dark:text-slate-100 sm:text-xl">Create Resume</h1>
            <p class="hidden truncate text-sm text-slate-500 dark:text-slate-400 sm:block">Build a new professional resume</p>
        </div>
    </x-slot>

    <p class="-mt-2 mb-1 text-sm text-slate-500 dark:text-slate-400 sm:hidden">Build a new professional resume</p>

    @include('resumes._form', [
        'action' => route('resumes.store'),
        'method' => 'POST',
        'resume' => $resume,
        'templates' => $templates,
    ])
</x-app-layout>

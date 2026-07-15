<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between lg:gap-8">
            <div class="min-w-0 shrink-0">
                <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ __('Create Resume') }}</h2>
                <p class="mt-1 text-sm text-gray-500">Build a new professional resume</p>
            </div>
            <div id="resume-progress-header" class="w-full min-w-0 lg:max-w-md xl:max-w-lg"></div>
        </div>
    </x-slot>

    @include('resumes._form', [
        'action' => route('resumes.store'),
        'method' => 'POST',
        'resume' => $resume,
        'templates' => $templates,
    ])
</x-app-layout>

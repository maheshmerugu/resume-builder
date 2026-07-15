<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ __('Create Resume') }}</h2>
    </x-slot>

    @include('resumes._form', [
        'action' => route('resumes.store'),
        'method' => 'POST',
        'resume' => $resume,
        'templates' => $templates,
    ])
</x-app-layout>

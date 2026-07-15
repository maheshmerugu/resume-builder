<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Resume') }}</h2>
    </x-slot>

    @include('resumes._form', [
        'action' => route('resumes.store'),
        'method' => 'POST',
        'resume' => $resume,
        'templates' => $templates,
    ])
</x-app-layout>

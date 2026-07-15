<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Resume') }}</h2>
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('resumes.show', $resume) }}" class="rounded-md border px-3 py-1.5 text-gray-700 hover:bg-gray-50">Full Preview</a>
                <a href="{{ route('resumes.pdf', $resume) }}" class="rounded-md border px-3 py-1.5 text-gray-700 hover:bg-gray-50">Download PDF</a>
                <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-500">Check ATS</a>
            </div>
        </div>
    </x-slot>

    @include('resumes._form', [
        'action' => route('resumes.update', $resume),
        'method' => 'PUT',
        'resume' => $resume,
        'templates' => $templates,
    ])
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between lg:gap-6">
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500">
                    <a href="{{ route('resumes.index') }}" class="hover:text-indigo-600">My Resumes</a>
                    <span>/</span>
                    <span>Edit</span>
                </div>
                <h2 class="mt-1 truncate text-xl font-bold text-gray-900 sm:text-2xl">{{ $resume->title }}</h2>
                <p class="mt-0.5 text-sm text-gray-500">Updated {{ $resume->updated_at->diffForHumans() }}</p>
            </div>
            <div id="resume-progress-header" class="w-full min-w-0 lg:max-w-sm xl:max-w-md"></div>
            <div class="flex shrink-0 flex-wrap items-center gap-2">
                <a href="{{ route('resumes.show', $resume) }}" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Preview</a>
                <a href="{{ route('resumes.pdf', $resume) }}" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">PDF</a>
                <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500">Check ATS</a>
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

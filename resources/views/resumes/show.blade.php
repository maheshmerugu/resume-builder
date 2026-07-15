<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Preview — {{ $resume->title }}</h2>
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('resumes.edit', $resume) }}" class="rounded-md border px-3 py-1.5 text-gray-700 hover:bg-gray-50">Edit</a>
                <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-500">Check ATS</a>
                <a href="{{ route('resumes.pdf', $resume) }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-500">Download PDF</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-10">
                @include($template, ['resume' => $resume])
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Resumes') }}</h2>
            <a href="{{ route('resumes.create') }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                + New Resume
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @forelse ($resumes as $resume)
                <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $resume->title }}</p>
                        <p class="text-sm text-gray-500">{{ $resume->full_name }} @if($resume->headline)&middot; {{ $resume->headline }}@endif</p>
                        <div class="mt-2 flex items-center gap-3">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">{{ ucfirst($resume->template) }}</span>
                            <span class="text-xs text-gray-500">{{ $resume->completeness() }}% complete</span>
                            <span class="text-xs text-gray-400">updated {{ $resume->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <a href="{{ route('resumes.show', $resume) }}" class="rounded-md border px-3 py-1.5 text-gray-700 hover:bg-gray-50">Preview</a>
                        <a href="{{ route('resumes.edit', $resume) }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-500">Edit</a>
                        <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-500">Check ATS</a>
                        <a href="{{ route('resumes.pdf', $resume) }}" class="rounded-md border px-3 py-1.5 text-gray-700 hover:bg-gray-50">Download PDF</a>
                        <form method="POST" action="{{ route('resumes.duplicate', $resume) }}">
                            @csrf
                            <button type="submit" class="rounded-md border px-3 py-1.5 text-gray-700 hover:bg-gray-50">Duplicate</button>
                        </form>
                        <form method="POST" action="{{ route('resumes.destroy', $resume) }}" onsubmit="return confirm('Delete this resume?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-red-600 hover:bg-red-50">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <p class="text-gray-500">You haven't created any resumes yet.</p>
                    <a href="{{ route('resumes.create') }}" class="mt-4 inline-block rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Create your first resume</a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

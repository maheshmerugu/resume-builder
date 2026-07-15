<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('ATS Checks') }}</h2>
            <a href="{{ route('ats.create') }}" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">+ New Check</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @forelse ($checks as $check)
                <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between gap-4">
                    <a href="{{ route('ats.show', $check) }}" class="flex items-center gap-4 min-w-0 group">
                        <span class="shrink-0 inline-flex items-center justify-center h-12 w-12 rounded-full text-sm font-bold text-{{ $check->scoreColor() }}-700 bg-{{ $check->scoreColor() }}-100">
                            {{ $check->score }}
                        </span>
                        <div class="min-w-0">
                            <p class="font-medium text-gray-800 group-hover:text-indigo-600 truncate">{{ $check->job_title ?: 'Untitled role' }}</p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $check->resume?->title ?? 'Resume deleted' }} &middot;
                                {{ $check->scoreLabel() }} &middot;
                                {{ $check->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('ats.destroy', $check) }}" onsubmit="return confirm('Delete this check?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:underline">Delete</button>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <p class="text-gray-500">No ATS checks yet.</p>
                    <a href="{{ route('ats.create') }}" class="mt-4 inline-block rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">Run your first ATS check</a>
                </div>
            @endforelse

            @if ($checks->hasPages())
                <div>{{ $checks->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

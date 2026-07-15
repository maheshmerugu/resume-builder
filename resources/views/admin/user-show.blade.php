<x-admin-layout>
    <x-slot name="header">
        <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="truncate text-lg font-bold text-slate-900 sm:text-xl">{{ $user->name }}</h1>
            <a href="{{ route('admin.users') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">&larr; Back to users</a>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $user->name }}
                            @if ($user->is_admin)
                                <span class="ml-1 inline-block rounded bg-gray-800 px-1.5 py-0.5 text-[10px] font-semibold text-white align-middle">ADMIN</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">Joined {{ $user->created_at->format('M j, Y') }} &middot; {{ $user->resumes_count }} resumes &middot; {{ $user->ats_checks_count }} ATS checks</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.users.toggleAdmin', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button class="rounded-md border border-gray-300 px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50">
                                {{ $user->is_admin ? 'Revoke admin' : 'Make admin' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Delete {{ $user->name }} and all their data?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-md border border-red-300 px-3 py-1.5 text-xs text-red-600 hover:bg-red-50">Delete user</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Resumes</h3>
                @forelse ($resumes as $resume)
                    <div class="flex items-center justify-between py-3 border-b last:border-0">
                        <div>
                            <p class="font-medium text-gray-800">{{ $resume->title }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($resume->template) }} template &middot; {{ $resume->ats_checks_count }} checks &middot; updated {{ $resume->updated_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('resumes.pdf', $resume) }}" class="text-sm text-gray-600 hover:underline">PDF</a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No resumes.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Recent ATS Checks</h3>
                @forelse ($checks as $check)
                    <div class="flex items-center justify-between py-3 border-b last:border-0">
                        <div class="pr-3">
                            <p class="font-medium text-gray-800">{{ $check->job_title ?: 'Untitled role' }}</p>
                            <p class="text-xs text-gray-500">{{ $check->resume?->title ?? 'Resume deleted' }} &middot; {{ $check->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-bold text-{{ $check->scoreColor() }}-700 bg-{{ $check->scoreColor() }}-100">{{ $check->score }}%</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No ATS checks.</p>
                @endforelse
            </div>
        </div>
</x-admin-layout>

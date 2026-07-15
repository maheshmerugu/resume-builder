<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Users</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline">&larr; Back to admin</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="GET" action="{{ route('admin.users') }}" class="mb-4 flex gap-2">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search name or email…"
                           class="w-full sm:w-72 rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Search</button>
                    @if ($search)
                        <a href="{{ route('admin.users') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Clear</a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2 pr-3 font-medium">User</th>
                                <th class="py-2 px-3 font-medium">Resumes</th>
                                <th class="py-2 px-3 font-medium">Checks</th>
                                <th class="py-2 px-3 font-medium">Joined</th>
                                <th class="py-2 px-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b last:border-0">
                                    <td class="py-3 pr-3">
                                        <a href="{{ route('admin.users.show', $user) }}" class="font-medium text-gray-800 hover:text-indigo-600">{{ $user->name }}</a>
                                        @if ($user->is_admin)
                                            <span class="ml-1 inline-block rounded bg-gray-800 px-1.5 py-0.5 text-[10px] font-semibold text-white align-middle">ADMIN</span>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </td>
                                    <td class="py-3 px-3 text-gray-700">{{ $user->resumes_count }}</td>
                                    <td class="py-3 px-3 text-gray-700">{{ $user->ats_checks_count }}</td>
                                    <td class="py-3 px-3 text-gray-500">{{ $user->created_at->format('M j, Y') }}</td>
                                    <td class="py-3 px-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.users.toggleAdmin', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-md border border-gray-300 px-2.5 py-1 text-xs text-gray-700 hover:bg-gray-50">
                                                    {{ $user->is_admin ? 'Revoke admin' : 'Make admin' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Delete {{ $user->name }} and all their data?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-md border border-red-300 px-2.5 py-1 text-xs text-red-600 hover:bg-red-50">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-6 text-center text-gray-500">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

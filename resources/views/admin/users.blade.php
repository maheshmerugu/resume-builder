<x-admin-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Users</h1>
    </x-slot>

    <div class="space-y-6">
        @include('admin.partials.alert')

        <div class="admin-card">
            <div class="admin-card-header">
                <p class="text-sm text-slate-500">Search and manage registered users</p>
                <form method="GET" action="{{ route('admin.users') }}" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search name or email…"
                           class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-64">
                    <button class="admin-btn-primary">Search</button>
                    @if ($search)
                        <a href="{{ route('admin.users') }}" class="admin-btn-secondary">Clear</a>
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table min-w-full">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Resumes</th>
                            <th>Checks</th>
                            <th>Joined</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="font-semibold text-slate-900 hover:text-indigo-600">{{ $user->name }}</a>
                                    @if ($user->is_admin)
                                        <span class="admin-badge ml-2 bg-indigo-50 text-indigo-700">Admin</span>
                                    @endif
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </td>
                                <td>{{ $user->resumes_count }}</td>
                                <td>{{ $user->ats_checks_count }}</td>
                                <td class="text-slate-500">{{ $user->created_at->format('M j, Y') }}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.users.toggleAdmin', $user) }}">
                                            @csrf @method('PATCH')
                                            <button class="admin-btn-secondary !px-3 !py-1.5 !text-xs">{{ $user->is_admin ? 'Revoke' : 'Make admin' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="rounded-xl border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center text-slate-500">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $users->links() }}</div>
        </div>
    </div>
</x-admin-layout>

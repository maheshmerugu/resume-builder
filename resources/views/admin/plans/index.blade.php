<x-admin-layout>
    <x-slot name="header">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Plans</h1>
            <a href="{{ route('admin.plans.create') }}" class="admin-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Plan
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @include('admin.partials.alert')

        <div class="admin-card">
            <div class="overflow-x-auto">
                <table class="admin-table min-w-full">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Price</th>
                            <th>Resumes</th>
                            <th>Downloads</th>
                            <th>Active Subs</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plans as $plan)
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $plan->name }}</p>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @if ($plan->is_featured)<span class="admin-badge bg-indigo-50 text-indigo-700">Featured</span>@endif
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">{{ $plan->slug }} · {{ $plan->intervalLabel() }}</p>
                                </td>
                                <td class="font-semibold text-slate-900">{{ $plan->priceLabel() }}</td>
                                <td>{{ is_null($plan->resume_limit) ? '∞' : $plan->resume_limit }}</td>
                                <td>{{ is_null($plan->download_limit) ? '∞' : $plan->download_limit }}</td>
                                <td>{{ $plan->subscriptions_count }}</td>
                                <td>
                                    <span class="admin-badge {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $plan->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="admin-btn-secondary !px-3 !py-1.5 !text-xs">Edit</a>
                                        <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete {{ $plan->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="rounded-xl border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-12 text-center text-slate-500">No plans yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>

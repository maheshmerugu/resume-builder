<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Plans</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline">&larr; Admin</a>
                <a href="{{ route('admin.plans.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">+ New Plan</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="py-2 pr-3 font-medium">Plan</th>
                            <th class="py-2 px-3 font-medium">Price</th>
                            <th class="py-2 px-3 font-medium">Resumes</th>
                            <th class="py-2 px-3 font-medium">Downloads</th>
                            <th class="py-2 px-3 font-medium">Active subs</th>
                            <th class="py-2 px-3 font-medium">Status</th>
                            <th class="py-2 px-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plans as $plan)
                            <tr class="border-b last:border-0">
                                <td class="py-3 pr-3">
                                    <span class="font-medium text-gray-800">{{ $plan->name }}</span>
                                    @if ($plan->is_default)<span class="ml-1 rounded bg-gray-800 px-1.5 py-0.5 text-[10px] font-semibold text-white">DEFAULT</span>@endif
                                    @if ($plan->is_featured)<span class="ml-1 rounded bg-indigo-600 px-1.5 py-0.5 text-[10px] font-semibold text-white">FEATURED</span>@endif
                                    <p class="text-xs text-gray-500">{{ $plan->slug }} &middot; {{ $plan->intervalLabel() }}</p>
                                </td>
                                <td class="py-3 px-3 text-gray-700">{{ $plan->priceLabel() }}</td>
                                <td class="py-3 px-3 text-gray-700">{{ is_null($plan->resume_limit) ? '∞' : $plan->resume_limit }}</td>
                                <td class="py-3 px-3 text-gray-700">{{ is_null($plan->download_limit) ? '∞' : $plan->download_limit }}</td>
                                <td class="py-3 px-3 text-gray-700">{{ $plan->subscriptions_count }}</td>
                                <td class="py-3 px-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $plan->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $plan->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="rounded-md border border-gray-300 px-2.5 py-1 text-xs text-gray-700 hover:bg-gray-50">Edit</a>
                                        @unless ($plan->is_default)
                                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete the {{ $plan->name }} plan?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-md border border-red-300 px-2.5 py-1 text-xs text-red-600 hover:bg-red-50">Delete</button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-6 text-center text-gray-500">No plans yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

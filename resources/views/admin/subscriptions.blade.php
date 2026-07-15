<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subscriptions</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline">&larr; Admin</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-wrap gap-2">
                @foreach (['' => 'All', 'active' => 'Active', 'cancelled' => 'Cancelled', 'expired' => 'Expired'] as $value => $label)
                    <a href="{{ route('admin.subscriptions', ['status' => $value]) }}"
                       class="rounded-full px-4 py-1.5 text-sm font-medium {{ $status === $value ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="py-2 pr-3 font-medium">User</th>
                            <th class="py-2 px-3 font-medium">Plan</th>
                            <th class="py-2 px-3 font-medium">Amount</th>
                            <th class="py-2 px-3 font-medium">Status</th>
                            <th class="py-2 px-3 font-medium">Started</th>
                            <th class="py-2 px-3 font-medium">Ends</th>
                            <th class="py-2 px-3 font-medium">Ref</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $sub)
                            <tr class="border-b last:border-0">
                                <td class="py-3 pr-3">
                                    <p class="font-medium text-gray-800">{{ $sub->user?->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-500">{{ $sub->user?->email }}</p>
                                </td>
                                <td class="py-3 px-3 text-gray-700">{{ $sub->plan?->name ?? '—' }}</td>
                                <td class="py-3 px-3 text-gray-700">₹{{ number_format($sub->amount_paid) }}</td>
                                <td class="py-3 px-3">
                                    @php $c = ['active' => 'green', 'cancelled' => 'yellow', 'expired' => 'red'][$sub->status] ?? 'gray'; @endphp
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">{{ ucfirst($sub->status) }}</span>
                                </td>
                                <td class="py-3 px-3 text-gray-500">{{ $sub->starts_at?->format('M j, Y') ?? '—' }}</td>
                                <td class="py-3 px-3 text-gray-500">{{ $sub->ends_at?->format('M j, Y') ?? 'Never' }}</td>
                                <td class="py-3 px-3 text-gray-400 text-xs">{{ $sub->payment_reference ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-6 text-center text-gray-500">No subscriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $subscriptions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

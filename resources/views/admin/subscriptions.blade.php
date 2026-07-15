<x-admin-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Subscriptions</h1>
    </x-slot>

    <div class="space-y-6">
        <div class="flex flex-wrap gap-2">
            @foreach (['' => 'All', 'active' => 'Active', 'cancelled' => 'Cancelled', 'expired' => 'Expired'] as $value => $label)
                <a href="{{ route('admin.subscriptions', ['status' => $value]) }}"
                   class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $status === $value ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="admin-card">
            <div class="overflow-x-auto">
                <table class="admin-table min-w-full">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th>Ends</th>
                            <th>Payment ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $sub)
                            @php
                                $statusClass = match($sub->status) {
                                    'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                                    'cancelled' => 'bg-amber-50 text-amber-700 ring-amber-100',
                                    'expired' => 'bg-red-50 text-red-700 ring-red-100',
                                    default => 'bg-slate-100 text-slate-700 ring-slate-200',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $sub->user?->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">{{ $sub->user?->email }}</p>
                                </td>
                                <td>{{ $sub->plan?->name ?? '—' }}</td>
                                <td class="font-semibold">₹{{ number_format($sub->amount_paid) }}</td>
                                <td><span class="admin-badge ring-1 {{ $statusClass }}">{{ ucfirst($sub->status) }}</span></td>
                                <td class="text-slate-500">{{ $sub->starts_at?->format('M j, Y') ?? '—' }}</td>
                                <td class="text-slate-500">{{ $sub->ends_at?->format('M j, Y') ?? 'Never' }}</td>
                                <td class="max-w-[120px] truncate text-xs text-slate-400">{{ $sub->payment_reference ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-12 text-center text-slate-500">No subscriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $subscriptions->links() }}</div>
        </div>
    </div>
</x-admin-layout>

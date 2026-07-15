<x-admin-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold panel-title sm:text-2xl">Dashboard</h1>
            <p class="text-sm panel-muted">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    <div id="admin-dashboard-charts" class="space-y-6">
        @include('admin.partials.alert')

        {{-- Revenue overview --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-5 text-white">
                    <p class="text-sm font-medium text-amber-100">Total Revenue</p>
                    <p class="mt-2 text-3xl font-extrabold">₹{{ number_format($stats['revenue']) }}</p>
                    <p class="mt-1 text-xs text-amber-100">{{ $stats['payments_count'] }} payments</p>
                </div>
            </div>
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white">
                    <p class="text-sm font-medium text-emerald-100">Revenue This Month</p>
                    <p class="mt-2 text-3xl font-extrabold">₹{{ number_format($stats['revenue_month']) }}</p>
                    <p class="mt-1 text-xs text-emerald-100">
                        @if ($stats['revenue_growth'] >= 0)
                            +{{ $stats['revenue_growth'] }}% vs last month
                        @else
                            {{ $stats['revenue_growth'] }}% vs last month
                        @endif
                    </p>
                </div>
            </div>
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-violet-500 to-purple-600 p-5 text-white">
                    <p class="text-sm font-medium text-violet-100">Est. MRR</p>
                    <p class="mt-2 text-3xl font-extrabold">₹{{ number_format($stats['mrr']) }}</p>
                    <p class="mt-1 text-xs text-violet-100">Active monthly plans</p>
                </div>
            </div>
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-indigo-500 to-violet-600 p-5 text-white">
                    <p class="text-sm font-medium text-indigo-100">Avg. Payment</p>
                    <p class="mt-2 text-3xl font-extrabold">₹{{ number_format($stats['avg_payment']) }}</p>
                    <p class="mt-1 text-xs text-indigo-100">{{ $stats['active_subs'] }} active subs</p>
                </div>
            </div>
        </div>

        {{-- Charts row 1 --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="admin-card xl:col-span-2">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold panel-title">Revenue Trend</h2>
                    <span class="text-sm panel-muted">Last 6 months</span>
                </div>
                <div class="h-72 px-4 pb-4 sm:px-6">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold panel-title">Revenue by Plan</h2>
                </div>
                <div class="h-72 px-4 pb-4 sm:px-6">
                    <canvas id="planRevenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Charts row 2 --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold panel-title">New User Signups</h2>
                    <span class="text-sm panel-muted">Last 6 months</span>
                </div>
                <div class="h-64 px-4 pb-4 sm:px-6">
                    <canvas id="userSignupsChart"></canvas>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold panel-title">Subscription Status</h2>
                </div>
                <div class="h-64 px-4 pb-4 sm:px-6">
                    <canvas id="subscriptionStatusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Platform stats --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="admin-card overflow-hidden">
                <div class="bg-gradient-to-br from-blue-500 to-cyan-600 p-5 text-white">
                    <p class="text-sm font-medium text-blue-100">Total Users</p>
                    <p class="mt-2 text-3xl font-extrabold">{{ $stats['users'] }}</p>
                    <p class="mt-1 text-xs text-blue-100">+{{ $stats['new_users_7d'] }} this week</p>
                </div>
            </div>
            <div class="admin-card p-4">
                <span class="admin-badge bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-300">ATS Checks</span>
                <p class="mt-3 text-2xl font-bold panel-title">{{ $stats['checks'] }}</p>
            </div>
            <div class="admin-card p-4">
                <span class="admin-badge bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300">Avg ATS Score</span>
                <p class="mt-3 text-2xl font-bold panel-title">{{ $stats['avg_score'] }}%</p>
            </div>
            <div class="admin-card p-4">
                <span class="admin-badge bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-300">Total Resumes</span>
                <p class="mt-3 text-2xl font-bold panel-title">{{ $stats['resumes'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            {{-- Recent payments --}}
            <div class="admin-card xl:col-span-2">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold panel-title">Recent Payments</h2>
                    <a href="{{ route('admin.subscriptions') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">All subscriptions →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-full">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPayments as $payment)
                                <tr>
                                    <td>
                                        <p class="font-semibold panel-title">{{ $payment->user?->name ?? '—' }}</p>
                                        <p class="text-xs panel-muted">{{ $payment->user?->email }}</p>
                                    </td>
                                    <td>{{ $payment->plan?->name ?? '—' }}</td>
                                    <td class="font-semibold text-emerald-600 dark:text-emerald-400">₹{{ number_format($payment->amount_paid) }}</td>
                                    <td>
                                        <span class="admin-badge {{ $payment->status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-300' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="panel-muted">{{ $payment->created_at->format('M j, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-10 text-center panel-muted">No payments recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="admin-card p-5">
                    <h2 class="mb-4 text-lg font-bold panel-title">Revenue Summary</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                            <span class="panel-muted">Last month</span>
                            <span class="font-bold panel-title">₹{{ number_format($stats['revenue_last_month']) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                            <span class="panel-muted">This month</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">₹{{ number_format($stats['revenue_month']) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-amber-50 px-4 py-3 dark:bg-amber-500/10">
                            <span class="text-amber-800 dark:text-amber-300">All-time total</span>
                            <span class="font-bold text-amber-700 dark:text-amber-300">₹{{ number_format($stats['revenue']) }}</span>
                        </div>
                    </div>
                </div>

                <div class="admin-card p-5">
                    <h2 class="mb-4 text-lg font-bold panel-title">Top Checked Resumes</h2>
                    @forelse ($topResumes as $resume)
                        <div class="flex items-center justify-between border-b border-slate-100 py-3 last:border-0 dark:border-slate-800">
                            <div class="min-w-0 pr-3">
                                <p class="truncate text-sm font-semibold panel-title">{{ $resume->title }}</p>
                                <p class="truncate text-xs panel-muted">{{ $resume->user?->name ?? 'Unknown' }}</p>
                            </div>
                            <span class="admin-badge bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-300">{{ $resume->ats_checks_count }}</span>
                        </div>
                    @empty
                        <p class="text-sm panel-muted">No data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="admin-card xl:col-span-2">
                <div class="admin-card-header">
                    <h2 class="text-lg font-bold panel-title">Recent Users</h2>
                    <a href="{{ route('admin.users') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">View all →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-full">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Resumes</th>
                                <th>Checks</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3">
                                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-xs font-bold text-white">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                            <div>
                                                <p class="font-semibold panel-title">{{ $user->name }}</p>
                                                <p class="text-xs panel-muted">{{ $user->email }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td><span class="admin-badge bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-300">{{ $user->resumes_count }}</span></td>
                                    <td><span class="admin-badge bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-300">{{ $user->ats_checks_count }}</span></td>
                                    <td class="panel-muted">{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-10 text-center panel-muted">No users yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="admin-card p-5">
                <h2 class="mb-4 text-lg font-bold panel-title">Template Usage</h2>
                @php $totalTpl = array_sum($templateUsage) ?: 1; @endphp
                @forelse ($templateUsage as $template => $count)
                    <div class="mb-4 last:mb-0">
                        <div class="mb-1.5 flex justify-between text-sm">
                            <span class="font-medium capitalize panel-title">{{ $template }}</span>
                            <span class="panel-muted">{{ $count }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                            <div class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ round($count / $totalTpl * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm panel-muted">No resumes yet.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="text-lg font-bold panel-title">Recent ATS Checks</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table min-w-full">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>User</th>
                            <th>Resume</th>
                            <th>Score</th>
                            <th>When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentChecks as $check)
                            @php
                                $scoreClass = match($check->scoreColor()) {
                                    'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-100 dark:bg-emerald-500/15 dark:text-emerald-300',
                                    'yellow', 'amber' => 'bg-amber-50 text-amber-700 ring-amber-100 dark:bg-amber-500/15 dark:text-amber-300',
                                    'red' => 'bg-red-50 text-red-700 ring-red-100 dark:bg-red-500/15 dark:text-red-300',
                                    default => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-500/15 dark:text-slate-300',
                                };
                            @endphp
                            <tr>
                                <td class="font-semibold panel-title">{{ $check->job_title ?: 'Untitled role' }}</td>
                                <td>{{ $check->user?->name ?? '—' }}</td>
                                <td class="panel-muted">{{ $check->resume?->title ?? 'Deleted' }}</td>
                                <td><span class="admin-badge ring-1 {{ $scoreClass }}">{{ $check->score }}%</span></td>
                                <td class="panel-muted">{{ $check->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-10 text-center panel-muted">No ATS checks yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.adminChartData = @json($chartData);
        </script>
        @vite('resources/js/admin-dashboard.js')
    @endpush
</x-admin-layout>

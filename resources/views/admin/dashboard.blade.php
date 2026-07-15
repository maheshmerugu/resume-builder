<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                    Manage Plans
                </a>
                <a href="{{ route('admin.subscriptions') }}" class="inline-flex items-center gap-2 rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Subscriptions
                </a>
                <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Manage Users
                </a>
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

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $cards = [
                        ['label' => 'Total Users', 'value' => $stats['users'], 'color' => 'indigo'],
                        ['label' => 'Active Subscriptions', 'value' => $stats['active_subs'], 'color' => 'green'],
                        ['label' => 'Revenue (₹)', 'value' => '₹'.number_format($stats['revenue']), 'color' => 'green'],
                        ['label' => 'Resumes', 'value' => $stats['resumes'], 'color' => 'blue'],
                        ['label' => 'ATS Checks', 'value' => $stats['checks'], 'color' => 'purple'],
                        ['label' => 'Avg ATS Score', 'value' => $stats['avg_score'].'%', 'color' => 'amber'],
                        ['label' => 'New Users (7d)', 'value' => $stats['new_users_7d'], 'color' => 'gray'],
                        ['label' => 'Admins', 'value' => $stats['admins'], 'color' => 'gray'],
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-{{ $card['color'] }}-600">{{ $card['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent users --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Recent Users</h3>
                        <a href="{{ route('admin.users') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2 pr-3 font-medium">User</th>
                                    <th class="py-2 px-3 font-medium">Resumes</th>
                                    <th class="py-2 px-3 font-medium">Checks</th>
                                    <th class="py-2 px-3 font-medium">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $user)
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
                                        <td class="py-3 px-3 text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-6 text-center text-gray-500">No users yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Template usage --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Template Usage</h3>
                    @php $totalTpl = array_sum($templateUsage) ?: 1; @endphp
                    @forelse ($templateUsage as $template => $count)
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 capitalize">{{ $template }}</span>
                                <span class="text-gray-500">{{ $count }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-indigo-500" style="width: {{ round($count / $totalTpl * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No resumes yet.</p>
                    @endforelse

                    <h3 class="font-semibold text-gray-800 mt-6 mb-3">Most Checked Resumes</h3>
                    @forelse ($topResumes as $resume)
                        <div class="flex items-center justify-between py-2 border-b last:border-0 text-sm">
                            <div class="pr-2 truncate">
                                <p class="text-gray-800 truncate">{{ $resume->title }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $resume->user?->name ?? 'Unknown' }}</p>
                            </div>
                            <span class="shrink-0 rounded-full bg-purple-100 px-2 py-0.5 text-xs font-semibold text-purple-700">{{ $resume->ats_checks_count }} checks</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No data yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent ATS checks --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Recent ATS Checks</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2 pr-3 font-medium">Job Title</th>
                                <th class="py-2 px-3 font-medium">User</th>
                                <th class="py-2 px-3 font-medium">Resume</th>
                                <th class="py-2 px-3 font-medium">Score</th>
                                <th class="py-2 px-3 font-medium">When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentChecks as $check)
                                <tr class="border-b last:border-0">
                                    <td class="py-3 pr-3 text-gray-800">{{ $check->job_title ?: 'Untitled role' }}</td>
                                    <td class="py-3 px-3 text-gray-600">{{ $check->user?->name ?? '—' }}</td>
                                    <td class="py-3 px-3 text-gray-600">{{ $check->resume?->title ?? 'Deleted' }}</td>
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-bold text-{{ $check->scoreColor() }}-700 bg-{{ $check->scoreColor() }}-100">{{ $check->score }}%</span>
                                    </td>
                                    <td class="py-3 px-3 text-gray-500">{{ $check->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-6 text-center text-gray-500">No ATS checks yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

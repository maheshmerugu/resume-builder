<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2>
            <a href="{{ route('resumes.create') }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                + New Resume
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Plan / usage banner --}}
            @if ($plan)
                @php $rr = auth()->user()->remainingResumes(); $rd = auth()->user()->remainingDownloads(); @endphp
                <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white p-5 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-indigo-100">Current plan</p>
                        <p class="text-xl font-bold">{{ $plan->name }}
                            <span class="text-sm font-normal text-indigo-100">
                                &middot; Resumes left: {{ is_null($rr) ? 'Unlimited' : $rr }}
                                &middot; Downloads left: {{ is_null($rd) ? 'Unlimited' : $rd }}
                            </span>
                        </p>
                    </div>
                    <a href="{{ route('plans.index') }}" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                        {{ $plan->isFree() ? 'Upgrade plan' : 'Manage plan' }}
                    </a>
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $cards = [
                        ['label' => 'Resumes', 'value' => $stats['resumes'], 'color' => 'indigo'],
                        ['label' => 'ATS Checks', 'value' => $stats['checks'], 'color' => 'blue'],
                        ['label' => 'Best ATS Score', 'value' => $stats['best_score'].'%', 'color' => 'green'],
                        ['label' => 'Avg. Completeness', 'value' => $stats['avg_completeness'].'%', 'color' => 'purple'],
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
                {{-- Resumes --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Your Resumes</h3>
                        <a href="{{ route('resumes.index') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
                    </div>

                    @forelse ($resumes as $resume)
                        <div class="flex items-center justify-between py-3 border-b last:border-0">
                            <div>
                                <p class="font-medium text-gray-800">{{ $resume->title }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ ucfirst($resume->template) }} template &middot;
                                    {{ $resume->completeness() }}% complete &middot;
                                    updated {{ $resume->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <a href="{{ route('resumes.edit', $resume) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="text-blue-600 hover:underline">Check ATS</a>
                                <a href="{{ route('resumes.pdf', $resume) }}" class="text-gray-600 hover:underline">PDF</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-500">You have no resumes yet.</p>
                            <a href="{{ route('resumes.create') }}" class="mt-3 inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Create your first resume</a>
                        </div>
                    @endforelse
                </div>

                {{-- Recent ATS checks --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Recent ATS Checks</h3>
                        <a href="{{ route('ats.index') }}" class="text-sm text-indigo-600 hover:underline">All</a>
                    </div>

                    @forelse ($atsChecks as $check)
                        <a href="{{ route('ats.show', $check) }}" class="flex items-center justify-between py-3 border-b last:border-0 group">
                            <div class="pr-3">
                                <p class="font-medium text-gray-800 group-hover:text-indigo-600 truncate">{{ $check->job_title ?: 'Untitled role' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $check->resume?->title ?? 'Resume deleted' }}</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center justify-center h-10 w-10 rounded-full text-sm font-bold text-{{ $check->scoreColor() }}-700 bg-{{ $check->scoreColor() }}-100">
                                {{ $check->score }}
                            </span>
                        </a>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-sm">No ATS checks yet.</p>
                            <a href="{{ route('ats.create') }}" class="mt-3 inline-block rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Run an ATS check</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">ATS Result — {{ $check->job_title ?: 'Untitled role' }}</h2>
            <a href="{{ route('ats.create') }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-500">New Check</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Score --}}
            <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col sm:flex-row items-center gap-6">
                @php
                    $c = $check->scoreColor();
                    $deg = $check->score * 3.6;
                    $ring = ['green' => '#16a34a', 'blue' => '#2563eb', 'yellow' => '#ca8a04', 'red' => '#dc2626'][$c] ?? '#4f46e5';
                @endphp
                <div class="relative shrink-0" style="width:130px;height:130px;">
                    <div class="rounded-full" style="width:130px;height:130px;background: conic-gradient({{ $ring }} {{ $deg }}deg, #e5e7eb {{ $deg }}deg);"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-white rounded-full flex flex-col items-center justify-center" style="width:96px;height:96px;">
                            <span class="text-3xl font-bold text-{{ $c }}-600">{{ $check->score }}</span>
                            <span class="text-xs text-gray-500">/ 100</span>
                        </div>
                    </div>
                </div>
                <div class="text-center sm:text-left">
                    <p class="text-lg font-semibold text-{{ $c }}-600">{{ $check->scoreLabel() }}</p>
                    <p class="text-sm text-gray-600 mt-1">Resume: <span class="font-medium">{{ $check->resume?->title ?? 'deleted' }}</span></p>
                    <p class="text-sm text-gray-500">{{ count($check->matched_keywords ?? []) }} matched &middot; {{ count($check->missing_keywords ?? []) }} missing keywords</p>
                    @if($check->resume)
                        <div class="mt-3 flex gap-2 justify-center sm:justify-start">
                            <a href="{{ route('resumes.edit', $check->resume) }}" class="text-sm rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-500">Improve Resume</a>
                            <a href="{{ route('resumes.pdf', $check->resume) }}" class="text-sm rounded-md border px-3 py-1.5 text-gray-700 hover:bg-gray-50">Download PDF</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Suggestions --}}
            @if(!empty($check->suggestions))
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Suggestions to improve your score</h3>
                    <ul class="space-y-2">
                        @foreach($check->suggestions as $s)
                            <li class="flex gap-2 text-sm text-gray-700">
                                <span class="text-indigo-500">→</span><span>{{ $s }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Keywords --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-green-700 mb-3">Matched keywords ({{ count($check->matched_keywords ?? []) }})</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($check->matched_keywords ?? [] as $kw)
                            <span class="px-2 py-1 rounded bg-green-50 text-green-700 text-xs">{{ $kw }}</span>
                        @empty
                            <p class="text-sm text-gray-400">No keywords matched.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-red-700 mb-3">Missing keywords ({{ count($check->missing_keywords ?? []) }})</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($check->missing_keywords ?? [] as $kw)
                            <span class="px-2 py-1 rounded bg-red-50 text-red-700 text-xs">{{ $kw }}</span>
                        @empty
                            <p class="text-sm text-gray-400">Great — nothing important missing!</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('ats.index') }}" class="text-sm text-gray-600 hover:underline">← Back to all checks</a>
            </div>
        </div>
    </div>
</x-app-layout>

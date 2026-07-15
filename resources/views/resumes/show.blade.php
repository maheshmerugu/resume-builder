<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Preview</h1>
                <p class="text-sm text-slate-500">{{ $resume->title }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('resumes.edit', $resume) }}" class="admin-btn-secondary">Edit</a>
                <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="admin-btn-primary">Check ATS</a>
                @php
                    $user = auth()->user();
                    $canDownload = $user->canDownload();
                    $hasPlanAccess = $user->hasPlanAccess();
                    $billingEnabled = $user->billingEnabled();
                @endphp
                @if ($canDownload && $hasPlanAccess)
                    <a href="{{ route('resumes.pdf', $resume) }}" class="admin-btn-primary">Download PDF</a>
                @elseif ($billingEnabled && ! $hasPlanAccess)
                    <a href="{{ route('plans.index') }}" class="admin-btn-secondary border-amber-200 text-amber-700 hover:bg-amber-50" title="Subscribe to download PDFs">Download PDF</a>
                @elseif ($billingEnabled && ! $canDownload)
                    <span class="admin-btn-secondary cursor-not-allowed opacity-60" title="Download limit reached for your plan">Download PDF</span>
                @else
                    <a href="{{ route('resumes.pdf', $resume) }}" class="admin-btn-primary">Download PDF</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-5">
        @include('partials.resume-completeness', ['resume' => $resume])

        <div class="admin-card mx-auto max-w-3xl p-8 shadow-lg sm:p-10">
            @include($template, ['resume' => $resume])
        </div>
    </div>
</x-app-layout>

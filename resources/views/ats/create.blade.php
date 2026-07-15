<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">ATS Score Checker</h1>
            <p class="text-sm text-slate-500">Compare your resume against a job description</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl space-y-4">
        @include('partials.alert')

        @if ($errors->any())
            <div class="admin-card border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <ul class="list-disc ps-5">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if ($resumes->isEmpty())
            <div class="admin-card p-10 text-center">
                <p class="text-slate-600">You need a resume before running an ATS check.</p>
                <a href="{{ route('resumes.create') }}" class="admin-btn-primary mt-4 inline-flex">Create a resume</a>
            </div>
        @else
            @php
                $selectedResume = $resumes->firstWhere('id', $selectedResumeId) ?? $resumes->first();
            @endphp
            <div class="admin-card p-5">
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Resume progress</p>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Complete your resume before running ATS for better scores.</p>
                @include('partials.resume-completeness-inline', ['resume' => $selectedResume])
            </div>

            <form method="POST" action="{{ route('ats.store') }}" class="admin-card space-y-5 p-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700">Select resume</label>
                    <select name="resume_id" class="mt-1 w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($resumes as $r)
                            <option value="{{ $r->id }}" @selected($selectedResumeId === $r->id)>{{ $r->title }} — {{ $r->completeness() }}% complete</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Job title (optional)</label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" placeholder="Senior PHP Developer" class="mt-1 w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Paste the job description</label>
                    <textarea name="job_description" rows="12" required class="mt-1 w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Paste the full job posting here...">{{ old('job_description') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">We compare your resume against the keywords in this description and give you a match score with suggestions.</p>
                </div>

                <button type="submit" class="admin-btn-primary">Analyze My Resume</button>
            </form>
        @endif
    </div>
</x-app-layout>

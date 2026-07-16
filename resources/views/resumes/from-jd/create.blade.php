<x-app-layout>
    <x-slot name="header">
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('resumes.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">My Resumes</a>
                <span>/</span>
                <span>From Job Description</span>
            </div>
            <h1 class="mt-1 text-lg font-bold text-slate-900 dark:text-slate-100 sm:text-xl">Create Resume from Job Description</h1>
            <p class="hidden text-sm text-slate-500 dark:text-slate-400 sm:block">Paste a JD — AI builds a tailored resume automatically</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @include('partials.alert')

        @if ($errors->any())
            <div class="admin-card border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-300">
                <ul class="list-disc ps-5">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-3">
            {{-- Main form --}}
            <div class="xl:col-span-2">
                <form method="POST" action="{{ route('resumes.from-jd.store') }}" class="space-y-5">
                    @csrf

                    <div class="admin-card overflow-hidden">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-5 text-white dark:border-slate-800">
                            <div class="flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/20">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <div>
                                    <h2 class="text-lg font-bold">Paste the job description</h2>
                                    <p class="mt-1 text-sm text-indigo-100">We extract keywords, write tailored content, and create a ready-to-edit resume.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5 p-6">
                            <div>
                                <label for="job_title" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">Target job title</label>
                                <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $prefillTitle) }}"
                                       placeholder="e.g. Senior Product Manager"
                                       class="resume-input mt-1.5">
                            </div>

                            <div>
                                <label for="job_description" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">Job description <span class="text-red-500">*</span></label>
                                <textarea id="job_description" name="job_description" rows="14" required
                                          placeholder="Paste the full job posting here — responsibilities, requirements, skills, qualifications..."
                                          class="resume-input mt-1.5 font-mono text-xs leading-relaxed">{{ old('job_description', $prefillJd) }}</textarea>
                                <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Minimum 40 characters. More detail = better keyword matching.</p>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Your details</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Used to personalize the generated resume. You can edit everything after.</p>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Full name</label>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" class="resume-input mt-1">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="resume-input mt-1">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone (optional)</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" class="resume-input mt-1">
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Location (optional)</label>
                                <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="Bangalore, India" class="resume-input mt-1">
                            </div>
                            <div>
                                <label for="current_role" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Current / recent role</label>
                                <input type="text" id="current_role" name="current_role" value="{{ old('current_role') }}" placeholder="Product Manager" class="resume-input mt-1">
                            </div>
                            <div>
                                <label for="years_experience" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Years of experience</label>
                                <input type="number" id="years_experience" name="years_experience" min="0" max="45" value="{{ old('years_experience', 4) }}" class="resume-input mt-1">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="background_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Background notes (optional)</label>
                            <textarea id="background_notes" name="background_notes" rows="4"
                                      placeholder="Briefly describe your experience, achievements, tools, industries..."
                                      class="resume-input mt-1">{{ old('background_notes') }}</textarea>
                        </div>

                        @if ($resumes->isNotEmpty())
                            <div class="mt-4">
                                <label for="source_resume_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Use existing resume as base (optional)</label>
                                <select id="source_resume_id" name="source_resume_id" class="resume-input mt-1">
                                    <option value="">Start fresh from JD only</option>
                                    @foreach ($resumes as $resume)
                                        <option value="{{ $resume->id }}" @selected(old('source_resume_id') == $resume->id)>
                                            {{ $resume->title }} — {{ $resume->completeness() }}% complete
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="admin-card p-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Choose a theme</h3>
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach (collect($themeCatalog)->take(6) as $key => $meta)
                                <label class="cursor-pointer">
                                    <input type="radio" name="template" value="{{ $key }}" class="peer sr-only" @checked(old('template', $selectedTemplate) === $key)>
                                    <div class="rounded-xl border-2 border-slate-200 p-3 transition peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 dark:border-slate-700 dark:peer-checked:border-indigo-500 dark:peer-checked:bg-indigo-500/10">
                                        @include('partials.theme-preview', ['theme' => $key, 'compact' => true])
                                        <p class="mt-2 text-center text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $meta['label'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="admin-btn-primary">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Generate tailored resume
                        </button>
                        <a href="{{ route('resumes.create') }}" class="admin-btn-secondary">Create manually instead</a>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                <div class="admin-card p-6">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100">What you get</h3>
                    <ul class="mt-4 space-y-3">
                        @foreach ([
                            'Headline & summary matched to the JD',
                            'Skills extracted from job keywords',
                            'Experience bullets with impact language',
                            'Estimated ATS match score after generation',
                        ] as $item)
                            <li class="flex gap-2.5 text-sm text-slate-600 dark:text-slate-400">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="admin-card border-indigo-200 bg-indigo-50/50 p-6 dark:border-indigo-900/50 dark:bg-indigo-950/20">
                    <p class="text-xs font-bold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">Pro tip</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                        Paste the <strong>full job posting</strong> including requirements and responsibilities. Add background notes with your real achievements for the most accurate AI output.
                    </p>
                </div>

                <div class="admin-card p-6">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100">How it works</h3>
                    <ol class="mt-4 space-y-4">
                        @foreach ([
                            ['Analyze JD', 'Extract role title, skills, and keywords'],
                            ['Generate content', 'AI writes summary, bullets, and skills'],
                            ['Edit & export', 'Review in the builder, then download PDF'],
                        ] as $step)
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">{{ $loop->iteration }}</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $step[0] }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $step[1] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@php
    $init = [
        'title' => old('title', $resume->title ?? 'Untitled Resume'),
        'template' => old('template', $resume->template ?? 'modern'),
        'full_name' => old('full_name', $resume->full_name),
        'headline' => old('headline', $resume->headline),
        'email' => old('email', $resume->email),
        'phone' => old('phone', $resume->phone),
        'location' => old('location', $resume->location),
        'linkedin' => old('linkedin', $resume->linkedin),
        'website' => old('website', $resume->website),
        'summary' => old('summary', $resume->summary),
        'experience' => old('experience', ! empty($resume->experience) ? $resume->experience : [['role' => '', 'company' => '', 'location' => '', 'start' => '', 'end' => '', 'bullets' => '']]),
        'education' => old('education', ! empty($resume->education) ? $resume->education : [['degree' => '', 'school' => '', 'field' => '', 'start' => '', 'end' => '']]),
        'projects' => old('projects', ! empty($resume->projects) ? $resume->projects : [['name' => '', 'tech' => '', 'description' => '']]),
        'certifications' => old('certifications', ! empty($resume->certifications) ? $resume->certifications : [['name' => '', 'issuer' => '', 'year' => '']]),
        'skills_raw' => old('skills_raw', is_array($resume->skills ?? null) ? implode(', ', $resume->skills) : ''),
        'languages_raw' => old('languages_raw', is_array($resume->languages ?? null) ? implode(', ', $resume->languages) : ''),
    ];
@endphp

<div x-data="resumeForm(@js($init), @js($themeCatalog ?? \App\Support\ResumeThemes::catalog()), @js(route('resumes.ai.generate')))" @theme-selected="resume.template = $event.detail; themeOpen = false" class="space-y-5">
        @include('partials.resume-completeness-header')

        @include('partials.alert')

        <div x-show="aiError" x-cloak class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300" x-text="aiError"></div>
        <div x-show="aiNotice" x-cloak class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-300" x-text="aiNotice"></div>

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-1 list-disc ps-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $action }}" class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(320px,420px)]">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            {{-- ============ LEFT: EDITOR ============ --}}
            <div class="min-w-0 space-y-5">

                {{-- Meta + theme --}}
                <div class="resume-editor-section">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Resume details</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Internal title and visual theme</p>
                        </div>
                    </div>
                    <div class="resume-editor-section-body space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Resume title (internal)</label>
                            <input type="text" name="title" x-model="resume.title" class="resume-input">
                        </div>

                        <div>
                            <input type="hidden" name="template" x-model="resume.template">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Resume theme</label>
                                <a href="{{ route('themes.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Browse all {{ \App\Support\ResumeThemes::count() }} themes →</a>
                            </div>

                            <div x-show="!themeOpen" class="mt-3 flex flex-col gap-4 rounded-xl border border-slate-200 bg-slate-50/60 p-4 dark:border-slate-700 dark:bg-slate-800/40 sm:flex-row sm:items-center">
                                <div class="mx-auto w-28 shrink-0 rounded-xl p-2 sm:mx-0" :style="previewWrap(currentTheme())">
                                    <div class="aspect-[3/4] rounded-lg bg-white p-2 shadow-sm" :class="currentTheme().font === 'serif' ? 'font-serif' : 'font-sans'">
                                        <div :style="previewHeader(currentTheme())">
                                            <div class="h-2 w-12 rounded" :style="{ background: currentTheme().layout === 'banner' ? '#fff' : (currentTheme().colors?.primary || '#4f46e5') }"></div>
                                            <div class="mt-1 h-1.5 w-14 rounded opacity-70" :style="{ background: currentTheme().layout === 'banner' ? '#fff' : (currentTheme().colors?.light || '#eef2ff') }"></div>
                                        </div>
                                        <div class="mt-2 space-y-1">
                                            <div class="h-1 w-full rounded" :style="{ background: currentTheme().colors?.light || '#eef2ff' }"></div>
                                            <div class="h-1 w-10/12 rounded" :style="{ background: currentTheme().colors?.light || '#eef2ff' }"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 text-center sm:text-left">
                                    <p class="font-semibold text-slate-900 dark:text-slate-100" x-text="currentTheme().label || resume.template"></p>
                                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                        <span x-text="currentTheme().tagline"></span>
                                        <span x-show="currentTheme().category"> · </span>
                                        <span x-text="currentTheme().category"></span>
                                    </p>
                                </div>
                                <button type="button" @click="themeOpen = true" class="admin-btn-secondary w-full sm:w-auto">Change theme</button>
                            </div>

                            <div x-show="themeOpen" x-cloak class="mt-3 space-y-3 rounded-xl border border-indigo-200 bg-indigo-50/30 p-4 dark:border-indigo-500/30 dark:bg-indigo-950/20">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Pick a theme</p>
                                    <button type="button" @click="themeOpen = false" class="text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Close</button>
                                </div>
                                @include('partials.theme-gallery', [
                                    'mode' => 'select',
                                    'selected' => old('template', $resume->template ?? 'modern'),
                                    'themes' => \App\Support\ResumeThemes::all(),
                                    'categories' => \App\Support\ResumeThemes::categories(),
                                    'showFilters' => true,
                                    'compact' => true,
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div id="section-contact" class="resume-editor-section scroll-mt-36 transition-shadow"
                     :class="isNextSection('section-contact') ? 'ring-2 ring-indigo-400/60 ring-offset-2 dark:ring-indigo-500/50' : ''">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Contact details</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">How employers can reach you</p>
                        </div>
                    </div>
                    <div class="resume-editor-section-body">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Full name</label>
                                <input type="text" name="full_name" x-model="resume.full_name" class="resume-input">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Headline / Target role</label>
                                <input type="text" name="headline" x-model="resume.headline" placeholder="Full Stack Developer" class="resume-input">
                                <div class="mt-2">
                                    @include('partials.ai-write-button', ['target' => 'headline', 'label' => 'AI Headline'])
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Email</label>
                                <input type="text" name="email" x-model="resume.email" class="resume-input">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Phone</label>
                                <input type="text" name="phone" x-model="resume.phone" class="resume-input">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Location</label>
                                <input type="text" name="location" x-model="resume.location" class="resume-input">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">LinkedIn</label>
                                <input type="text" name="linkedin" x-model="resume.linkedin" class="resume-input">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Website / Portfolio</label>
                                <input type="text" name="website" x-model="resume.website" class="resume-input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Summary --}}
                <div id="section-summary" class="resume-editor-section scroll-mt-36 transition-shadow"
                     :class="isNextSection('section-summary') ? 'ring-2 ring-indigo-400/60 ring-offset-2 dark:ring-indigo-500/50' : ''">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Professional summary</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">A short overview of your experience</p>
                        </div>
                    </div>
                    <div class="resume-editor-section-body">
                        <textarea name="summary" x-model="resume.summary" rows="4" class="resume-input" placeholder="Full Stack Developer with 5+ years..."></textarea>
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                            <p class="text-xs text-slate-400" x-text="wordCount(resume.summary) + ' words'"></p>
                            @include('partials.ai-write-button', ['target' => 'summary'])
                        </div>
                    </div>
                </div>

                {{-- Experience --}}
                <div id="section-experience" class="resume-editor-section scroll-mt-36 transition-shadow"
                     :class="isNextSection('section-experience') ? 'ring-2 ring-indigo-400/60 ring-offset-2 dark:ring-indigo-500/50' : ''">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Work experience</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Roles, companies, and achievements</p>
                        </div>
                        <button type="button" @click="addExperience()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">+ Add role</button>
                    </div>
                    <div class="resume-editor-section-body">
                        <template x-for="(item, i) in resume.experience" :key="'exp'+i">
                            <div class="resume-repeater-item">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400" x-text="'Role ' + (i + 1)"></span>
                                    <button type="button" @click="removeRow('experience', i)" class="text-xs font-medium text-red-500 hover:text-red-600">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <input type="text" :name="`experience[${i}][role]`" x-model="item.role" placeholder="Role / Title" class="resume-input mt-0">
                                    <input type="text" :name="`experience[${i}][company]`" x-model="item.company" placeholder="Company" class="resume-input mt-0">
                                    <input type="text" :name="`experience[${i}][location]`" x-model="item.location" placeholder="Location" class="resume-input mt-0">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" :name="`experience[${i}][start]`" x-model="item.start" placeholder="Start (Jan 2022)" class="resume-input mt-0">
                                        <input type="text" :name="`experience[${i}][end]`" x-model="item.end" placeholder="End / Present" class="resume-input mt-0">
                                    </div>
                                </div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Achievements (one per line)</label>
                                <textarea :name="`experience[${i}][bullets]`" x-model="item.bullets" rows="3" placeholder="One achievement per line" class="resume-input mt-0"></textarea>
                                <div class="mt-2">
                                    <button type="button"
                                            @click="aiWrite('experience:' + i)"
                                            :disabled="aiLoading !== null"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700 transition hover:border-violet-300 hover:bg-violet-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-violet-500/30 dark:bg-violet-500/10 dark:text-violet-300 dark:hover:bg-violet-500/20">
                                        <span x-text="aiLoading === ('experience:' + i) ? 'Writing...' : 'AI Write'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <p x-show="!resume.experience.length" class="text-center text-sm text-slate-400">No experience added yet.</p>
                    </div>
                </div>

                {{-- Education --}}
                <div id="section-education" class="resume-editor-section scroll-mt-36 transition-shadow"
                     :class="isNextSection('section-education') ? 'ring-2 ring-indigo-400/60 ring-offset-2 dark:ring-indigo-500/50' : ''">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Education</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Degrees and institutions</p>
                        </div>
                        <button type="button" @click="addEducation()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">+ Add degree</button>
                    </div>
                    <div class="resume-editor-section-body">
                        <template x-for="(item, i) in resume.education" :key="'edu'+i">
                            <div class="resume-repeater-item">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400" x-text="'Education ' + (i + 1)"></span>
                                    <button type="button" @click="removeRow('education', i)" class="text-xs font-medium text-red-500 hover:text-red-600">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <input type="text" :name="`education[${i}][degree]`" x-model="item.degree" placeholder="Degree (MCA)" class="resume-input mt-0">
                                    <input type="text" :name="`education[${i}][school]`" x-model="item.school" placeholder="School / University" class="resume-input mt-0">
                                    <input type="text" :name="`education[${i}][field]`" x-model="item.field" placeholder="Field of study" class="resume-input mt-0">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" :name="`education[${i}][start]`" x-model="item.start" placeholder="Start" class="resume-input mt-0">
                                        <input type="text" :name="`education[${i}][end]`" x-model="item.end" placeholder="End" class="resume-input mt-0">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Skills & Languages --}}
                <div id="section-skills" class="resume-editor-section scroll-mt-36 transition-shadow"
                     :class="isNextSection('section-skills') ? 'ring-2 ring-indigo-400/60 ring-offset-2 dark:ring-indigo-500/50' : ''">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Skills & languages</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Comma or newline separated</p>
                        </div>
                    </div>
                    <div class="resume-editor-section-body space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Skills</label>
                            <textarea name="skills_raw" x-model="resume.skills_raw" rows="3" class="resume-input" placeholder="PHP, Laravel, MySQL, React.js, REST APIs"></textarea>
                            <div class="mt-2">
                                @include('partials.ai-write-button', ['target' => 'skills'])
                            </div>
                        </div>
                        <div class="border-t border-slate-100 pt-4 dark:border-slate-800">
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Languages</label>
                            <input type="text" name="languages_raw" x-model="resume.languages_raw" class="resume-input" placeholder="English, Telugu">
                            <div class="mt-2">
                                @include('partials.ai-write-button', ['target' => 'languages'])
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Projects --}}
                <div class="resume-editor-section">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Projects</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Portfolio and side projects</p>
                        </div>
                        <button type="button" @click="addProject()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">+ Add project</button>
                    </div>
                    <div class="resume-editor-section-body">
                        <template x-for="(item, i) in resume.projects" :key="'proj'+i">
                            <div class="resume-repeater-item">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400" x-text="'Project ' + (i + 1)"></span>
                                    <button type="button" @click="removeRow('projects', i)" class="text-xs font-medium text-red-500 hover:text-red-600">Remove</button>
                                </div>
                                <input type="text" :name="`projects[${i}][name]`" x-model="item.name" placeholder="Project name" class="resume-input mt-0">
                                <input type="text" :name="`projects[${i}][tech]`" x-model="item.tech" placeholder="Tech used (Laravel, MySQL)" class="resume-input mt-0">
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400">Description</label>
                                <textarea :name="`projects[${i}][description]`" x-model="item.description" rows="2" placeholder="What it does / your role" class="resume-input mt-0"></textarea>
                                <div class="mt-2">
                                    <button type="button"
                                            @click="aiWrite('project:' + i)"
                                            :disabled="aiLoading !== null"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700 transition hover:border-violet-300 hover:bg-violet-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-violet-500/30 dark:bg-violet-500/10 dark:text-violet-300 dark:hover:bg-violet-500/20">
                                        <span x-text="aiLoading === ('project:' + i) ? 'Writing...' : 'AI Write'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Certifications --}}
                <div class="resume-editor-section">
                    <div class="resume-editor-section-head">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">Certifications</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Professional credentials</p>
                        </div>
                        <button type="button" @click="addCertification()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">+ Add cert</button>
                    </div>
                    <div class="resume-editor-section-body">
                        <template x-for="(item, i) in resume.certifications" :key="'cert'+i">
                            <div class="resume-repeater-item">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400" x-text="'Certification ' + (i + 1)"></span>
                                    <button type="button" @click="removeRow('certifications', i)" class="text-xs font-medium text-red-500 hover:text-red-600">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <input type="text" :name="`certifications[${i}][name]`" x-model="item.name" placeholder="Certification" class="resume-input mt-0">
                                    <input type="text" :name="`certifications[${i}][issuer]`" x-model="item.issuer" placeholder="Issuer" class="resume-input mt-0">
                                    <input type="text" :name="`certifications[${i}][year]`" x-model="item.year" placeholder="Year" class="resume-input mt-0">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="resume-save-bar sticky bottom-4 z-10 xl:static">
                    <button type="submit" class="admin-btn-primary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Resume
                    </button>
                    <a href="{{ route('resumes.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200">Cancel</a>
                </div>
            </div>

            {{-- ============ RIGHT: LIVE PREVIEW ============ --}}
            <div class="xl:sticky xl:top-24 xl:self-start">
                <div class="admin-card overflow-hidden">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-3 dark:border-slate-800 sm:px-5">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Live preview</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Updates as you type</p>
                        </div>
                        <span class="admin-badge bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300" x-text="themeLabel()"></span>
                    </div>
                    <div class="resume-preview-frame">
                        <div class="resume-preview-paper text-xs leading-relaxed"
                             :class="currentTheme().font === 'serif' ? 'font-serif' : 'font-sans'"
                             :style="{ color: currentTheme().colors?.text || '#111827' }">

                        <div :style="themeHeaderStyle()">
                            <h1 class="text-xl font-bold" :style="{ color: headerTextColor() }" x-text="resume.full_name || 'Your Name'"></h1>
                            <p class="mt-0.5 text-sm font-medium" :style="{ color: headerAccentColor() }" x-text="resume.headline || 'Your headline'"></p>
                            <p class="mt-1 text-[11px]" :style="{ color: headerMutedColor() }" x-text="contactLine() || 'email@example.com | phone | location'"></p>
                        </div>

                        <template x-if="resume.summary">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Summary</h2>
                                <p x-text="resume.summary" class="text-justify"></p>
                            </div>
                        </template>
                        <template x-if="!resume.summary">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Summary</h2>
                                <p class="italic text-slate-400">Your summary will appear here…</p>
                            </div>
                        </template>

                        {{-- Experience --}}
                        <template x-if="hasContent(resume.experience, ['role','company'])">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Experience</h2>
                                <template x-for="(item, i) in resume.experience" :key="'pexp'+i">
                                    <div class="mb-2" x-show="item.role || item.company">
                                        <div class="flex justify-between">
                                            <span class="font-semibold" x-text="[item.role, item.company].filter(Boolean).join(' · ')"></span>
                                            <span class="text-gray-500" x-text="[item.start, item.end].filter(Boolean).join(' – ')"></span>
                                        </div>
                                        <ul class="list-disc ms-4 text-gray-700">
                                            <template x-for="(b, bi) in lines(item.bullets)" :key="bi">
                                                <li x-text="b"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Education --}}
                        <template x-if="hasContent(resume.education, ['degree','school'])">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Education</h2>
                                <template x-for="(item, i) in resume.education" :key="'pedu'+i">
                                    <div class="mb-1 flex justify-between" x-show="item.degree || item.school">
                                        <span x-text="[item.degree, item.field, item.school].filter(Boolean).join(', ')"></span>
                                        <span class="text-gray-500" x-text="[item.start, item.end].filter(Boolean).join(' – ')"></span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Skills --}}
                        <template x-if="listItems(resume.skills_raw).length">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Skills</h2>
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="(s, i) in listItems(resume.skills_raw)" :key="'sk'+i">
                                        <span class="rounded px-2 py-0.5 text-[10px]" :style="skillBadgeStyle()" x-text="s"></span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Projects --}}
                        <template x-if="hasContent(resume.projects, ['name'])">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Projects</h2>
                                <template x-for="(item, i) in resume.projects" :key="'ppr'+i">
                                    <div class="mb-1" x-show="item.name">
                                        <span class="font-semibold" x-text="item.name"></span>
                                        <span class="text-gray-500" x-show="item.tech" x-text="' — ' + item.tech"></span>
                                        <p class="text-gray-700" x-text="item.description"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Certifications --}}
                        <template x-if="hasContent(resume.certifications, ['name'])">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Certifications</h2>
                                <template x-for="(item, i) in resume.certifications" :key="'pce'+i">
                                    <div x-show="item.name" x-text="[item.name, item.issuer, item.year].filter(Boolean).join(' · ')"></div>
                                </template>
                            </div>
                        </template>

                        {{-- Languages --}}
                        <template x-if="listItems(resume.languages_raw).length">
                            <div class="mt-4">
                                <h2 class="mb-1 border-b pb-1 text-xs font-bold uppercase tracking-wide" :style="sectionHeadingStyle()">Languages</h2>
                                <p x-text="listItems(resume.languages_raw).join(', ')"></p>
                            </div>
                        </template>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</div>


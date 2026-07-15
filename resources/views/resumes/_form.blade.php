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

<div class="py-6" x-data="resumeForm(@js($init))">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="list-disc ms-5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $action }}" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            {{-- ============ LEFT: EDITOR ============ --}}
            <div class="space-y-5">

                {{-- Meta + template --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Resume title (internal)</label>
                        <input type="text" name="title" x-model="resume.title" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Template</label>
                        <select name="template" x-model="resume.template" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($templates as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
                    <h3 class="font-semibold text-gray-800">Contact Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Full name</label>
                            <input type="text" name="full_name" x-model="resume.full_name" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Headline / Target role</label>
                            <input type="text" name="headline" x-model="resume.headline" placeholder="Full Stack Developer" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Email</label>
                            <input type="text" name="email" x-model="resume.email" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Phone</label>
                            <input type="text" name="phone" x-model="resume.phone" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Location</label>
                            <input type="text" name="location" x-model="resume.location" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">LinkedIn</label>
                            <input type="text" name="linkedin" x-model="resume.linkedin" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-600">Website / Portfolio</label>
                            <input type="text" name="website" x-model="resume.website" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                        </div>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-2">
                    <h3 class="font-semibold text-gray-800">Professional Summary</h3>
                    <textarea name="summary" x-model="resume.summary" rows="4" class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Full Stack Developer with 5+ years..."></textarea>
                    <p class="text-xs text-gray-400" x-text="wordCount(resume.summary) + ' words'"></p>
                </div>

                {{-- Experience --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Work Experience</h3>
                        <button type="button" @click="addExperience()" class="text-sm text-indigo-600 hover:underline">+ Add</button>
                    </div>
                    <template x-for="(item, i) in resume.experience" :key="'exp'+i">
                        <div class="border rounded-lg p-3 space-y-2 bg-gray-50">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" :name="`experience[${i}][role]`" x-model="item.role" placeholder="Role / Title" class="rounded-md border-gray-300 text-sm">
                                <input type="text" :name="`experience[${i}][company]`" x-model="item.company" placeholder="Company" class="rounded-md border-gray-300 text-sm">
                                <input type="text" :name="`experience[${i}][location]`" x-model="item.location" placeholder="Location" class="rounded-md border-gray-300 text-sm">
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" :name="`experience[${i}][start]`" x-model="item.start" placeholder="Start (Jan 2022)" class="rounded-md border-gray-300 text-sm">
                                    <input type="text" :name="`experience[${i}][end]`" x-model="item.end" placeholder="End / Present" class="rounded-md border-gray-300 text-sm">
                                </div>
                            </div>
                            <textarea :name="`experience[${i}][bullets]`" x-model="item.bullets" rows="3" placeholder="One achievement per line" class="w-full rounded-md border-gray-300 text-sm"></textarea>
                            <div class="text-right">
                                <button type="button" @click="removeRow('experience', i)" class="text-xs text-red-500 hover:underline">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Education --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Education</h3>
                        <button type="button" @click="addEducation()" class="text-sm text-indigo-600 hover:underline">+ Add</button>
                    </div>
                    <template x-for="(item, i) in resume.education" :key="'edu'+i">
                        <div class="border rounded-lg p-3 space-y-2 bg-gray-50">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" :name="`education[${i}][degree]`" x-model="item.degree" placeholder="Degree (MCA)" class="rounded-md border-gray-300 text-sm">
                                <input type="text" :name="`education[${i}][school]`" x-model="item.school" placeholder="School / University" class="rounded-md border-gray-300 text-sm">
                                <input type="text" :name="`education[${i}][field]`" x-model="item.field" placeholder="Field of study" class="rounded-md border-gray-300 text-sm">
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" :name="`education[${i}][start]`" x-model="item.start" placeholder="Start" class="rounded-md border-gray-300 text-sm">
                                    <input type="text" :name="`education[${i}][end]`" x-model="item.end" placeholder="End" class="rounded-md border-gray-300 text-sm">
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" @click="removeRow('education', i)" class="text-xs text-red-500 hover:underline">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Skills & Languages --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">Skills</h3>
                        <p class="text-xs text-gray-500 mb-1">Comma or newline separated.</p>
                        <textarea name="skills_raw" x-model="resume.skills_raw" rows="3" class="w-full rounded-md border-gray-300 text-sm" placeholder="PHP, Laravel, MySQL, React.js, REST APIs"></textarea>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Languages</h3>
                        <input type="text" name="languages_raw" x-model="resume.languages_raw" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="English, Telugu">
                    </div>
                </div>

                {{-- Projects --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Projects</h3>
                        <button type="button" @click="addProject()" class="text-sm text-indigo-600 hover:underline">+ Add</button>
                    </div>
                    <template x-for="(item, i) in resume.projects" :key="'proj'+i">
                        <div class="border rounded-lg p-3 space-y-2 bg-gray-50">
                            <input type="text" :name="`projects[${i}][name]`" x-model="item.name" placeholder="Project name" class="w-full rounded-md border-gray-300 text-sm">
                            <input type="text" :name="`projects[${i}][tech]`" x-model="item.tech" placeholder="Tech used (Laravel, MySQL)" class="w-full rounded-md border-gray-300 text-sm">
                            <textarea :name="`projects[${i}][description]`" x-model="item.description" rows="2" placeholder="What it does / your role" class="w-full rounded-md border-gray-300 text-sm"></textarea>
                            <div class="text-right">
                                <button type="button" @click="removeRow('projects', i)" class="text-xs text-red-500 hover:underline">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Certifications --}}
                <div class="bg-white rounded-xl shadow-sm p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Certifications</h3>
                        <button type="button" @click="addCertification()" class="text-sm text-indigo-600 hover:underline">+ Add</button>
                    </div>
                    <template x-for="(item, i) in resume.certifications" :key="'cert'+i">
                        <div class="border rounded-lg p-3 grid grid-cols-1 sm:grid-cols-3 gap-2 bg-gray-50">
                            <input type="text" :name="`certifications[${i}][name]`" x-model="item.name" placeholder="Certification" class="rounded-md border-gray-300 text-sm">
                            <input type="text" :name="`certifications[${i}][issuer]`" x-model="item.issuer" placeholder="Issuer" class="rounded-md border-gray-300 text-sm">
                            <div class="flex gap-2">
                                <input type="text" :name="`certifications[${i}][year]`" x-model="item.year" placeholder="Year" class="w-full rounded-md border-gray-300 text-sm">
                                <button type="button" @click="removeRow('certifications', i)" class="text-xs text-red-500 hover:underline shrink-0">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex items-center gap-3 sticky bottom-4">
                    <button type="submit" class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">Save Resume</button>
                    <a href="{{ route('resumes.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                </div>
            </div>

            {{-- ============ RIGHT: COMPLETENESS + PREVIEW ============ --}}
            <div class="space-y-4 lg:sticky lg:top-6 lg:self-start">
                @include('partials.resume-completeness-header')

                <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Live Preview</span>
                    <span class="text-xs text-gray-400" x-text="resume.template + ' template'"></span>
                </div>
                <div class="bg-white rounded-xl shadow-lg border overflow-hidden">
                    <div class="p-6 text-[11px] leading-relaxed text-gray-800 max-h-[80vh] overflow-y-auto"
                         :class="resume.template === 'classic' ? 'font-serif' : 'font-sans'">

                        {{-- Header --}}
                        <div :class="resume.template === 'modern' ? 'border-l-4 border-indigo-600 pl-3' : (resume.template === 'classic' ? 'text-center border-b-2 border-gray-800 pb-2' : '')">
                            <h1 class="text-lg font-bold text-gray-900" x-text="resume.full_name || 'Your Name'"></h1>
                            <p class="text-indigo-600 font-medium" x-text="resume.headline"></p>
                            <p class="text-gray-500 mt-1" x-text="contactLine()"></p>
                        </div>

                        {{-- Summary --}}
                        <template x-if="resume.summary">
                            <div class="mt-4">
                                <h2 class="uppercase tracking-wide font-bold text-gray-700 border-b mb-1" x-text="'Summary'"></h2>
                                <p x-text="resume.summary" class="text-justify"></p>
                            </div>
                        </template>

                        {{-- Experience --}}
                        <template x-if="hasContent(resume.experience, ['role','company'])">
                            <div class="mt-4">
                                <h2 class="uppercase tracking-wide font-bold text-gray-700 border-b mb-1">Experience</h2>
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
                                <h2 class="uppercase tracking-wide font-bold text-gray-700 border-b mb-1">Education</h2>
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
                                <h2 class="uppercase tracking-wide font-bold text-gray-700 border-b mb-1">Skills</h2>
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="(s, i) in listItems(resume.skills_raw)" :key="'sk'+i">
                                        <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700" x-text="s"></span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Projects --}}
                        <template x-if="hasContent(resume.projects, ['name'])">
                            <div class="mt-4">
                                <h2 class="uppercase tracking-wide font-bold text-gray-700 border-b mb-1">Projects</h2>
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
                                <h2 class="uppercase tracking-wide font-bold text-gray-700 border-b mb-1">Certifications</h2>
                                <template x-for="(item, i) in resume.certifications" :key="'pce'+i">
                                    <div x-show="item.name" x-text="[item.name, item.issuer, item.year].filter(Boolean).join(' · ')"></div>
                                </template>
                            </div>
                        </template>

                        {{-- Languages --}}
                        <template x-if="listItems(resume.languages_raw).length">
                            <div class="mt-4">
                                <h2 class="uppercase tracking-wide font-bold text-gray-700 border-b mb-1">Languages</h2>
                                <p x-text="listItems(resume.languages_raw).join(', ')"></p>
                            </div>
                        </template>
                    </div>
                </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function resumeForm(init) {
        return {
            resume: init,
            wordCount(t) { return t ? t.trim().split(/\s+/).filter(Boolean).length : 0; },
            lines(t) { return t ? t.split(/\n+/).map(s => s.trim()).filter(Boolean) : []; },
            listItems(t) { return t ? t.split(/[,\n]+/).map(s => s.trim()).filter(Boolean) : []; },
            contactLine() {
                return [this.resume.email, this.resume.phone, this.resume.location, this.resume.linkedin, this.resume.website]
                    .filter(Boolean).join('  |  ');
            },
            hasContent(arr, fields) {
                return Array.isArray(arr) && arr.some(item => fields.some(f => item[f] && String(item[f]).trim()));
            },
            filled(value) {
                return Boolean(value && String(value).trim());
            },
            completenessChecks() {
                return [
                    { label: 'Name', done: this.filled(this.resume.full_name) },
                    { label: 'Email', done: this.filled(this.resume.email) },
                    { label: 'Summary', done: this.filled(this.resume.summary) },
                    { label: 'Experience', done: this.hasContent(this.resume.experience, ['role', 'company']) },
                    { label: 'Education', done: this.hasContent(this.resume.education, ['degree', 'school']) },
                    { label: 'Skills', done: this.listItems(this.resume.skills_raw).length > 0 },
                ];
            },
            completenessPercent() {
                const checks = this.completenessChecks();
                const done = checks.filter((check) => check.done).length;
                return Math.round((done / checks.length) * 100);
            },
            completenessLabel() {
                const percent = this.completenessPercent();
                if (percent >= 100) return 'Your resume is ready to save and download.';
                if (percent >= 67) return 'Almost there — fill in the remaining sections.';
                if (percent >= 34) return 'Good progress — keep going.';
                return 'Just getting started — complete the key sections below.';
            },
            completenessBarClass() {
                const percent = this.completenessPercent();
                if (percent >= 100) return 'bg-gradient-to-r from-emerald-500 to-teal-500';
                if (percent >= 67) return 'bg-gradient-to-r from-indigo-500 to-violet-500';
                if (percent >= 34) return 'bg-gradient-to-r from-blue-500 to-cyan-500';
                return 'bg-gradient-to-r from-amber-400 to-orange-500';
            },
            completenessTextClass() {
                const percent = this.completenessPercent();
                if (percent >= 100) return 'text-emerald-600';
                if (percent >= 67) return 'text-indigo-600';
                if (percent >= 34) return 'text-blue-600';
                return 'text-amber-600';
            },
            addExperience() { this.resume.experience.push({ role: '', company: '', location: '', start: '', end: '', bullets: '' }); },
            addEducation() { this.resume.education.push({ degree: '', school: '', field: '', start: '', end: '' }); },
            addProject() { this.resume.projects.push({ name: '', tech: '', description: '' }); },
            addCertification() { this.resume.certifications.push({ name: '', issuer: '', year: '' }); },
            removeRow(section, i) { this.resume[section].splice(i, 1); },
        };
    }
</script>

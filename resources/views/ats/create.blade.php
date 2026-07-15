<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('ATS Score Checker') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc ms-5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @if ($resumes->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center">
                    <p class="text-gray-600">You need a resume before running an ATS check.</p>
                    <a href="{{ route('resumes.create') }}" class="mt-4 inline-block rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Create a resume</a>
                </div>
            @else
                <form method="POST" action="{{ route('ats.store') }}" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Select resume</label>
                        <select name="resume_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($resumes as $r)
                                <option value="{{ $r->id }}" @selected($selectedResumeId === $r->id)>{{ $r->title }} — {{ $r->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Job title (optional)</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" placeholder="Senior PHP Developer" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Paste the job description</label>
                        <textarea name="job_description" rows="12" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Paste the full job posting here...">{{ old('job_description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">We compare your resume against the keywords in this description and give you a match score with suggestions.</p>
                    </div>

                    <button type="submit" class="rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-500">
                        Analyze My Resume
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>

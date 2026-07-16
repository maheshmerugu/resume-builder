<x-marketing-layout :seo="$seo" :schemas="$schemas">
    <section class="landing-hero-bg border-b border-slate-200/80">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-20">
            <span class="landing-section-label">Career tips & guides</span>
            <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">Resume & job search blog</h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">Practical advice on ATS resumes, tailoring your CV to job descriptions, and getting more interviews.</p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        @if ($posts->isEmpty())
            <p class="text-center text-slate-500">New articles coming soon.</p>
        @else
            <div class="space-y-8">
                @foreach ($posts as $post)
                    <article class="landing-card p-6 sm:p-8">
                        <time datetime="{{ $post['published_at'] }}" class="text-sm font-medium text-indigo-600">
                            {{ \Illuminate\Support\Carbon::parse($post['published_at'])->format('M j, Y') }}
                            · {{ $post['reading_minutes'] }} min read
                        </time>
                        <h2 class="mt-3 text-2xl font-bold text-slate-900">
                            <a href="{{ route('blog.show', $post['slug']) }}" class="hover:text-indigo-600">{{ $post['title'] }}</a>
                        </h2>
                        <p class="mt-3 leading-relaxed text-slate-600">{{ $post['description'] }}</p>
                        <a href="{{ route('blog.show', $post['slug']) }}" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                            Read article
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="mt-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-8 text-center text-white sm:p-10">
            <h2 class="text-2xl font-bold">Ready to build your resume?</h2>
            <p class="mx-auto mt-3 max-w-lg text-indigo-100">Paste a job description and get a tailored ATS resume in minutes.</p>
            <a href="{{ route('register') }}" class="mt-6 inline-flex rounded-xl bg-white px-6 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:bg-indigo-50">Start free — no credit card</a>
        </div>
    </section>
</x-marketing-layout>

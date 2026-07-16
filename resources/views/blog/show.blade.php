<x-marketing-layout :seo="$seo" :schemas="$schemas">
    <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <header class="mb-10 border-b border-slate-200 pb-8">
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">&larr; Back to blog</a>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $post['title'] }}</h1>
            <p class="mt-4 text-lg text-slate-600">{{ $post['description'] }}</p>
            <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <time datetime="{{ $post['published_at'] }}">{{ \Illuminate\Support\Carbon::parse($post['published_at'])->format('F j, Y') }}</time>
                <span>·</span>
                <span>{{ $post['reading_minutes'] }} min read</span>
                <span>·</span>
                <span>{{ $post['author'] }}</span>
            </div>
        </header>

        <div class="blog-prose max-w-none">
            {!! $post['html'] !!}
        </div>

        <div class="mt-12 rounded-2xl border border-indigo-200 bg-indigo-50/50 p-8 text-center">
            <h2 class="text-xl font-bold text-slate-900">Create a resume from any job description</h2>
            <p class="mt-2 text-slate-600">Our AI extracts keywords from the JD and builds a tailored resume you can edit and download as PDF.</p>
            <a href="{{ route('register') }}" class="landing-btn-primary mt-5 inline-flex">Try it free</a>
        </div>
    </article>
</x-marketing-layout>

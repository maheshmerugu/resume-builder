<?php

namespace App\Http\Controllers;

use App\Support\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Blog::published();

        return view('blog.index', [
            'posts' => $posts,
            'seo' => [
                'title' => 'Resume & Job Search Tips — '.config('seo.site_name').' Blog',
                'description' => 'Expert guides on ATS resumes, tailoring your CV to job descriptions, and landing interviews in India and worldwide.',
                'canonical' => route('blog.index', absolute: true),
                'keywords' => 'resume tips, ATS resume guide, job search blog, resume from job description',
            ],
            'schemas' => [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Blog',
                    'name' => config('seo.site_name').' Blog',
                    'url' => route('blog.index', absolute: true),
                    'description' => 'Resume writing and job search tips from AI Resume Builder.',
                ],
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $post = Blog::find($slug);

        abort_if($post === null, 404);

        return view('blog.show', [
            'post' => $post,
            'seo' => [
                'title' => $post['title'].' — '.config('seo.site_name'),
                'description' => $post['description'],
                'keywords' => $post['keywords'],
                'canonical' => $post['url'],
                'type' => 'article',
                'published_at' => $post['published_at'],
                'modified_at' => $post['updated_at'],
                'author' => $post['author'],
            ],
            'schemas' => \App\Support\Seo::articleSchema($post),
        ]);
    }
}

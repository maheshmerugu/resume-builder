<?php

namespace App\Http\Controllers;

use App\Support\Blog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            [
                'loc' => route('home', absolute: true),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => route('blog.index', absolute: true),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'loc' => route('register', absolute: true),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
        ]);

        Blog::published()->each(function (array $post) use ($urls) {
            $urls->push([
                'loc' => $post['url'],
                'lastmod' => $post['updated_at'],
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ]);
        });

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}

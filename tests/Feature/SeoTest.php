<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_includes_seo_meta_tags(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('FAQPage', false);
        $response->assertSee('AI Resume Builder', false);
    }

    public function test_sitemap_returns_xml_with_public_urls(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<urlset', false);
        $response->assertSee(route('home', absolute: true), false);
        $response->assertSee(route('blog.index', absolute: true), false);
        $response->assertSee(route('blog.show', 'ats-friendly-resume-guide', absolute: true), false);
    }

    public function test_blog_index_page_renders(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Resume & job search blog', false);
        $response->assertSee('ATS-Friendly Resume', false);
    }

    public function test_blog_post_page_renders(): void
    {
        $response = $this->get('/blog/ats-friendly-resume-guide');

        $response->assertStatus(200);
        $response->assertSee('How to Write an ATS-Friendly Resume in 2026');
        $response->assertSee('property="og:type" content="article"', false);
    }

    public function test_unknown_blog_post_returns_not_found(): void
    {
        $response = $this->get('/blog/does-not-exist');

        $response->assertNotFound();
    }

    public function test_login_page_is_noindex(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('noindex, nofollow', false);
    }
}

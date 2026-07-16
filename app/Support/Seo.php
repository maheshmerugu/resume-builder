<?php

namespace App\Support;

class Seo
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function meta(array $overrides = []): array
    {
        $defaults = [
            'title' => config('seo.default_title'),
            'description' => config('seo.default_description'),
            'keywords' => config('seo.keywords'),
            'canonical' => url()->current(),
            'image' => self::ogImage(),
            'type' => 'website',
            'noindex' => false,
            'published_at' => null,
            'modified_at' => null,
            'author' => config('seo.site_name'),
        ];

        return array_merge($defaults, $overrides);
    }

    public static function ogImage(): string
    {
        if ($custom = config('seo.og_image')) {
            return str_starts_with($custom, 'http') ? $custom : url($custom);
        }

        if (is_file(public_path('og-image.png'))) {
            return asset('og-image.png');
        }

        return asset('favicon.svg');
    }

    /**
     * @return array<string, mixed>
     */
    public static function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('seo.site_name'),
            'alternateName' => config('seo.alternate_names'),
            'url' => config('app.url'),
            'email' => config('seo.contact_email'),
            'logo' => self::ogImage(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function websiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('seo.site_name'),
            'alternateName' => config('seo.alternate_names'),
            'url' => config('app.url'),
            'description' => config('seo.default_description'),
            'inLanguage' => 'en-IN',
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('seo.site_name'),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function softwareApplicationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebApplication',
            'name' => config('seo.site_name'),
            'alternateName' => 'AI Resume Maker',
            'applicationCategory' => 'BusinessApplication',
            'applicationSubCategory' => 'Resume Builder',
            'operatingSystem' => 'Web',
            'browserRequirements' => 'Requires JavaScript',
            'url' => config('app.url'),
            'description' => config('seo.default_description'),
            'featureList' => [
                'AI resume from job description',
                'ATS resume checker',
                'Professional resume templates',
                'PDF export',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'INR',
            ],
        ];
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $faqs
     * @return array<string, mixed>
     */
    public static function faqSchema(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn (array $faq) => [
                '@type' => 'Question',
                'name' => $faq[0],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq[1],
                ],
            ])->values()->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $post
     * @return array<int, array<string, mixed>>
     */
    public static function articleSchema(array $post): array
    {
        return [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post['title'],
                'description' => $post['description'],
                'datePublished' => $post['published_at'],
                'dateModified' => $post['updated_at'] ?? $post['published_at'],
                'author' => [
                    '@type' => 'Organization',
                    'name' => $post['author'] ?? config('seo.site_name'),
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => config('seo.site_name'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => self::ogImage(),
                    ],
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $post['url'],
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => route('home', absolute: true),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Blog',
                        'item' => route('blog.index', absolute: true),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $post['title'],
                        'item' => $post['url'],
                    ],
                ],
            ],
        ];
    }
}

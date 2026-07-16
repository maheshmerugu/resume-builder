<?php

return [

    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'AI Resume Builder')),

    'default_title' => env(
        'SEO_DEFAULT_TITLE',
        'AI Resume Builder — Free AI Resume Maker | Create Resume from Job Description'
    ),

    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'AI Resume Builder (airesumebuilder.co.in) — paste any job description and get a tailored ATS resume in minutes. Free AI resume maker with professional templates, ATS checker, and PDF export for India and worldwide.'
    ),

    'keywords' => env(
        'SEO_KEYWORDS',
        'ai resume builder, AI resume maker, resume builder, ATS resume, resume from job description, free resume builder India, airesumebuilder'
    ),

    'twitter_handle' => env('SEO_TWITTER', ''),

    'og_image' => env('SEO_OG_IMAGE'),

    'contact_email' => env('SEO_CONTACT_EMAIL', 'support@airesumebuilder.co.in'),

    'domain' => env('SEO_DOMAIN', 'airesumebuilder.co.in'),

    'alternate_names' => [
        'AI Resume Builder',
        'airesumebuilder',
        'airesumebuilder.co.in',
        'AI Resume Maker',
    ],

    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),

    'bing_site_verification' => env('BING_SITE_VERIFICATION'),

];

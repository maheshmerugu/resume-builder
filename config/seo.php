<?php

return [

    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'AI Resume Builder')),

    'default_title' => env(
        'SEO_DEFAULT_TITLE',
        'AI Resume Builder — Create a resume from any job description'
    ),

    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'Paste any job description and get a tailored ATS resume in minutes. AI writing, professional templates, job-match scoring, and one-click PDF export for job seekers in India and worldwide.'
    ),

    'keywords' => env(
        'SEO_KEYWORDS',
        'resume builder, AI resume, ATS resume, resume from job description, resume maker India, free resume builder'
    ),

    'twitter_handle' => env('SEO_TWITTER', ''),

    'og_image' => env('SEO_OG_IMAGE'),

    'contact_email' => env('SEO_CONTACT_EMAIL', 'support@airesumebuilder.co.in'),

];

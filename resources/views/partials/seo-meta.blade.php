@php
    $seo = \App\Support\Seo::meta($seo ?? []);
@endphp
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if (! empty($seo['keywords']))
    <meta name="keywords" content="{{ $seo['keywords'] }}">
@endif
<link rel="canonical" href="{{ $seo['canonical'] }}">
@if ($seo['noindex'])
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow">
@endif

<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:site_name" content="{{ config('seo.site_name') }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
@if (! empty($seo['published_at']))
    <meta property="article:published_time" content="{{ $seo['published_at'] }}">
@endif
@if (! empty($seo['modified_at']))
    <meta property="article:modified_time" content="{{ $seo['modified_at'] }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">
<meta name="twitter:image" content="{{ $seo['image'] }}">
@if ($handle = config('seo.twitter_handle'))
    <meta name="twitter:site" content="{{ $handle }}">
@endif

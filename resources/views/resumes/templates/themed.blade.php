@php
    use App\Support\ResumeThemes;

    $themeId = ResumeThemes::resolve($resume->template ?? 'modern');
    $theme = ResumeThemes::get($themeId);
    $layout = $theme['layout'];
    $font = $theme['font'] === 'serif' ? 'Georgia, Times New Roman, serif' : 'Helvetica, Arial, sans-serif';
    $c = $theme['colors'];

    $skills = is_array($resume->skills ?? null) ? array_filter($resume->skills) : [];
    $languages = is_array($resume->languages ?? null) ? array_filter($resume->languages) : [];
    $contacts = array_filter([$resume->email, $resume->phone, $resume->location, $resume->linkedin, $resume->website]);
@endphp
<style>
    .rb-themed {
        font-family: {{ $font }};
        color: {{ $c['text'] }};
        font-size: 12px;
        line-height: 1.5;
    }
    .rb-themed .rb-name { font-size: 24px; font-weight: bold; color: {{ $c['text'] }}; margin: 0; }
    .rb-themed .rb-headline { color: {{ $c['primary'] }}; font-weight: bold; font-size: 13px; margin: 2px 0; }
    .rb-themed .rb-contact { color: {{ $c['muted'] }}; font-size: 11px; margin-top: 4px; }
    .rb-themed h2 {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: {{ $c['primary'] }};
        border-bottom: 2px solid {{ $c['border'] }};
        padding-bottom: 3px;
        margin: 16px 0 8px;
    }
    .rb-themed .rb-item { margin-bottom: 10px; }
    .rb-themed .rb-title { font-weight: bold; color: {{ $c['text'] }}; }
    .rb-themed .rb-sub { color: {{ $c['muted'] }}; font-size: 11px; }
    .rb-themed .rb-dates { color: {{ $c['muted'] }}; font-size: 11px; float: right; }
    .rb-themed ul { margin: 4px 0 0 16px; padding: 0; }
    .rb-themed li { margin-bottom: 2px; }
    .rb-themed .rb-skill {
        display: inline-block;
        background: {{ $c['light'] }};
        color: {{ $c['primary_dark'] }};
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        margin: 2px 3px 2px 0;
    }
    .rb-themed p { margin: 0; }

    /* Layout: modern */
    .rb-layout-modern .rb-header { border-left: 5px solid {{ $c['primary'] }}; padding-left: 14px; margin-bottom: 16px; }

    /* Layout: classic */
    .rb-layout-classic .rb-header { text-align: center; border-bottom: 2px solid {{ $c['text'] }}; padding-bottom: 8px; margin-bottom: 14px; }
    .rb-layout-classic .rb-headline { font-style: italic; font-weight: normal; color: {{ $c['text'] }}; }
    .rb-layout-classic h2 { text-align: center; border-bottom: 1px solid {{ $c['muted'] }}; }

    /* Layout: minimal */
    .rb-layout-minimal h2 { border: 0; color: {{ $c['muted'] }}; letter-spacing: 2px; font-size: 11px; }

    /* Layout: banner */
    .rb-layout-banner .rb-header {
        background: {{ $c['primary'] }};
        color: #fff;
        padding: 16px 18px;
        margin: -4px -4px 16px;
        border-radius: 4px;
    }
    .rb-layout-banner .rb-name,
    .rb-layout-banner .rb-headline,
    .rb-layout-banner .rb-contact { color: #fff; }
    .rb-layout-banner .rb-headline { opacity: 0.9; font-weight: normal; }
    .rb-layout-banner .rb-contact { opacity: 0.85; }

    /* Layout: underline */
    .rb-layout-underline .rb-header { margin-bottom: 16px; }
    .rb-layout-underline .rb-name { border-bottom: 4px solid {{ $c['primary'] }}; display: inline-block; padding-bottom: 4px; }

    /* Layout: boxed */
    .rb-layout-boxed .rb-header {
        border: 2px solid {{ $c['primary'] }};
        padding: 14px 16px;
        margin-bottom: 16px;
        border-radius: 4px;
        background: {{ $c['light'] }};
    }
</style>

<div class="rb-themed rb-layout-{{ $layout }}">
    <div class="rb-header">
        <p class="rb-name">{{ $resume->full_name ?: 'Your Name' }}</p>
        @if($resume->headline)<p class="rb-headline">{{ $resume->headline }}</p>@endif
        @if($contacts)<p class="rb-contact">{{ implode($layout === 'minimal' ? '  ·  ' : '  |  ', $contacts) }}</p>@endif
    </div>

    @if($resume->summary)
        <h2>{{ $layout === 'classic' ? 'Summary' : 'Professional Summary' }}</h2>
        <p style="text-align: justify;">{{ $resume->summary }}</p>
    @endif

    @if(!empty(array_filter((array) $resume->experience, fn($e) => filled($e['role'] ?? null) || filled($e['company'] ?? null))))
        <h2>Work Experience</h2>
        @foreach($resume->experience as $exp)
            @continue(blank($exp['role'] ?? null) && blank($exp['company'] ?? null))
            <div class="rb-item">
                <span class="rb-dates">{{ implode(' – ', array_filter([$exp['start'] ?? null, $exp['end'] ?? null])) }}</span>
                <span class="rb-title">{{ implode(' · ', array_filter([$exp['role'] ?? null, $exp['company'] ?? null])) }}</span>
                @if(filled($exp['location'] ?? null))<div class="rb-sub">{{ $exp['location'] }}</div>@endif
                @php $bullets = array_filter(array_map('trim', preg_split('/\n+/', (string) ($exp['bullets'] ?? '')))); @endphp
                @if($bullets)<ul>@foreach($bullets as $b)<li>{{ $b }}</li>@endforeach</ul>@endif
            </div>
        @endforeach
    @endif

    @if(!empty(array_filter((array) $resume->education, fn($e) => filled($e['degree'] ?? null) || filled($e['school'] ?? null))))
        <h2>Education</h2>
        @foreach($resume->education as $edu)
            @continue(blank($edu['degree'] ?? null) && blank($edu['school'] ?? null))
            <div class="rb-item">
                <span class="rb-dates">{{ implode(' – ', array_filter([$edu['start'] ?? null, $edu['end'] ?? null])) }}</span>
                <span class="rb-title">{{ implode(', ', array_filter([$edu['degree'] ?? null, $edu['field'] ?? null])) }}</span>
                @if(filled($edu['school'] ?? null))<div class="rb-sub">{{ $edu['school'] }}</div>@endif
            </div>
        @endforeach
    @endif

    @if($skills)
        <h2>Skills</h2>
        <div>@foreach($skills as $s)<span class="rb-skill">{{ $s }}</span>@endforeach</div>
    @endif

    @if(!empty(array_filter((array) $resume->projects, fn($p) => filled($p['name'] ?? null))))
        <h2>Projects</h2>
        @foreach($resume->projects as $proj)
            @continue(blank($proj['name'] ?? null))
            <div class="rb-item">
                <span class="rb-title">{{ $proj['name'] }}</span>@if(filled($proj['tech'] ?? null))<span class="rb-sub"> — {{ $proj['tech'] }}</span>@endif
                @if(filled($proj['description'] ?? null))<p>{{ $proj['description'] }}</p>@endif
            </div>
        @endforeach
    @endif

    @if(!empty(array_filter((array) $resume->certifications, fn($c) => filled($c['name'] ?? null))))
        <h2>Certifications</h2>
        @foreach($resume->certifications as $cert)
            @continue(blank($cert['name'] ?? null))
            <div>{{ implode(' · ', array_filter([$cert['name'] ?? null, $cert['issuer'] ?? null, $cert['year'] ?? null])) }}</div>
        @endforeach
    @endif

    @if($languages)
        <h2>Languages</h2>
        <p>{{ implode(', ', $languages) }}</p>
    @endif
</div>

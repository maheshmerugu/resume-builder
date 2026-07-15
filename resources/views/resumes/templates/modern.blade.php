@php
    $skills = is_array($resume->skills ?? null) ? array_filter($resume->skills) : [];
    $languages = is_array($resume->languages ?? null) ? array_filter($resume->languages) : [];
    $contacts = array_filter([$resume->email, $resume->phone, $resume->location, $resume->linkedin, $resume->website]);
@endphp
<style>
    .rb-modern { font-family: 'Helvetica', Arial, sans-serif; color: #1f2937; font-size: 12px; line-height: 1.5; }
    .rb-modern .rb-header { border-left: 5px solid #4f46e5; padding-left: 14px; margin-bottom: 16px; }
    .rb-modern .rb-name { font-size: 24px; font-weight: bold; color: #111827; margin: 0; }
    .rb-modern .rb-headline { color: #4f46e5; font-weight: bold; font-size: 13px; margin: 2px 0; }
    .rb-modern .rb-contact { color: #6b7280; font-size: 11px; margin-top: 4px; }
    .rb-modern h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #4f46e5;
        border-bottom: 2px solid #e5e7eb; padding-bottom: 3px; margin: 16px 0 8px; }
    .rb-modern .rb-item { margin-bottom: 10px; }
    .rb-modern .rb-row { width: 100%; }
    .rb-modern .rb-title { font-weight: bold; color: #111827; }
    .rb-modern .rb-sub { color: #6b7280; font-size: 11px; }
    .rb-modern .rb-dates { color: #6b7280; font-size: 11px; float: right; }
    .rb-modern ul { margin: 4px 0 0 16px; padding: 0; }
    .rb-modern li { margin-bottom: 2px; }
    .rb-modern .rb-skill { display: inline-block; background: #eef2ff; color: #4338ca;
        padding: 2px 8px; border-radius: 3px; font-size: 11px; margin: 2px 3px 2px 0; }
    .rb-modern p { margin: 0; }
</style>

<div class="rb-modern">
    <div class="rb-header">
        <p class="rb-name">{{ $resume->full_name ?: 'Your Name' }}</p>
        @if($resume->headline)<p class="rb-headline">{{ $resume->headline }}</p>@endif
        @if($contacts)<p class="rb-contact">{{ implode('  |  ', $contacts) }}</p>@endif
    </div>

    @if($resume->summary)
        <h2>Professional Summary</h2>
        <p style="text-align: justify;">{{ $resume->summary }}</p>
    @endif

    @if(!empty(array_filter((array) $resume->experience, fn($e) => filled($e['role'] ?? null) || filled($e['company'] ?? null))))
        <h2>Work Experience</h2>
        @foreach($resume->experience as $exp)
            @continue(blank($exp['role'] ?? null) && blank($exp['company'] ?? null))
            <div class="rb-item">
                <div class="rb-row">
                    <span class="rb-dates">{{ implode(' – ', array_filter([$exp['start'] ?? null, $exp['end'] ?? null])) }}</span>
                    <span class="rb-title">{{ implode(' · ', array_filter([$exp['role'] ?? null, $exp['company'] ?? null])) }}</span>
                    @if(filled($exp['location'] ?? null))<div class="rb-sub">{{ $exp['location'] }}</div>@endif
                </div>
                @php $bullets = array_filter(array_map('trim', preg_split('/\n+/', (string) ($exp['bullets'] ?? '')))); @endphp
                @if($bullets)
                    <ul>@foreach($bullets as $b)<li>{{ $b }}</li>@endforeach</ul>
                @endif
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

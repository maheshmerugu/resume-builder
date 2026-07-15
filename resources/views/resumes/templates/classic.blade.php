@php
    $skills = is_array($resume->skills ?? null) ? array_filter($resume->skills) : [];
    $languages = is_array($resume->languages ?? null) ? array_filter($resume->languages) : [];
    $contacts = array_filter([$resume->email, $resume->phone, $resume->location, $resume->linkedin, $resume->website]);
@endphp
<style>
    .rb-classic { font-family: 'Georgia', 'Times New Roman', serif; color: #1a1a1a; font-size: 12px; line-height: 1.5; }
    .rb-classic .rb-header { text-align: center; border-bottom: 2px solid #1a1a1a; padding-bottom: 8px; margin-bottom: 14px; }
    .rb-classic .rb-name { font-size: 24px; font-weight: bold; letter-spacing: 1px; margin: 0; }
    .rb-classic .rb-headline { font-size: 13px; font-style: italic; margin: 3px 0; }
    .rb-classic .rb-contact { font-size: 11px; color: #444; }
    .rb-classic h2 { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; text-align: center;
        border-bottom: 1px solid #999; padding-bottom: 2px; margin: 14px 0 8px; }
    .rb-classic .rb-item { margin-bottom: 10px; }
    .rb-classic .rb-title { font-weight: bold; }
    .rb-classic .rb-sub { font-style: italic; color: #444; font-size: 11px; }
    .rb-classic .rb-dates { float: right; color: #444; font-size: 11px; }
    .rb-classic ul { margin: 4px 0 0 18px; padding: 0; }
    .rb-classic li { margin-bottom: 2px; }
    .rb-classic p { margin: 0; }
</style>

<div class="rb-classic">
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
        <h2>Experience</h2>
        @foreach($resume->experience as $exp)
            @continue(blank($exp['role'] ?? null) && blank($exp['company'] ?? null))
            <div class="rb-item">
                <span class="rb-dates">{{ implode(' – ', array_filter([$exp['start'] ?? null, $exp['end'] ?? null])) }}</span>
                <span class="rb-title">{{ $exp['role'] ?? '' }}</span>
                @if(filled($exp['company'] ?? null))<div class="rb-sub">{{ implode(', ', array_filter([$exp['company'] ?? null, $exp['location'] ?? null])) }}</div>@endif
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
        <p style="text-align: center;">{{ implode('  •  ', $skills) }}</p>
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
            <div style="text-align: center;">{{ implode(' · ', array_filter([$cert['name'] ?? null, $cert['issuer'] ?? null, $cert['year'] ?? null])) }}</div>
        @endforeach
    @endif

    @if($languages)
        <h2>Languages</h2>
        <p style="text-align: center;">{{ implode(', ', $languages) }}</p>
    @endif
</div>

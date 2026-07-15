@php
    $skills = is_array($resume->skills ?? null) ? array_filter($resume->skills) : [];
    $languages = is_array($resume->languages ?? null) ? array_filter($resume->languages) : [];
    $contacts = array_filter([$resume->email, $resume->phone, $resume->location, $resume->linkedin, $resume->website]);
@endphp
<style>
    .rb-min { font-family: 'Helvetica', Arial, sans-serif; color: #222; font-size: 12px; line-height: 1.5; }
    .rb-min .rb-name { font-size: 22px; font-weight: bold; margin: 0; }
    .rb-min .rb-headline { color: #555; font-size: 12px; margin: 2px 0; }
    .rb-min .rb-contact { color: #666; font-size: 11px; margin-top: 3px; }
    .rb-min h2 { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #999;
        margin: 16px 0 6px; border: 0; }
    .rb-min .rb-item { margin-bottom: 9px; }
    .rb-min .rb-title { font-weight: bold; }
    .rb-min .rb-sub { color: #666; font-size: 11px; }
    .rb-min .rb-dates { float: right; color: #888; font-size: 11px; }
    .rb-min ul { margin: 3px 0 0 15px; padding: 0; }
    .rb-min li { margin-bottom: 2px; }
    .rb-min p { margin: 0; }
    .rb-min .rb-divider { border-top: 1px solid #eee; margin: 0; }
</style>

<div class="rb-min">
    <p class="rb-name">{{ $resume->full_name ?: 'Your Name' }}</p>
    @if($resume->headline)<p class="rb-headline">{{ $resume->headline }}</p>@endif
    @if($contacts)<p class="rb-contact">{{ implode('  ·  ', $contacts) }}</p>@endif

    @if($resume->summary)
        <h2>Summary</h2>
        <p style="text-align: justify;">{{ $resume->summary }}</p>
    @endif

    @if(!empty(array_filter((array) $resume->experience, fn($e) => filled($e['role'] ?? null) || filled($e['company'] ?? null))))
        <h2>Experience</h2>
        @foreach($resume->experience as $exp)
            @continue(blank($exp['role'] ?? null) && blank($exp['company'] ?? null))
            <div class="rb-item">
                <span class="rb-dates">{{ implode(' – ', array_filter([$exp['start'] ?? null, $exp['end'] ?? null])) }}</span>
                <span class="rb-title">{{ implode(', ', array_filter([$exp['role'] ?? null, $exp['company'] ?? null])) }}</span>
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
                <span>{{ implode(', ', array_filter([$edu['degree'] ?? null, $edu['field'] ?? null, $edu['school'] ?? null])) }}</span>
            </div>
        @endforeach
    @endif

    @if($skills)
        <h2>Skills</h2>
        <p>{{ implode(', ', $skills) }}</p>
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

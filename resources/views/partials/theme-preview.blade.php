@php
    use App\Support\ResumeThemes;

    $themeId = $theme ?? 'modern';
    $themeData = ResumeThemes::get($themeId);
    $layout = $themeData['layout'] ?? 'modern';
    $compact = $compact ?? false;
    $c = $themeData['colors'] ?? [];
    $primary = $c['primary'] ?? '#4f46e5';
    $light = $c['light'] ?? '#eef2ff';
@endphp

<div class="{{ $compact ? 'p-3' : 'p-4' }} rounded-xl" style="background: linear-gradient(135deg, {{ $light }}, #ffffff);">
    <div class="mx-auto {{ $compact ? 'max-w-[140px]' : 'max-w-[200px]' }} aspect-[3/4] rounded-lg bg-white p-3 shadow-sm {{ ($themeData['font'] ?? 'sans') === 'serif' ? 'font-serif' : 'font-sans' }}">
        @if ($layout === 'banner')
            <div class="mb-2 rounded p-2" style="background: {{ $primary }};">
                <div class="h-2.5 {{ $compact ? 'w-16' : 'w-20' }} rounded bg-white/90"></div>
                <div class="mt-1.5 h-1.5 {{ $compact ? 'w-20' : 'w-24' }} rounded bg-white/60"></div>
            </div>
        @elseif ($layout === 'classic')
            <div class="border-b-2 pb-2 text-center" style="border-color: {{ $c['text'] ?? '#111827' }};">
                <div class="mx-auto h-2.5 {{ $compact ? 'w-16' : 'w-20' }} rounded" style="background: {{ $primary }};"></div>
                <div class="mx-auto mt-1.5 h-1.5 {{ $compact ? 'w-20' : 'w-24' }} rounded opacity-60" style="background: {{ $primary }};"></div>
            </div>
        @elseif ($layout === 'underline')
            <div class="mb-2 border-b-4 pb-1" style="border-color: {{ $primary }};">
                <div class="h-2.5 {{ $compact ? 'w-16' : 'w-20' }} rounded" style="background: {{ $primary }};"></div>
            </div>
        @elseif ($layout === 'boxed')
            <div class="mb-2 rounded border-2 p-2" style="border-color: {{ $primary }}; background: {{ $light }};">
                <div class="h-2.5 {{ $compact ? 'w-16' : 'w-20' }} rounded" style="background: {{ $primary }};"></div>
            </div>
        @elseif ($layout === 'minimal')
            <div class="mb-2">
                <div class="h-2.5 {{ $compact ? 'w-16' : 'w-20' }} rounded" style="background: {{ $primary }};"></div>
            </div>
        @else
            <div class="mb-2 border-l-4 pl-2" style="border-color: {{ $primary }};">
                <div class="h-2.5 {{ $compact ? 'w-16' : 'w-20' }} rounded" style="background: {{ $primary }};"></div>
                <div class="mt-1.5 h-1.5 {{ $compact ? 'w-20' : 'w-24' }} rounded opacity-50" style="background: {{ $primary }};"></div>
            </div>
        @endif
        <div class="mt-3 space-y-1">
            <div class="h-1 w-full rounded" style="background: {{ $light }};"></div>
            <div class="h-1 w-11/12 rounded" style="background: {{ $light }};"></div>
            <div class="h-1 w-4/5 rounded" style="background: {{ $light }};"></div>
        </div>
        <div class="mt-3 h-1.5 {{ $compact ? 'w-12' : 'w-14' }} rounded" style="background: {{ $primary }};"></div>
        <div class="mt-2 space-y-1">
            <div class="h-1 w-full rounded" style="background: {{ $light }};"></div>
            <div class="h-1 w-10/12 rounded" style="background: {{ $light }};"></div>
        </div>
    </div>
</div>

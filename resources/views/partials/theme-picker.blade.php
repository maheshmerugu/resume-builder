@php
    use App\Models\Resume;

    $selected = $selected ?? 'modern';
    $name = $name ?? 'template';
    $compact = $compact ?? false;
    $mode = $mode ?? 'select';
@endphp

<div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
    @foreach (Resume::templateOptions() as $key => $meta)
        @php
            $activeRing = match ($meta['accent']) {
                'indigo' => 'ring-2 ring-indigo-500 border-indigo-200 bg-indigo-50/40',
                'stone' => 'ring-2 ring-stone-500 border-stone-200 bg-stone-50/40',
                'slate' => 'ring-2 ring-slate-500 border-slate-200 bg-slate-50/40',
                default => 'ring-2 ring-indigo-500 border-indigo-200 bg-indigo-50/40',
            };
            $badgeClass = match ($meta['accent']) {
                'indigo' => 'bg-indigo-50 text-indigo-700',
                'stone' => 'bg-stone-100 text-stone-700',
                'slate' => 'bg-slate-100 text-slate-700',
                default => 'bg-indigo-50 text-indigo-700',
            };
        @endphp

        @if ($mode === 'link')
            <a href="{{ route('resumes.create', ['template' => $key]) }}"
               class="admin-card group overflow-hidden border border-slate-200 transition hover:border-slate-300 hover:shadow-md">
                @include('partials.theme-preview', ['theme' => $key, 'compact' => $compact])
                <div class="border-t border-slate-100 px-3 py-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-semibold text-slate-900 group-hover:text-indigo-600">{{ $meta['label'] }}</span>
                        <span class="admin-badge {{ $badgeClass }}">{{ $meta['tagline'] }}</span>
                    </div>
                    @unless($compact)
                        <p class="mt-1 text-xs text-slate-500">{{ $meta['description'] }}</p>
                        <span class="mt-2 inline-flex text-xs font-semibold text-indigo-600">Use this theme →</span>
                    @endunless
                </div>
            </a>
        @else
            <button
                type="button"
                @click="resume.template = '{{ $key }}'"
                :class="resume.template === '{{ $key }}' ? '{{ $activeRing }}' : 'border-slate-200 hover:border-slate-300'"
                class="admin-card overflow-hidden border p-0 text-left transition"
            >
                @include('partials.theme-preview', ['theme' => $key, 'compact' => $compact])
                <div class="border-t border-slate-100 px-3 py-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-semibold text-slate-900">{{ $meta['label'] }}</span>
                        <span class="admin-badge {{ $badgeClass }}">{{ $meta['tagline'] }}</span>
                    </div>
                    @unless($compact)
                        <p class="mt-1 text-xs text-slate-500">{{ $meta['description'] }}</p>
                    @endunless
                </div>
            </button>
        @endif
    @endforeach
</div>

@if ($mode === 'select')
    <input type="hidden" name="{{ $name }}" x-model="resume.template">
@endif

@php
    use App\Support\ResumeThemes;

    $themes = $themes ?? ResumeThemes::all();
    $categories = $categories ?? ResumeThemes::categories();
    $mode = $mode ?? 'link';
    $selected = $selected ?? 'modern';
    $compact = $compact ?? false;
    $showFilters = $showFilters ?? true;
    $limit = $limit ?? null;

    $accentRings = [
        'indigo' => 'ring-indigo-500 border-indigo-200 bg-indigo-50/40 dark:bg-indigo-500/10 dark:border-indigo-500/40',
        'violet' => 'ring-violet-500 border-violet-200 bg-violet-50/40 dark:bg-violet-500/10 dark:border-violet-500/40',
        'blue' => 'ring-blue-500 border-blue-200 bg-blue-50/40 dark:bg-blue-500/10 dark:border-blue-500/40',
        'teal' => 'ring-teal-500 border-teal-200 bg-teal-50/40 dark:bg-teal-500/10 dark:border-teal-500/40',
        'emerald' => 'ring-emerald-500 border-emerald-200 bg-emerald-50/40 dark:bg-emerald-500/10 dark:border-emerald-500/40',
        'amber' => 'ring-amber-500 border-amber-200 bg-amber-50/40 dark:bg-amber-500/10 dark:border-amber-500/40',
        'rose' => 'ring-rose-500 border-rose-200 bg-rose-50/40 dark:bg-rose-500/10 dark:border-rose-500/40',
        'slate' => 'ring-slate-500 border-slate-200 bg-slate-50/40 dark:bg-slate-500/10 dark:border-slate-500/40',
        'stone' => 'ring-stone-500 border-stone-200 bg-stone-50/40 dark:bg-stone-500/10 dark:border-stone-500/40',
    ];

    $accentBadges = [
        'indigo' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300',
        'violet' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300',
        'blue' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300',
        'teal' => 'bg-teal-50 text-teal-700 dark:bg-teal-500/15 dark:text-teal-300',
        'emerald' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
        'amber' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
        'rose' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300',
        'slate' => 'bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-300',
        'stone' => 'bg-stone-100 text-stone-700 dark:bg-stone-500/15 dark:text-stone-300',
    ];
@endphp

<div
    @if($mode === 'select')
        x-data="themeGallery(@js(collect($themes)->map(fn($t, $id) => array_merge($t, ['id' => $id]))->values()->all()), @js($selected))"
    @else
        x-data="themeGallery(@js(collect($themes)->map(fn($t, $id) => array_merge($t, ['id' => $id]))->values()->all()))"
    @endif
    class="space-y-4"
>
    @if ($showFilters)
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" x-model="search" placeholder="Search themes..."
                       class="w-full rounded-xl border-slate-200 py-2.5 pl-10 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="category = ''"
                        :class="category === '' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'"
                        class="rounded-full px-3 py-1.5 text-xs font-semibold transition">All</button>
                @foreach ($categories as $cat)
                    <button type="button" @click="category = '{{ $cat }}'"
                            :class="category === '{{ $cat }}' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold transition">{{ $cat }}</button>
                @endforeach
            </div>
        </div>
        <p class="text-sm panel-muted"><span x-text="filtered.length"></span> themes available</p>
    @endif

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <template x-for="theme in filtered" :key="theme.id">
            @if ($mode === 'link')
                <a :href="`{{ url('resumes/create') }}?template=${theme.id}`"
                   class="admin-card group overflow-hidden border border-slate-200 transition hover:border-slate-300 hover:shadow-md dark:border-slate-700">
            @else
                <button type="button" @click="select(theme.id)"
                        :class="selected === theme.id ? 'ring-2 ' + ringClass(theme.accent) : 'border-slate-200 dark:border-slate-700'"
                        class="admin-card overflow-hidden border p-0 text-left transition hover:border-slate-300 dark:hover:border-slate-600">
            @endif
                    <div class="p-3" :style="previewWrap(theme)">
                        <div class="mx-auto aspect-[3/4] max-w-[140px] rounded-lg bg-white p-3 shadow-sm" :class="theme.font === 'serif' ? 'font-serif' : 'font-sans'">
                            <div :style="previewHeader(theme)">
                                <div class="h-2.5 w-16 rounded" :style="{ background: theme.layout === 'banner' ? '#fff' : theme.colors.primary }"></div>
                                <div class="mt-1.5 h-1.5 w-20 rounded opacity-70" :style="{ background: theme.layout === 'banner' ? '#fff' : theme.colors.light }"></div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <div class="h-1 w-full rounded" :style="{ background: theme.colors.light }"></div>
                                <div class="h-1 w-11/12 rounded" :style="{ background: theme.colors.light }"></div>
                            </div>
                            <div class="mt-3 h-1.5 w-12 rounded" :style="{ background: theme.colors.primary }"></div>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 px-3 py-3 dark:border-slate-800">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold panel-title" x-text="theme.label"></span>
                            <span class="admin-badge" :class="badgeClass(theme.accent)" x-text="theme.tagline"></span>
                        </div>
                        @unless($compact)
                            <p class="mt-1 text-xs panel-muted" x-text="theme.description"></p>
                            @if ($mode === 'link')
                                <span class="mt-2 inline-flex text-xs font-semibold text-indigo-600 dark:text-indigo-400">Use this theme →</span>
                            @endif
                        @endunless
                    </div>
            @if ($mode === 'link')
                </a>
            @else
                </button>
            @endif
        </template>
    </div>

    <div x-show="filtered.length === 0" class="admin-card p-10 text-center">
        <p class="panel-muted">No themes match your search.</p>
    </div>
</div>

@if ($mode === 'select')
    {{-- template field is bound on the parent resume form --}}
@endif

<script>
    function themeGallery(themes, initial = 'modern') {
        const rings = @json($accentRings);
        const badges = @json($accentBadges);

        return {
            themes,
            search: '',
            category: '',
            selected: initial,
            get filtered() {
                const q = this.search.trim().toLowerCase();
                return this.themes.filter((theme) => {
                    const matchesCategory = !this.category || theme.category === this.category;
                    const matchesSearch = !q
                        || theme.label.toLowerCase().includes(q)
                        || theme.category.toLowerCase().includes(q)
                        || theme.layout.toLowerCase().includes(q)
                        || theme.tagline.toLowerCase().includes(q);
                    return matchesCategory && matchesSearch;
                });
            },
            select(id) {
                this.selected = id;
                this.$dispatch('theme-selected', id);
            },
            ringClass(accent) {
                return rings[accent] || rings.indigo;
            },
            badgeClass(accent) {
                return badges[accent] || badges.indigo;
            },
            previewWrap(theme) {
                return { background: `linear-gradient(135deg, ${theme.colors.light}, #fff)` };
            },
            previewHeader(theme) {
                const styles = {
                    modern: { borderLeft: `4px solid ${theme.colors.primary}`, paddingLeft: '8px' },
                    classic: { borderBottom: `2px solid ${theme.colors.text}`, textAlign: 'center', paddingBottom: '6px' },
                    minimal: {},
                    banner: { background: theme.colors.primary, padding: '8px', borderRadius: '4px', marginBottom: '8px' },
                    underline: { borderBottom: `3px solid ${theme.colors.primary}`, paddingBottom: '4px' },
                    boxed: { border: `2px solid ${theme.colors.primary}`, background: theme.colors.light, padding: '8px', borderRadius: '4px' },
                };
                return styles[theme.layout] || styles.modern;
            },
        };
    }
</script>

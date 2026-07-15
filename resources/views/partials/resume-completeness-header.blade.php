{{-- Compact sticky dock — visible while scrolling (Alpine) --}}
<div x-show="progressDockVisible"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="-translate-y-2 opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="-translate-y-2 opacity-0"
     class="resume-progress-dock fixed top-16 z-40 border-b border-slate-200/80 bg-white/95 shadow-md backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-900/95 left-0 right-0">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-2.5 sm:flex-row sm:items-center sm:gap-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <div class="relative h-11 w-11 shrink-0">
                <svg class="h-11 w-11 -rotate-90" viewBox="0 0 44 44" aria-hidden="true">
                    <circle cx="22" cy="22" r="18" fill="none" stroke-width="4" class="stroke-slate-200 dark:stroke-slate-700"/>
                    <circle cx="22" cy="22" r="18" fill="none" stroke-width="4" stroke-linecap="round"
                            class="transition-all duration-500"
                            :class="completenessRingClass()"
                            :stroke-dasharray="`${completenessPercent() * 1.131} 113.1`"/>
                </svg>
                <span class="absolute inset-0 flex items-center justify-center text-[11px] font-extrabold tabular-nums" :class="completenessTextClass()" x-text="completenessPercent() + '%'"></span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">Profile completeness</p>
                    <span class="admin-badge hidden text-[10px] sm:inline-flex" :class="completenessBadgeClass()" x-text="completenessStatusBadge()"></span>
                </div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                    <div class="h-full rounded-full transition-all duration-500" :class="completenessBarClass()" :style="`width: ${completenessPercent()}%`"></div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-0.5 sm:pb-0">
            <template x-for="check in completenessChecks()" :key="'dock-' + check.key">
                <button type="button"
                        @click="scrollToSection(check.section)"
                        class="flex shrink-0 items-center gap-1 rounded-full px-2 py-1 text-[10px] font-semibold transition"
                        :class="check.done
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'
                            : (nextStep()?.section === check.section
                                ? 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-300 dark:bg-indigo-500/20 dark:text-indigo-300 dark:ring-indigo-500/40'
                                : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400')"
                        :title="check.label">
                    <span x-text="check.done ? '✓' : check.label.charAt(0)"></span>
                    <span class="hidden sm:inline" x-text="check.label"></span>
                </button>
            </template>
        </div>

        <div class="flex shrink-0 items-center gap-2">
            <template x-if="nextStep()">
                <button type="button" @click="scrollToNextStep()" class="admin-btn-primary py-2 text-xs sm:text-sm">
                    Next: <span x-text="nextStep()?.label"></span>
                </button>
            </template>
            <button type="button" @click="scrollToCompletenessPanel()" class="admin-btn-secondary py-2 text-xs sm:text-sm" title="Show full progress">
                Details
            </button>
        </div>
    </div>
</div>

{{-- Full progress panel --}}
<div id="completeness-full" class="admin-card overflow-hidden">
    <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:gap-6 sm:p-5">
        <div class="relative mx-auto h-20 w-20 shrink-0 sm:mx-0">
            <svg class="h-20 w-20 -rotate-90" viewBox="0 0 80 80" aria-hidden="true">
                <circle cx="40" cy="40" r="34" fill="none" stroke-width="6" class="stroke-slate-200 dark:stroke-slate-700"/>
                <circle cx="40" cy="40" r="34" fill="none" stroke-width="6" stroke-linecap="round"
                        class="transition-all duration-500 ease-out"
                        :class="completenessRingClass()"
                        :stroke-dasharray="`${completenessPercent() * 2.136} 213.6`"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-xl font-extrabold tabular-nums leading-none" :class="completenessTextClass()" x-text="completenessPercent() + '%'"></span>
                <span class="mt-0.5 text-[10px] font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">done</span>
            </div>
        </div>

        <div class="min-w-0 flex-1 text-center sm:text-left">
            <div class="flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 sm:text-base">Profile completeness</h2>
                <span class="admin-badge text-[11px]" :class="completenessBadgeClass()" x-text="completenessStatusBadge()"></span>
            </div>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 sm:text-sm" x-text="completenessLabel()"></p>
            <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                <div class="h-full rounded-full transition-all duration-500 ease-out"
                     :class="completenessBarClass()"
                     :style="`width: ${completenessPercent()}%`"></div>
            </div>
        </div>
    </div>

    <div x-show="nextStep()" x-cloak class="border-t border-indigo-100 bg-gradient-to-r from-indigo-50/80 to-violet-50/50 px-4 py-4 dark:border-indigo-500/20 dark:from-indigo-950/30 dark:to-violet-950/20 sm:px-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">→</span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">Next step</p>
                    <p class="mt-0.5 font-semibold text-slate-900 dark:text-slate-100">
                        Add <span x-text="nextStep()?.label?.toLowerCase()"></span>
                    </p>
                    <p class="mt-0.5 text-sm text-slate-600 dark:text-slate-400" x-text="nextStep()?.hint"></p>
                </div>
            </div>
            <button type="button" @click="scrollToNextStep()" class="admin-btn-primary shrink-0 justify-center">
                Go to section
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2 border-t border-slate-100 bg-slate-50/60 p-4 dark:border-slate-800 dark:bg-slate-800/30 sm:grid-cols-3 sm:p-5">
        <template x-for="check in completenessChecks()" :key="check.label">
            <button type="button"
                    @click="scrollToSection(check.section)"
                    class="flex items-center gap-2 rounded-xl border px-3 py-2.5 text-left transition-colors hover:shadow-sm"
                    :class="check.done
                        ? 'border-emerald-200 bg-emerald-50/80 dark:border-emerald-500/30 dark:bg-emerald-500/10'
                        : (nextStep()?.section === check.section
                            ? 'border-indigo-300 bg-indigo-50/80 ring-1 ring-indigo-200 dark:border-indigo-500/40 dark:bg-indigo-500/10 dark:ring-indigo-500/30'
                            : 'border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900/60')">
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold"
                      :class="check.done
                        ? 'bg-emerald-500 text-white'
                        : 'bg-slate-200 text-slate-400 dark:bg-slate-700 dark:text-slate-500'">
                    <svg x-show="check.done" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span x-show="!check.done" x-text="check.label.charAt(0)"></span>
                </span>
                <span class="text-xs font-medium"
                      :class="check.done ? 'text-emerald-800 dark:text-emerald-300' : 'text-slate-600 dark:text-slate-400'"
                      x-text="check.label"></span>
            </button>
        </template>
    </div>
</div>

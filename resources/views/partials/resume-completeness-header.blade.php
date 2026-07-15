<div class="rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm">
    <div class="mb-2 flex items-center justify-between gap-3">
        <p class="text-sm font-semibold text-gray-800">Resume completeness</p>
        <span class="text-xl font-bold tabular-nums" :class="completenessTextClass()" x-text="completenessPercent() + '%'"></span>
    </div>
    <div class="h-2.5 overflow-hidden rounded-full bg-gray-200">
        <div class="h-full rounded-full transition-all duration-500 ease-out"
             :class="completenessBarClass()"
             :style="`width: ${completenessPercent()}%`"></div>
    </div>
    <p class="mt-2 text-xs text-gray-500" x-text="completenessLabel()"></p>
    <div class="mt-3 flex flex-wrap gap-1.5">
        <template x-for="check in completenessChecks()" :key="check.label">
            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium"
                  :class="check.done ? 'bg-emerald-100 text-emerald-800' : 'bg-white text-gray-500 ring-1 ring-gray-200'">
                <svg x-show="check.done" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <svg x-show="!check.done" class="h-3 w-3 shrink-0 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                <span x-text="check.label"></span>
            </span>
        </template>
    </div>
</div>

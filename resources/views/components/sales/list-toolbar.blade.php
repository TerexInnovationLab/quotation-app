@props([
    'searchPlaceholder' => 'Search...',
    'statusOptions' => ['All Statuses', 'Draft', 'Sent', 'Paid', 'Unpaid', 'Open', 'Closed'],
])

<div class="bg-white/80 dark:bg-slate-900/80 border border-slate-200/70 dark:border-slate-800/80 rounded-2xl p-4 sm:p-5 shadow-sm">
    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M11 19a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21 21l-4.35-4.35" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input type="text"
                       placeholder="{{ $searchPlaceholder }}"
                       class="sales-toolbar-input w-full sm:w-80 rounded-xl border-slate-200/70 bg-white/80 pl-10 text-slate-900 placeholder:text-slate-400 focus:border-[var(--app-primary)] dark:bg-slate-900/70 dark:text-slate-100 dark:placeholder:text-slate-500 dark:border-slate-700/70">
            </div>

            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 6h16M7 12h10M10 18h4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <select class="sales-toolbar-select rounded-xl border-slate-200/70 bg-white/80 pl-9 text-slate-900 focus:border-[var(--app-primary)] dark:bg-slate-900/70 dark:text-slate-100 dark:border-slate-700/70">
                    @foreach($statusOptions as $opt)
                        <option>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <button class="inline-flex items-center gap-2 px-3.5 py-2 text-sm rounded-xl border border-slate-200/70 bg-white/80 text-slate-700 hover:bg-white hover:border-slate-300 dark:bg-slate-900/70 dark:text-slate-200 dark:border-slate-700/70 dark:hover:bg-slate-800/80">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 7h16M6 12h12M9 17h6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Filters
            </button>
        </div>

        <div class="flex items-center gap-2 app-action-bar">
            <button class="inline-flex items-center gap-2 px-3.5 py-2 text-sm rounded-xl border border-slate-200/70 bg-white/80 text-slate-700 hover:bg-white hover:border-slate-300 dark:bg-slate-900/70 dark:text-slate-200 dark:border-slate-700/70 dark:hover:bg-slate-800/80">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M3 7h18M6 12h12M10 17h4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Sort
            </button>
            <button class="inline-flex items-center gap-2 px-3.5 py-2 text-sm rounded-xl border border-slate-200/70 bg-white/80 text-slate-700 hover:bg-white hover:border-slate-300 dark:bg-slate-900/70 dark:text-slate-200 dark:border-slate-700/70 dark:hover:bg-slate-800/80">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Columns
            </button>
        </div>
    </div>

    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200/60 dark:border-slate-700/60 bg-white/70 dark:bg-slate-900/70 px-2.5 py-1">
            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
            Live updates enabled
        </span>
        <span class="hidden sm:inline">Use filters to narrow results and bulk actions for quick edits.</span>
    </div>
</div>

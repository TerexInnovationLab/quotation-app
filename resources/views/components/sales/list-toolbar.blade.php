@props([
    'searchPlaceholder' => 'Search...',
    'statusOptions' => ['All Statuses', 'Draft', 'Sent', 'Paid', 'Unpaid', 'Open', 'Closed'],
])

<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <input type="text"
                       placeholder="{{ $searchPlaceholder }}"
                       class="w-full sm:w-80 rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
            </div>

            <select class="rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                @foreach($statusOptions as $opt)
                    <option>{{ $opt }}</option>
                @endforeach
            </select>

            <button class="px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                Filters
            </button>
        </div>

        <div class="flex items-center gap-2">
            <button class="px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                Sort
            </button>
            <button class="px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                Columns
            </button>
        </div>
    </div>
</div>

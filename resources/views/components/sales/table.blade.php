@props([
    'columns' => [],   // [['key'=>'date','label'=>'Date'], ...]
    'rows' => collect(),
    'emptyTitle' => 'No records found',
    'emptyHint' => 'Try changing filters or create a new record.',
])

@php
    $statusTone = function ($value) {
        $status = strtolower(trim((string) $value));

        if ($status === '') {
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800/60 dark:text-slate-300';
        }

        if (str_contains($status, 'overdue') || str_contains($status, 'unpaid') || str_contains($status, 'void') || str_contains($status, 'cancel') || str_contains($status, 'reject')) {
            return 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200';
        }

        if (str_contains($status, 'paid') || str_contains($status, 'complete') || str_contains($status, 'approved') || str_contains($status, 'success')) {
            return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200';
        }

        if (str_contains($status, 'partial') || str_contains($status, 'pending') || str_contains($status, 'sent') || str_contains($status, 'open')) {
            return 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200';
        }

        if (str_contains($status, 'draft')) {
            return 'bg-slate-200 text-slate-700 dark:bg-slate-700/60 dark:text-slate-200';
        }

        return 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-200';
    };
@endphp

<div class="bg-white/85 dark:bg-slate-900/85 border border-slate-200/70 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100/70 dark:bg-slate-800/70 border-b border-slate-200/70 dark:border-slate-700/70">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-12">
                        <input type="checkbox" class="rounded border-slate-300 text-[var(--app-primary)] focus:ring-[var(--app-primary)] dark:border-slate-600 dark:bg-slate-900">
                    </th>
                    @foreach($columns as $col)
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            {{ $col['label'] }}
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($rows as $row)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-4 py-3">
                            <input type="checkbox" class="rounded border-slate-300 text-[var(--app-primary)] focus:ring-[var(--app-primary)] dark:border-slate-600 dark:bg-slate-900">
                        </td>

                        @foreach($columns as $col)
                            @php
                                $val = data_get($row, $col['key']);
                                $isAmount = is_numeric($val) && ($col['key'] === 'amount');
                                $label = strtolower($col['label'] ?? '');
                                $isStatus = in_array($col['key'], ['status', 'state']) || str_contains($label, 'status');
                            @endphp
                            <td class="px-4 py-3 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                @if($isStatus)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusTone($val) }}">
                                        {{ $val ?: 'Unknown' }}
                                    </span>
                                @else
                                    {{ $isAmount ? number_format($val, 0) : ($val === null || $val === '' ? '-' : $val) }}
                                @endif
                            </td>
                        @endforeach

                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            @php
                                $viewUrl = data_get($row, 'view_url');
                                $editUrl = data_get($row, 'edit_url');
                            @endphp

                            <div class="inline-flex items-center gap-2 app-action-bar">
                                @if($viewUrl)
                                    <a href="{{ $viewUrl }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs rounded-lg border border-slate-200/70 bg-white/80 text-slate-700 hover:bg-white hover:border-slate-300 dark:bg-slate-900/70 dark:text-slate-200 dark:border-slate-700/70 dark:hover:bg-slate-800/80">View</a>
                                @else
                                    <button type="button" class="px-3 py-1.5 text-xs rounded-lg border border-slate-200/70 text-slate-400 dark:border-slate-700/70 dark:text-slate-500 cursor-not-allowed" disabled>View</button>
                                @endif

                                @if($editUrl)
                                    <a href="{{ $editUrl }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs rounded-lg border border-slate-200/70 bg-white/80 text-slate-700 hover:bg-white hover:border-slate-300 dark:bg-slate-900/70 dark:text-slate-200 dark:border-slate-700/70 dark:hover:bg-slate-800/80">Edit</a>
                                @else
                                    <button type="button" class="px-3 py-1.5 text-xs rounded-lg border border-slate-200/70 text-slate-400 dark:border-slate-700/70 dark:text-slate-500 cursor-not-allowed" disabled>Edit</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 2 }}" class="px-6 py-14 text-center">
                            <div class="mx-auto max-w-md">
                                <div class="mx-auto h-12 w-12 rounded-full border border-slate-200/70 dark:border-slate-700/70 bg-white/70 dark:bg-slate-900/70 grid place-items-center text-slate-400">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M4 6h16M4 12h8M4 18h12" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="mt-4 text-base font-semibold text-slate-800 dark:text-slate-100">{{ $emptyTitle }}</div>
                                <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $emptyHint }}</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t border-slate-200/70 dark:border-slate-800/70 bg-white/70 dark:bg-slate-900/70 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        <div><span class="font-medium text-slate-700 dark:text-slate-200">{{ $rows->count() }}</span> records</div>
        <div class="flex items-center gap-2 app-action-bar">
            <button class="px-3 py-2 rounded-xl border border-slate-200/70 bg-white/80 text-slate-600 hover:bg-white hover:border-slate-300 dark:bg-slate-900/70 dark:text-slate-200 dark:border-slate-700/70 dark:hover:bg-slate-800/80">Prev</button>
            <button class="px-3 py-2 rounded-xl border border-slate-200/70 bg-white/80 text-slate-600 hover:bg-white hover:border-slate-300 dark:bg-slate-900/70 dark:text-slate-200 dark:border-slate-700/70 dark:hover:bg-slate-800/80">Next</button>
        </div>
    </div>
</div>

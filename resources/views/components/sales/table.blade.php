@props([
    'columns' => [],   // [['key'=>'date','label'=>'Date'], ...]
    'rows' => collect(),
    'emptyTitle' => 'No records found',
    'emptyHint' => 'Try changing filters or create a new record.',
])

<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 w-12">
                        <input type="checkbox" class="rounded border-slate-300">
                    </th>
                    @foreach($columns as $col)
                        <th class="px-4 py-3 text-left font-semibold text-slate-600 whitespace-nowrap">
                            {{ $col['label'] }}
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 whitespace-nowrap">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <input type="checkbox" class="rounded border-slate-300">
                        </td>

                        @foreach($columns as $col)
                            @php $val = data_get($row, $col['key']); @endphp
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ is_numeric($val) && ($col['key'] === 'amount') ? number_format($val, 0) : $val }}
                            </td>
                        @endforeach

                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <button class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 hover:bg-slate-50">View</button>
                            <button class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 hover:bg-slate-50">Edit</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 2 }}" class="px-6 py-14 text-center">
                            <div class="mx-auto max-w-md">
                                <div class="text-base font-semibold">{{ $emptyTitle }}</div>
                                <div class="text-sm text-slate-500 mt-1">{{ $emptyHint }}</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t border-slate-200 bg-white flex items-center justify-between text-xs text-slate-500">
        <div>Showing {{ $rows->count() }} records</div>
        <div class="flex items-center gap-2">
            <button class="px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">Prev</button>
            <button class="px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50">Next</button>
        </div>
    </div>
</div>

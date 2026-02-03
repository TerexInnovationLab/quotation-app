<?php

namespace App\Http\Controllers\Sales;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuotationController
{
    private function quotationRows(): \Illuminate\Support\Collection
    {
        return collect([
            [
                'date' => '2026-02-01',
                'number' => 'QT-00012',
                'customer' => 'MUST - Procurement',
                'status' => 'Sent',
                'amount' => 1250000,
                'expiry' => '2026-02-15',
            ],
            [
                'date' => '2026-01-24',
                'number' => 'QT-00011',
                'customer' => 'Terex Innovation Lab',
                'status' => 'Draft',
                'amount' => 420000,
                'expiry' => '2026-02-07',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $rows = $this->quotationRows()->map(function (array $row) {
            $row['view_url'] = route('sales.quotations.show', $row['number']);
            $row['edit_url'] = route('sales.quotations.edit', $row['number']);

            return $row;
        });

        return view('components.sales.quotations.index', compact('rows'));
    }

    public function exportPdf(Request $request)
    {
        $rows = $this->quotationRows();

        $pdf = Pdf::loadView('components.sales.quotations.pdf', [
            'rows' => $rows,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        $filename = 'quotations-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    public function create()
    {
        // dropdown lists (replace later with DB)
        $customers = [
            'MUST - Procurement',
            'Terex Innovation Lab',
            'Kumudzi Centre',
            'Nyasa Tech',
        ];

        $items = [
            ['name' => 'Consulting Services', 'rate' => 250000],
            ['name' => 'Software Subscription', 'rate' => 150000],
            ['name' => 'Training (per day)', 'rate' => 300000],
        ];

        return view('components.sales.quotations.create', compact('customers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'   => ['required', 'string', 'max:255'],
            'quotation_date'  => ['required', 'date'],
            'expiry_date'     => ['nullable', 'date'],
            'reference'       => ['nullable', 'string', 'max:255'],
            'subject'         => ['nullable', 'string', 'max:255'],
            'currency'        => ['nullable', 'string', 'max:10'],
            'notes'           => ['nullable', 'string'],
            'terms'           => ['nullable', 'string'],

            'items'                   => ['required', 'array', 'min:1'],
            'items.*.name'            => ['required', 'string', 'max:255'],
            'items.*.qty'             => ['required', 'numeric', 'min:0.01'],
            'items.*.rate'            => ['required', 'numeric', 'min:0'],
            'items.*.discount_type'   => ['nullable', 'in:fixed,percent'],
            'items.*.discount'        => ['nullable', 'numeric', 'min:0'],
            'items.*.note'            => ['nullable', 'string'],
        ]);

        return redirect()
            ->route('sales.quotations.create')
            ->with('success', 'Quotation form submitted (UI only).');
    }

    public function show(string $quotation)
    {
        $row = $this->findQuotationOrFail($quotation);

        return view('components.sales.quotations.show', compact('row'));
    }

    public function edit(string $quotation)
    {
        $row = $this->findQuotationOrFail($quotation);
        $customers = [
            'MUST - Procurement',
            'Terex Innovation Lab',
            'Kumudzi Centre',
            'Nyasa Tech',
        ];

        return view('components.sales.quotations.edit', compact('row', 'customers'));
    }

    public function update(Request $request, string $quotation)
    {
        $row = $this->findQuotationOrFail($quotation);

        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'quotation_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:quotation_date'],
            'status' => ['required', 'string', 'in:Draft,Sent,Accepted,Rejected'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        return redirect()
            ->route('sales.quotations.show', $row['number'])
            ->with('success', 'Quotation updated successfully (UI only).');
    }

    private function findQuotationOrFail(string $quotationNumber): array
    {
        $quotation = $this->quotationRows()->firstWhere('number', $quotationNumber);

        abort_unless($quotation, 404);

        return $quotation;
    }
}

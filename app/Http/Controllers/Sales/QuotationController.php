<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class QuotationController
{
    public function index(Request $request)
    {
        $rows = collect([
            [
                'date' => '2026-02-01',
                'number' => 'QT-00012',
                'customer' => 'MUST - Procurement',
                'status' => 'Sent',
                'amount' => 1250000,
            ],
            [
                'date' => '2026-01-24',
                'number' => 'QT-00011',
                'customer' => 'Terex Innovation Lab',
                'status' => 'Draft',
                'amount' => 420000,
            ],
        ]);

        return view('components.sales.quotations.index', compact('rows'));
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

    // NEW: UI-only store (validates then redirects back)
    public function store(Request $request)
    {
        $validated = $request->validate([
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

        // UI-only: no DB yet. Just show success message.
    return redirect()
        ->route('sales.quotations.create')
        ->with('success', 'Quotation form submitted (UI only).');
    }
}

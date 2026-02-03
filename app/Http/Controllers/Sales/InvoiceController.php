<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class InvoiceController
{
    public function index(Request $request)
    {
        $rows = collect([
            [
                'date' => '2026-01-30',
                'number' => 'INV-00105',
                'customer' => 'Kumudzi Centre',
                'status' => 'Unpaid',
                'amount' => 980000,
                'due' => '2026-02-15',
            ],
            [
                'date' => '2026-01-10',
                'number' => 'INV-00104',
                'customer' => 'Nyasa Tech',
                'status' => 'Paid',
                'amount' => 250000,
                'due' => '2026-01-25',
            ],
        ]);

        return view('components.sales.invoices.index', compact('rows'));
    }
}

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
}

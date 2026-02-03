<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class PaymentsReceivedController
{
    public function index(Request $request)
    {
        $rows = collect([
            [
                'date' => '2026-01-12',
                'number' => 'PAY-00044',
                'customer' => 'Nyasa Tech',
                'method' => 'Bank Transfer',
                'amount' => 250000,
                'reference' => 'NBS-TRX-39401',
            ],
        ]);

        return view('components.sales.payments.index', compact('rows'));
    }
}

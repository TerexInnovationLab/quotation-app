<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class SalesOrderController
{
    public function index(Request $request)
    {
        $rows = collect([
            [
                'date' => '2026-02-02',
                'number' => 'SO-00031',
                'customer' => 'MUST - Finance',
                'status' => 'Open',
                'amount' => 1500000,
            ],
        ]);

        return view('components.sales.orders.index', compact('rows'));
    }
}

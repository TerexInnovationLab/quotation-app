<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class DashboardController
{
    public function index(Request $request)
    {
        // Dummy KPIs
        $kpis = [
            'total_sales' => 8250000,
            'total_paid' => 5120000,
            'total_outstanding' => 3130000,
            'avg_days_to_pay' => 18,
        ];

        // Dummy time series (12 months)
        $labels12 = ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'];
        $sales12  = [420000, 610000, 780000, 540000, 890000, 950000, 720000, 1100000, 980000, 860000, 1020000, 1200000];

        // Last 6 months (slice of the above)
        $labels6 = array_slice($labels12, -6);
        $sales6  = array_slice($sales12, -6);

        // Summary counts
        $summary = [
            'quotations' => ['count' => 14, 'draft' => 5, 'sent' => 7, 'accepted' => 2],
            'invoices'   => ['count' => 22, 'paid' => 12, 'unpaid' => 7, 'overdue' => 3],
            'orders'     => ['count' => 9,  'open' => 6, 'closed' => 3],
        ];

        // Payments due by customer + simple aging buckets
        $dueByCustomer = collect([
            ['customer' => 'MUST - Procurement', 'current' => 650000, 'days_1_30' => 450000, 'days_31_60' => 120000, 'days_61_plus' => 0],
            ['customer' => 'Kumudzi Centre',     'current' => 200000, 'days_1_30' => 0,      'days_31_60' => 180000, 'days_61_plus' => 90000],
            ['customer' => 'Nyasa Tech',         'current' => 0,      'days_1_30' => 150000, 'days_31_60' => 0,      'days_61_plus' => 0],
        ])->map(function ($row) {
            $row['total_due'] = $row['current'] + $row['days_1_30'] + $row['days_31_60'] + $row['days_61_plus'];
            return $row;
        });

        // Recent activity (dummy)
        $recent = collect([
            ['type' => 'Invoice', 'ref' => 'INV-00105', 'note' => 'Created for Kumudzi Centre', 'date' => '2026-01-30'],
            ['type' => 'Payment', 'ref' => 'PAY-00044', 'note' => 'Received from Nyasa Tech',  'date' => '2026-01-12'],
            ['type' => 'Quotation', 'ref' => 'QT-00012','note' => 'Sent to MUST - Procurement','date' => '2026-02-01'],
        ]);

        return view('components.sales.dashboard.index', [
            'kpis' => $kpis,
            'summary' => $summary,
            'dueByCustomer' => $dueByCustomer,
            'recent' => $recent,
            'chart' => [
                'labels12' => $labels12,
                'sales12'  => $sales12,
                'labels6'  => $labels6,
                'sales6'   => $sales6,
            ],
        ]);
    }
}

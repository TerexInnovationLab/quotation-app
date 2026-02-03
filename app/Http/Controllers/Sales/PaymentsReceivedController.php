<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class PaymentsReceivedController
{
    private function paymentRows(): \Illuminate\Support\Collection
    {
        return collect([
            [
                'date' => '2026-01-12',
                'number' => 'PAY-00044',
                'customer' => 'Nyasa Tech',
                'method' => 'Bank Transfer',
                'amount' => 250000,
                'reference' => 'NBS-TRX-39401',
                'notes' => 'Invoice INV-00104 settlement',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $rows = $this->paymentRows()->map(function (array $row) {
            $row['view_url'] = route('sales.payments.show', $row['number']);
            $row['edit_url'] = route('sales.payments.edit', $row['number']);

            return $row;
        });

        return view('components.sales.payments.index', compact('rows'));
    }

    public function create()
    {
        $customers = [
            'Nyasa Tech',
            'Kumudzi Centre',
            'ABC Company',
            'XYZ Solutions',
        ];

        $methods = ['Bank Transfer', 'Cash', 'Card', 'Mobile Money'];

        return view('components.sales.payments.create', compact('customers', 'methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'in:Bank Transfer,Cash,Card,Mobile Money'],
            'reference' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        return redirect()
            ->route('sales.payments.index')
            ->with('success', 'Payment recorded successfully (UI only).');
    }

    public function show(string $payment)
    {
        $row = $this->findPaymentOrFail($payment);

        return view('components.sales.payments.show', compact('row'));
    }

    public function edit(string $payment)
    {
        $row = $this->findPaymentOrFail($payment);

        $customers = [
            'Nyasa Tech',
            'Kumudzi Centre',
            'ABC Company',
            'XYZ Solutions',
        ];

        $methods = ['Bank Transfer', 'Cash', 'Card', 'Mobile Money'];

        return view('components.sales.payments.edit', compact('row', 'customers', 'methods'));
    }

    public function update(Request $request, string $payment)
    {
        $row = $this->findPaymentOrFail($payment);

        $request->validate([
            'payment_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'in:Bank Transfer,Cash,Card,Mobile Money'],
            'reference' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        return redirect()
            ->route('sales.payments.show', $row['number'])
            ->with('success', 'Payment updated successfully (UI only).');
    }

    private function findPaymentOrFail(string $paymentNumber): array
    {
        $payment = $this->paymentRows()->firstWhere('number', $paymentNumber);

        abort_unless($payment, 404);

        return $payment;
    }
}

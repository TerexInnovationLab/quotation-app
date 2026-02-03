<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class ClientController
{
    private function clientRows(): \Illuminate\Support\Collection
    {
        return collect([
            [
                'code' => 'CL-00021',
                'company_name' => 'Nyasa Tech',
                'contact_person' => 'Tadala Mphande',
                'email' => 'accounts@nyasatech.mw',
                'phone' => '+265 999 456 120',
                'status' => 'Active',
                'balance' => 730000,
                'billing' => [
                    'currency' => 'MWK',
                    'payment_terms' => '30 days',
                    'tax_id' => 'MW-TAX-10021',
                    'credit_limit' => 2500000,
                    'address' => 'Area 10, Lilongwe, Malawi',
                ],
                'history' => [
                    ['date' => '2026-02-02', 'type' => 'Quotation', 'reference' => 'QT-00012', 'note' => 'Quotation sent for support retainer', 'amount' => 1250000],
                    ['date' => '2026-01-30', 'type' => 'Invoice', 'reference' => 'INV-00105', 'note' => 'Invoice created', 'amount' => 980000],
                    ['date' => '2026-01-12', 'type' => 'Payment', 'reference' => 'PAY-00044', 'note' => 'Payment received', 'amount' => 250000],
                ],
            ],
            [
                'code' => 'CL-00022',
                'company_name' => 'Kumudzi Centre',
                'contact_person' => 'Mercy Lungu',
                'email' => 'finance@kumudzi.org',
                'phone' => '+265 888 110 227',
                'status' => 'Active',
                'balance' => 1180000,
                'billing' => [
                    'currency' => 'MWK',
                    'payment_terms' => '14 days',
                    'tax_id' => 'MW-TAX-10022',
                    'credit_limit' => 1500000,
                    'address' => 'Mchesi, Lilongwe, Malawi',
                ],
                'history' => [
                    ['date' => '2026-01-30', 'type' => 'Invoice', 'reference' => 'INV-00105', 'note' => 'Invoice created', 'amount' => 980000],
                    ['date' => '2026-01-15', 'type' => 'Quotation', 'reference' => 'QT-00010', 'note' => 'Quotation accepted', 'amount' => 460000],
                ],
            ],
        ]);
    }

    public function index(Request $request)
    {
        $rows = $this->clientRows()->map(function (array $row) {
            return [
                'code' => $row['code'],
                'name' => $row['company_name'],
                'contact' => $row['contact_person'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'status' => $row['status'],
                'amount' => $row['balance'],
                'view_url' => route('sales.clients.show', $row['code']),
                'edit_url' => route('sales.clients.edit', $row['code']),
            ];
        });

        return view('components.sales.clients.index', compact('rows'));
    }

    public function create()
    {
        $currencies = ['MWK', 'USD', 'ZAR', 'EUR', 'GBP'];
        $paymentTerms = ['7 days', '14 days', '30 days', '45 days', '60 days'];

        return view('components.sales.clients.create', compact('currencies', 'paymentTerms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_code' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
            'billing_address' => ['required', 'string'],
            'currency' => ['required', 'in:MWK,USD,ZAR,EUR,GBP'],
            'payment_terms' => ['required', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
        ]);

        return redirect()
            ->route('sales.clients.index')
            ->with('success', 'Client created successfully (UI only).');
    }

    public function show(string $client)
    {
        $row = $this->findClientOrFail($client);

        return view('components.sales.clients.show', compact('row'));
    }

    public function edit(string $client)
    {
        $row = $this->findClientOrFail($client);
        $currencies = ['MWK', 'USD', 'ZAR', 'EUR', 'GBP'];
        $paymentTerms = ['7 days', '14 days', '30 days', '45 days', '60 days'];

        return view('components.sales.clients.edit', compact('row', 'currencies', 'paymentTerms'));
    }

    public function update(Request $request, string $client)
    {
        $row = $this->findClientOrFail($client);

        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
            'billing_address' => ['required', 'string'],
            'currency' => ['required', 'in:MWK,USD,ZAR,EUR,GBP'],
            'payment_terms' => ['required', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
        ]);

        return redirect()
            ->route('sales.clients.show', $row['code'])
            ->with('success', 'Client profile and billing updated (UI only).');
    }

    private function findClientOrFail(string $clientCode): array
    {
        $client = $this->clientRows()->firstWhere('code', $clientCode);

        abort_unless($client, 404);

        return $client;
    }
}

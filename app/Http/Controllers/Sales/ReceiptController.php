<?php

namespace App\Http\Controllers\Sales;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceiptController
{
    private function receiptRows(): \Illuminate\Support\Collection
    {
        return collect([
            [
                'date' => '2026-02-03',
                'number' => 'RCPT-00052',
                'customer' => 'Kumudzi Centre',
                'status' => 'Issued',
                'method' => 'Bank Transfer',
                'reference' => 'INV-00105',
                'amount' => 980000,
                'notes' => 'Payment received for invoice INV-00105.',
            ],
            [
                'date' => '2026-01-20',
                'number' => 'RCPT-00051',
                'customer' => 'Nyasa Tech',
                'status' => 'Draft',
                'method' => 'Cash',
                'reference' => 'INV-00104',
                'amount' => 250000,
                'notes' => 'Partial settlement on invoice INV-00104.',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $rows = $this->receiptRows()->map(function (array $row) {
            $row['view_url'] = route('sales.receipts.show', $row['number']);
            $row['edit_url'] = route('sales.receipts.edit', $row['number']);

            return $row;
        });

        return view('components.sales.receipts.index', compact('rows'));
    }

    public function create()
    {
        $customers = [
            'Kumudzi Centre',
            'Nyasa Tech',
            'MUST - Procurement',
            'Terex Innovation Lab',
        ];

        $methods = ['Bank Transfer', 'Cash', 'Card', 'Mobile Money'];

        return view('components.sales.receipts.create', compact('customers', 'methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receipt_number' => ['required', 'string', 'max:255'],
            'receipt_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'in:Bank Transfer,Cash,Card,Mobile Money'],
            'reference' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:Draft,Issued,Sent'],
        ]);

        return redirect()
            ->route('sales.receipts.index')
            ->with('success', 'Receipt saved successfully (UI only).');
    }

    public function show(string $receipt)
    {
        $row = $this->findReceiptOrFail($receipt);
        $document = $this->buildReceiptDocument($row);

        return view('components.sales.receipts.show', [
            'row' => $row,
            'receipt' => $document['receipt'],
        ]);
    }

    public function edit(string $receipt)
    {
        $row = $this->findReceiptOrFail($receipt);
        $document = $this->buildReceiptDocument($row);

        return view('components.sales.receipts.edit', [
            'row' => $row,
            'receipt' => $document['receipt'],
        ]);
    }

    public function update(Request $request, string $receipt)
    {
        $row = $this->findReceiptOrFail($receipt);

        $request->validate([
            'receipt_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'in:Bank Transfer,Cash,Card,Mobile Money'],
            'reference' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:Draft,Issued,Sent'],
        ]);

        return redirect()
            ->route('sales.receipts.show', $row['number'])
            ->with('success', 'Receipt updated successfully (UI only).');
    }

    public function pdf(string $receipt)
    {
        $row = $this->findReceiptOrFail($receipt);
        $document = $this->buildReceiptDocument($row);

        $pdf = Pdf::loadView('components.sales.receipts.single-pdf', [
            'row' => $row,
            'receipt' => $document['receipt'],
            'company' => $this->companyProfile(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($row['number'] . '.pdf');
    }

    public function downloadPdf(string $receipt)
    {
        $row = $this->findReceiptOrFail($receipt);
        $document = $this->buildReceiptDocument($row);

        $pdf = Pdf::loadView('components.sales.receipts.single-pdf', [
            'row' => $row,
            'receipt' => $document['receipt'],
            'company' => $this->companyProfile(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download($row['number'] . '.pdf');
    }

    public function send(Request $request, string $receipt)
    {
        $this->findReceiptOrFail($receipt);

        return response()->json([
            'success' => true,
            'message' => 'Receipt sent successfully (UI only)',
        ]);
    }

    private function findReceiptOrFail(string $receiptNumber): array
    {
        $receipt = $this->receiptRows()->firstWhere('number', $receiptNumber);

        abort_unless($receipt, 404);

        return $receipt;
    }

    private function buildReceiptDocument(array $row): array
    {
        $receipt = [
            'receipt_number' => $row['number'],
            'receipt_date' => $row['date'],
            'customer_name' => $row['customer'],
            'method' => $row['method'],
            'reference' => $row['reference'],
            'amount' => $row['amount'],
            'currency' => 'MWK',
            'notes' => $row['notes'] ?? '',
            'status' => $row['status'] ?? 'Issued',
        ];

        return compact('receipt');
    }

    private function companyProfile(): array
    {
        $logo = null;
        $signature = null;
        $logoPaths = [
            public_path('images/terex_innovation_lab_logo.jpg'),
            public_path('images/company-logo.png'),
            public_path('images/company-logo.jpg'),
            public_path('images/company-logo.jpeg'),
            public_path('logo.png'),
            public_path('logo.jpg'),
            public_path('logo.jpeg'),
        ];
        $signaturePaths = [
            public_path('images/richard_chilipa_signature.jpg'),
            public_path('images/ceo-signature.png'),
            public_path('images/ceo-signature.jpg'),
            public_path('images/ceo-signature.jpeg'),
            public_path('images/signature.png'),
            public_path('images/signature.jpg'),
            public_path('images/signature.jpeg'),
        ];

        foreach ($logoPaths as $path) {
            if (! is_file($path)) {
                continue;
            }

            $mime = mime_content_type($path) ?: 'image/png';
            $logo = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            break;
        }

        foreach ($signaturePaths as $path) {
            if (! is_file($path)) {
                continue;
            }

            $mime = mime_content_type($path) ?: 'image/png';
            $signature = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            break;
        }

        return [
            'name' => 'Terex Innovation Lab',
            'tagline' => 'Innovating for Malawi\'s Digital Economy',
            'email' => 'info@terexlab.com',
            'phone' => '265 999852 222',
            'address' => 'Smythe Road, Sunny Side, Blantyre, Malawi',
            'logo' => $logo,
            'signature' => $signature,
            'signatory_name' => 'Richard Chilipa',
            'signatory_title' => 'Chief Executive Officer',
        ];
    }
}

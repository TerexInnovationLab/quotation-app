<?php

namespace App\Http\Controllers\Sales;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
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
            'company' => $this->companyProfile(),
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
        $document = $this->buildQuotationDocument($row);

        return view('components.sales.quotations.show', [
            'row' => $row,
            'quotation' => $document['quotation'],
            'items' => $document['items'],
        ]);
    }

    /**
     * Public verification view for QR scans.
     */
    public function verify(string $quotation)
    {
        $row = $this->findQuotationOrFail($quotation);
        $document = $this->buildQuotationDocument($row);
        $company = $this->companyProfile();

        return view('verify.quotation', [
            'row' => $row,
            'quotation' => $document['quotation'],
            'company' => $company,
            'verifiedAt' => now(),
            'verifyUrl' => route('quotations.verify', $row['number']),
        ]);
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

    public function pdf(string $quotation)
    {
        $row = $this->findQuotationOrFail($quotation);
        $document = $this->buildQuotationDocument($row);
        $template = request()->cookie('quotation_template', 'Template 3');
        $templateColor = request()->cookie('quotation_template_color', '');

        $company = $this->companyProfile();

        $pdf = Pdf::loadView('components.sales.quotations.single-pdf', [
            'row' => $row,
            'quotation' => $document['quotation'],
            'items' => $document['items'],
            'generatedAt' => now(),
            'company' => $company,
            'template' => $template,
            'templateColor' => $templateColor,
            'documentQr' => $this->qrCodeDataUri($this->quotationQrPayload($row, $document['quotation'], $company)),
            'documentUrl' => route('sales.quotations.show', $row['number']),
            'watermarkText' => 'QUOTATION',
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($row['number'] . '.pdf');
    }

    public function downloadPdf(string $quotation)
    {
        $row = $this->findQuotationOrFail($quotation);
        $document = $this->buildQuotationDocument($row);
        $template = request()->cookie('quotation_template', 'Template 3');
        $templateColor = request()->cookie('quotation_template_color', '');

        $company = $this->companyProfile();

        $pdf = Pdf::loadView('components.sales.quotations.single-pdf', [
            'row' => $row,
            'quotation' => $document['quotation'],
            'items' => $document['items'],
            'generatedAt' => now(),
            'company' => $company,
            'template' => $template,
            'templateColor' => $templateColor,
            'documentQr' => $this->qrCodeDataUri($this->quotationQrPayload($row, $document['quotation'], $company)),
            'documentUrl' => route('sales.quotations.show', $row['number']),
            'watermarkText' => 'QUOTATION',
        ])->setPaper('a4', 'portrait');

        return $pdf->download($row['number'] . '.pdf');
    }

    public function convertToInvoice(string $quotation)
    {
        $row = $this->findQuotationOrFail($quotation);
        $digits = preg_replace('/\D+/', '', $row['number']) ?: '00000';

        return redirect()
            ->route('sales.invoices.create', [
                'customer_name' => $row['customer'],
                'invoice_number' => 'INV-' . $digits,
                'invoice_date' => $row['date'],
                'due_date' => $row['expiry'] ?? now()->addDays(14)->toDateString(),
                'subject' => 'Converted from quotation ' . $row['number'],
                'notes' => 'Converted from quotation ' . $row['number'],
                'currency' => 'MWK',
            ])
            ->with('success', 'Invoice draft opened from quotation. Review and save.');
    }

    private function findQuotationOrFail(string $quotationNumber): array
    {
        $quotation = $this->quotationRows()->firstWhere('number', $quotationNumber);

        abort_unless($quotation, 404);

        return $quotation;
    }

    private function buildQuotationDocument(array $row): array
    {
        $vatRate = 16.5;
        $grandTotal = (int) ($row['amount'] ?? 0);
        $subTotal = (int) round($grandTotal / (1 + ($vatRate / 100)));
        $vatAmount = $grandTotal - $subTotal;

        $items = collect([
            [
                'name' => 'Professional Services',
                'note' => 'Quoted service package for ' . $row['customer'],
                'qty' => 1,
                'rate' => $subTotal,
                'discount_type' => 'fixed',
                'discount' => 0,
                'line_total' => $subTotal,
            ],
        ]);

        $quotation = [
            'customer_name' => $row['customer'],
            'quotation_number' => $row['number'],
            'quotation_date' => $row['date'],
            'expiry_date' => $row['expiry'] ?? now()->addDays(14)->toDateString(),
            'subject' => 'Quotation ' . $row['number'],
            'currency' => 'MWK',
            'vat_rate' => $vatRate,
            'notes' => 'Pricing based on current scope and assumptions.',
            'terms' => 'Validity until ' . ($row['expiry'] ?? now()->addDays(14)->toDateString()) . '.',
            'sub_total' => $subTotal,
            'vat_amount' => $vatAmount,
            'grand_total' => $grandTotal,
        ];

        return compact('quotation', 'items');
    }

    private function companyProfile(): array
    {
        $logo = null;
        $seal = null;
        $ceoSignature = null;
        $logoPaths = [
            public_path('images/company-logo.png'),
            public_path('images/company-logo.jpg'),
            public_path('images/company-logo.jpeg'),
            public_path('logo.png'),
            public_path('logo.jpg'),
            public_path('logo.jpeg'),
        ];
        $sealPaths = [
            public_path('images/company-seal.png'),
            public_path('images/company-seal.jpg'),
            public_path('images/company-seal.jpeg'),
            public_path('images/company-stamp.png'),
            public_path('images/company-stamp.jpg'),
            public_path('images/company-stamp.jpeg'),
            public_path('seal.png'),
            public_path('seal.jpg'),
            public_path('seal.jpeg'),
            public_path('stamp.png'),
            public_path('stamp.jpg'),
            public_path('stamp.jpeg'),
        ];
        $ceoSignaturePaths = [
            public_path('images/richard_chilipa_signature.jpg'),
            public_path('images/ceo-signature.png'),
            public_path('images/ceo-signature.jpg'),
            public_path('images/ceo-signature.jpeg'),
            public_path('images/signature.png'),
            public_path('images/signature.jpg'),
            public_path('images/signature.jpeg'),
            public_path('ceo-signature.png'),
            public_path('ceo-signature.jpg'),
            public_path('ceo-signature.jpeg'),
            public_path('signature.png'),
            public_path('signature.jpg'),
            public_path('signature.jpeg'),
        ];

        foreach ($logoPaths as $path) {
            if (! is_file($path)) {
                continue;
            }

            $mime = mime_content_type($path) ?: 'image/png';
            $logo = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            break;
        }

        foreach ($sealPaths as $path) {
            if (! is_file($path)) {
                continue;
            }

            $mime = mime_content_type($path) ?: 'image/png';
            $seal = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            break;
        }

        foreach ($ceoSignaturePaths as $path) {
            if (! is_file($path)) {
                continue;
            }

            $mime = mime_content_type($path) ?: 'image/png';
            $ceoSignature = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            break;
        }

        return [
            'name' => 'Terex Innovation Lab',
            'tagline' => 'Innovating for Malawi Digital Economy',
            'email' => 'info@terexlab.com',
            'phone' => '+265 999 852 222',
            'address' => 'Blantyre, Malawi',
            'ceo_name' => 'Richard Chilipa',
            'ceo_title' => 'Terex Innovation Lab CEO',
            'logo' => $logo,
            'seal' => $seal,
            'ceo_signature' => $ceoSignature,
        ];
    }

    private function quotationQrPayload(array $row, array $quotation, array $company): string
    {
        $companyName = (string) ($company['name'] ?? 'Terex Innovation Lab');
        $companyEmail = (string) ($company['email'] ?? '');
        $companyPhone = (string) ($company['phone'] ?? '');
        $contact = trim($companyEmail . ($companyEmail && $companyPhone ? ' | ' : '') . $companyPhone);

        return implode("\n", [
            'DOCUMENT: QUOTATION',
            'NUMBER: ' . $quotation['quotation_number'],
            'STATUS: ' . strtoupper((string) ($row['status'] ?? '')),
            'ISSUE DATE: ' . $quotation['quotation_date'],
            'VALID UNTIL: ' . $quotation['expiry_date'],
            'CUSTOMER: ' . $quotation['customer_name'],
            'AMOUNT: ' . $quotation['currency'] . ' ' . number_format((float) $quotation['grand_total'], 2, '.', ''),
            'OWNER: ' . $companyName,
            'CONTACT: ' . ($contact !== '' ? $contact : 'N/A'),
            'SECURITY: Verified by ' . $companyName,
            'VERIFY: ' . route('quotations.verify', $row['number']),
        ]);
    }

    private function qrCodeDataUri(string $payload): ?string
    {
        try {
            $renderer = new ImageRenderer(
                new RendererStyle(220, 2),
                new SvgImageBackEnd(),
            );
            $writer = new Writer($renderer);
            $svg = $writer->writeString($payload);

            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        } catch (\Throwable) {
            return null;
        }
    }
}

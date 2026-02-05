<?php

namespace App\Http\Controllers\Sales;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController
{
    /**
     * Sample invoice rows (UI-only data for now).
     */
    private function invoiceRows(): \Illuminate\Support\Collection
    {
        return collect([
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
    }

    /**
     * Show list of invoices
     */
    public function index(Request $request)
    {
        $rows = $this->invoiceRows()->map(function (array $row) {
            $row['view_url'] = route('sales.invoices.show', $row['number']);
            $row['edit_url'] = route('sales.invoices.edit', $row['number']);

            return $row;
        });

        return view('components.sales.invoices.index', compact('rows'));
    }

    /**
     * Export currently listed invoices to PDF.
     */
    public function exportPdf(Request $request)
    {
        $rows = $this->invoiceRows();

        $pdf = Pdf::loadView('components.sales.invoices.pdf', [
            'rows' => $rows,
            'generatedAt' => now(),
            'company' => $this->companyProfile(),
        ])->setPaper('a4', 'portrait');

        $filename = 'invoices-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show invoice creation form
     */
    public function create(Request $request)
    {
        // Sample customers for dropdown
        $customers = ['Kumudzi Centre', 'Nyasa Tech', 'ABC Company', 'XYZ Solutions'];

        // Sample items for quick pick
        $items = [
            ['name' => 'Professional Services', 'rate' => 0],
            ['name' => 'Consulting', 'rate' => 0],
            ['name' => 'Development', 'rate' => 0],
        ];

        return view('components.sales.invoices.create', compact('customers', 'items'));
    }

    /**
     * Store invoice
     */
    public function store(Request $request)
    {
        // For now, just validate and redirect back
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'invoice_number' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'currency' => 'required|string|in:MWK,USD,ZAR,EUR,GBP',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        return redirect()
            ->route('sales.invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    /**
     * Preview invoice before exporting/sharing.
     */
    public function preview(Request $request)
    {
        $currency = (string) $request->input('currency', 'MWK');
        $vatRate = min(max((float) $request->input('vat_rate', 0), 0), 100);

        $items = collect($request->input('items', []))
            ->map(function ($item) {
                $discountType = in_array($item['discount_type'] ?? 'fixed', ['fixed', 'percent'], true)
                    ? $item['discount_type']
                    : 'fixed';

                $qty = max((float) ($item['qty'] ?? 0), 0);
                $rate = max((float) ($item['rate'] ?? 0), 0);
                $discount = max((float) ($item['discount'] ?? 0), 0);
                $lineSubtotal = $qty * $rate;
                $lineDiscount = $discountType === 'percent'
                    ? $lineSubtotal * (min($discount, 100) / 100)
                    : min($discount, $lineSubtotal);
                $lineTotal = max($lineSubtotal - $lineDiscount, 0);

                return [
                    'name' => trim((string) ($item['name'] ?? '')),
                    'note' => trim((string) ($item['note'] ?? '')),
                    'qty' => $qty,
                    'rate' => $rate,
                    'discount_type' => $discountType,
                    'discount' => $discount,
                    'line_subtotal' => round($lineSubtotal),
                    'line_discount' => round($lineDiscount),
                    'line_total' => round($lineTotal),
                ];
            })
            ->filter(fn ($item) => $item['name'] !== '' || $item['line_total'] > 0 || $item['note'] !== '')
            ->values();

        $subTotal = (int) $items->sum('line_total');
        $vatAmount = (int) round($subTotal * ($vatRate / 100));

        $invoice = [
            'customer_name' => (string) $request->input('customer_name', ''),
            'invoice_number' => (string) $request->input('invoice_number', 'DRAFT'),
            'invoice_date' => (string) $request->input('invoice_date', now()->toDateString()),
            'due_date' => (string) $request->input('due_date', ''),
            'subject' => (string) $request->input('subject', ''),
            'currency' => $currency,
            'vat_rate' => $vatRate,
            'notes' => (string) $request->input('notes', ''),
            'terms' => (string) $request->input('terms', ''),
            'sub_total' => $subTotal,
            'vat_amount' => $vatAmount,
            'grand_total' => $subTotal + $vatAmount,
        ];

        return view('components.sales.invoices.preview', compact('invoice', 'items'));
    }

    /**
     * Show invoice details
     */
    public function show(string $invoice)
    {
        $row = $this->findInvoiceOrFail($invoice);
        $document = $this->buildInvoiceDocument($row);

        return view('components.sales.invoices.show', [
            'row' => $row,
            'invoice' => $document['invoice'],
            'items' => $document['items'],
        ]);
    }

    /**
     * Show invoice edit form
     */
    public function edit(string $invoice)
    {
        $row = $this->findInvoiceOrFail($invoice);
        $customers = ['Kumudzi Centre', 'Nyasa Tech', 'ABC Company', 'XYZ Solutions'];

        return view('components.sales.invoices.edit', compact('row', 'customers'));
    }

    /**
     * Update invoice (UI-only)
     */
    public function update(Request $request, string $invoice)
    {
        $row = $this->findInvoiceOrFail($invoice);

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|string|in:Draft,Sent,Paid,Unpaid,Overdue',
            'amount' => 'required|numeric|min:0',
        ]);

        return redirect()
            ->route('sales.invoices.show', $row['number'])
            ->with('success', 'Invoice updated successfully (UI only).');
    }

    /**
     * Download invoice as PDF
     */
    public function pdf(string $invoice)
    {
        $row = $this->findInvoiceOrFail($invoice);
        $document = $this->buildInvoiceDocument($row);
        $generatedAt = now();

        $pdf = Pdf::loadView('components.sales.invoices.single-pdf', [
            'row' => $row,
            'invoice' => $document['invoice'],
            'items' => $document['items'],
            'generatedAt' => $generatedAt,
            'company' => $this->companyProfile(),
            'documentQr' => $this->qrCodeDataUri($this->invoiceQrPayload($row, $document['invoice'])),
            'stampDataUri' => $this->stampDataUri($generatedAt),
            'documentUrl' => route('sales.invoices.show', $row['number']),
            'watermarkText' => 'INVOICE',
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($row['number'] . '.pdf');
    }

    /**
     * Download invoice as PDF
     */
    public function downloadPdf(string $invoice)
    {
        $row = $this->findInvoiceOrFail($invoice);
        $document = $this->buildInvoiceDocument($row);
        $generatedAt = now();

        $pdf = Pdf::loadView('components.sales.invoices.single-pdf', [
            'row' => $row,
            'invoice' => $document['invoice'],
            'items' => $document['items'],
            'generatedAt' => $generatedAt,
            'company' => $this->companyProfile(),
            'documentQr' => $this->qrCodeDataUri($this->invoiceQrPayload($row, $document['invoice'])),
            'stampDataUri' => $this->stampDataUri($generatedAt),
            'documentUrl' => route('sales.invoices.show', $row['number']),
            'watermarkText' => 'INVOICE',
        ])->setPaper('a4', 'portrait');

        return $pdf->download($row['number'] . '.pdf');
    }

    public function convertToReceipt(string $invoice)
    {
        $row = $this->findInvoiceOrFail($invoice);
        $digits = preg_replace('/\D+/', '', $row['number']) ?: '00000';

        return redirect()
            ->route('sales.payments.create', [
                'payment_number' => 'RCPT-' . $digits,
                'customer_name' => $row['customer'],
                'method' => 'Cash',
                'reference' => $row['number'],
                'amount' => $row['amount'],
                'notes' => 'Converted from invoice ' . $row['number'],
            ])
            ->with('success', 'Receipt draft opened. Confirm and save to record payment.');
    }

    /**
     * Send invoice via email
     */
    public function send(Request $request)
    {
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Invoice sent successfully (frontend only)',
        ]);
    }

    private function findInvoiceOrFail(string $invoiceNumber): array
    {
        $invoice = $this->invoiceRows()->firstWhere('number', $invoiceNumber);

        abort_unless($invoice, 404);

        return $invoice;
    }

    private function buildInvoiceDocument(array $row): array
    {
        $vatRate = 16.5;
        $grandTotal = (int) ($row['amount'] ?? 0);
        $subTotal = (int) round($grandTotal / (1 + ($vatRate / 100)));
        $vatAmount = $grandTotal - $subTotal;

        $items = collect([
            [
                'name' => 'Professional Services',
                'note' => 'Service charge for invoice ' . $row['number'],
                'qty' => 1,
                'rate' => $subTotal,
                'discount_type' => 'fixed',
                'discount' => 0,
                'line_total' => $subTotal,
            ],
        ]);

        $invoice = [
            'customer_name' => $row['customer'],
            'invoice_number' => $row['number'],
            'invoice_date' => $row['date'],
            'due_date' => $row['due'],
            'subject' => 'Invoice ' . $row['number'],
            'currency' => 'MWK',
            'vat_rate' => $vatRate,
            'notes' => 'Thank you for your business.',
            'terms' => 'Payment due by ' . $row['due'] . '.',
            'sub_total' => $subTotal,
            'vat_amount' => $vatAmount,
            'grand_total' => $grandTotal,
        ];

        return compact('invoice', 'items');
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

    private function invoiceQrPayload(array $row, array $invoice): string
    {
        return implode("\n", [
            'DOCUMENT: INVOICE',
            'NUMBER: ' . $invoice['invoice_number'],
            'CUSTOMER: ' . $invoice['customer_name'],
            'AMOUNT: ' . $invoice['currency'] . ' ' . number_format((float) $invoice['grand_total'], 2, '.', ''),
            'DATE: ' . $invoice['invoice_date'],
            'DUE: ' . $invoice['due_date'],
            'VERIFY: ' . route('sales.invoices.show', $row['number']),
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

    private function stampDataUri(\DateTimeInterface $generatedAt): ?string
    {
        try {
            if (! function_exists('imagettftext')) {
                return null;
            }

            $templatePath = collect([
                public_path('images/terex_stamp.png'),
                public_path('images/terex_stamp.jpg'),
                public_path('images/terex_stamp.jpeg'),
                public_path('images/stamps/stamp-template.png'),
            ])->first(fn ($path) => is_file($path));

            if (! $templatePath) {
                return null;
            }

            $fontPath = collect([
                public_path('fonts/Oswald-Bold.ttf'),
                public_path('fonts/Arial-Bold.ttf'),
                'C:\\Windows\\Fonts\\arialbd.ttf',
            ])->first(fn ($path) => is_file($path));

            if (! $fontPath) {
                return null;
            }

            $ext = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));
            $image = match ($ext) {
                'png' => imagecreatefrompng($templatePath),
                'jpg', 'jpeg' => imagecreatefromjpeg($templatePath),
                default => null,
            };

            if (! $image) {
                return null;
            }

            imagealphablending($image, true);
            imagesavealpha($image, true);

            $text = strtoupper($generatedAt->format('jS M Y'));
            $color = imagecolorallocate($image, 43, 78, 118);

            $fontSize = 56;
            $angle = 0;

            $w = imagesx($image);
            $h = imagesy($image);
            $bbox = imagettfbbox($fontSize, $angle, $fontPath, $text);
            $textW = abs($bbox[2] - $bbox[0]);
            $textH = abs($bbox[7] - $bbox[1]);
            $x = (int) round(($w - $textW) / 2);
            $y = (int) round(($h / 2) + ($textH / 2) + 8);

            imagettftext($image, $fontSize, $angle, $x, $y, $color, $fontPath, $text);

            ob_start();
            imagepng($image, null, 9);
            $png = ob_get_clean();
            imagedestroy($image);

            if (! $png) {
                return null;
            }

            return 'data:image/png;base64,' . base64_encode($png);
        } catch (\Throwable) {
            return null;
        }
    }
}

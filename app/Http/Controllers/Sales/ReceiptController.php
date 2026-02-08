<?php

namespace App\Http\Controllers\Sales;

use App\Support\SalesSettings;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
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
        [$template, $templateColor] = $this->resolveTemplateSettings();
        $generatedAt = now();
        $company = $this->companyProfile();

        $pdf = Pdf::loadView('components.sales.receipts.single-pdf', [
            'row' => $row,
            'receipt' => $document['receipt'],
            'company' => $company,
            'generatedAt' => $generatedAt,
            'template' => $template,
            'templateColor' => $templateColor,
            'documentQr' => $this->qrCodeDataUri($this->receiptQrPayload($row, $document['receipt'], $company)),
            'stampDataUri' => $this->stampDataUri($generatedAt),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($row['number'] . '.pdf');
    }

    public function downloadPdf(string $receipt)
    {
        $row = $this->findReceiptOrFail($receipt);
        $document = $this->buildReceiptDocument($row);
        [$template, $templateColor] = $this->resolveTemplateSettings();
        $generatedAt = now();
        $company = $this->companyProfile();

        $pdf = Pdf::loadView('components.sales.receipts.single-pdf', [
            'row' => $row,
            'receipt' => $document['receipt'],
            'company' => $company,
            'generatedAt' => $generatedAt,
            'template' => $template,
            'templateColor' => $templateColor,
            'documentQr' => $this->qrCodeDataUri($this->receiptQrPayload($row, $document['receipt'], $company)),
            'stampDataUri' => $this->stampDataUri($generatedAt),
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

    private function resolveTemplateSettings(): array
    {
        $template = (string) request()->cookie('document_template', '');
        if ($template === '') {
            foreach (['invoice_template', 'quotation_template', 'letter_template', 'receipt_template'] as $key) {
                $value = (string) request()->cookie($key, '');
                if ($value !== '') {
                    $template = $value;
                    break;
                }
            }
        }
        if ($template === '') {
            $template = 'Template 2';
        }

        $templateColor = (string) request()->cookie('document_template_color', '');
        if ($templateColor === '') {
            foreach (['invoice_template_color', 'quotation_template_color', 'letter_template_color', 'receipt_template_color'] as $key) {
                $value = (string) request()->cookie($key, '');
                if ($value !== '') {
                    $templateColor = $value;
                    break;
                }
            }
        }

        return [$template, $templateColor];
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
        $settings = SalesSettings::get();
        $profile = $settings['profile'] ?? [];
        $companySettings = $settings['company'] ?? [];
        $logoPaths = array_filter([
            SalesSettings::logoStoragePath(),
            public_path('images/terex_innovation_lab_logo.jpg'),
            public_path('images/company-logo.png'),
            public_path('images/company-logo.jpg'),
            public_path('images/company-logo.jpeg'),
            public_path('logo.png'),
            public_path('logo.jpg'),
            public_path('logo.jpeg'),
        ]);
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

        $company = [
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

        if (! empty($companySettings['name'])) {
            $company['name'] = $companySettings['name'];
        }
        if (! empty($companySettings['tagline'])) {
            $company['tagline'] = $companySettings['tagline'];
        }
        if (! empty($companySettings['email'])) {
            $company['email'] = $companySettings['email'];
        }
        if (! empty($companySettings['phone'])) {
            $company['phone'] = $companySettings['phone'];
        }
        if (! empty($companySettings['address'])) {
            $company['address'] = $companySettings['address'];
        }
        if (! empty($profile['full_name'])) {
            $company['signatory_name'] = $profile['full_name'];
        }
        if (! empty($profile['role'])) {
            $company['signatory_title'] = $profile['role'];
        }

        return $company;
    }

    private function receiptQrPayload(array $row, array $receipt, array $company): string
    {
        $companyName = (string) ($company['name'] ?? 'Terex Innovation Lab');
        $companyEmail = (string) ($company['email'] ?? '');
        $companyPhone = (string) ($company['phone'] ?? '');
        $contact = trim($companyEmail . ($companyEmail && $companyPhone ? ' | ' : '') . $companyPhone);

        return implode("\n", [
            'DOCUMENT: RECEIPT',
            'NUMBER: ' . $receipt['receipt_number'],
            'STATUS: ' . strtoupper((string) ($row['status'] ?? '')),
            'DATE ISSUED: ' . $receipt['receipt_date'],
            'CUSTOMER: ' . $receipt['customer_name'],
            'METHOD: ' . $receipt['method'],
            'AMOUNT: ' . $receipt['currency'] . ' ' . number_format((float) $receipt['amount'], 2, '.', ''),
            'REFERENCE: ' . ($receipt['reference'] ?: 'N/A'),
            'OWNER: ' . $companyName,
            'CONTACT: ' . ($contact !== '' ? $contact : 'N/A'),
            'VIEW: ' . route('sales.receipts.show', $row['number']),
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

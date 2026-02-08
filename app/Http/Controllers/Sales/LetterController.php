<?php

namespace App\Http\Controllers\Sales;

use App\Support\SalesSettings;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LetterController
{
    private function letterRows(): \Illuminate\Support\Collection
    {
        return collect([
            [
                'date' => '2026-01-30',
                'number' => 'LTR-00012',
                'recipient' => 'Copyright Society of Malawi (COSOMA)',
                'subject' => 'Proposal for the Development of a Membership Management Portal',
                'status' => 'Sent',
                'email' => 'info@cosoma.mw',
            ],
            [
                'date' => '2026-01-18',
                'number' => 'LTR-00011',
                'recipient' => 'MUST - Procurement',
                'subject' => 'Request for Proposal: Student Portal Support',
                'status' => 'Draft',
                'email' => 'procurement@must.ac.mw',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $rows = $this->letterRows()->map(function (array $row) {
            $row['view_url'] = route('sales.letters.show', $row['number']);
            $row['edit_url'] = route('sales.letters.edit', $row['number']);

            return $row;
        });

        return view('components.sales.letters.index', compact('rows'));
    }

    public function create()
    {
        $recipients = [
            'Copyright Society of Malawi (COSOMA)',
            'MUST - Procurement',
            'Nyasa Tech',
            'Kumudzi Centre',
        ];

        return view('components.sales.letters.create', compact('recipients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_org' => ['nullable', 'string', 'max:255'],
            'recipient_email' => ['nullable', 'email', 'max:255'],
            'recipient_address' => ['required', 'string', 'max:1000'],
            'letter_date' => ['required', 'date'],
            'letter_number' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Draft,Sent,Signed'],
            'body' => ['required', 'string'],
            'closing' => ['nullable', 'string', 'max:255'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_title' => ['nullable', 'string', 'max:255'],
        ]);

        return redirect()
            ->route('sales.letters.index')
            ->with('success', 'Letter saved successfully (UI only).');
    }

    public function show(string $letter)
    {
        $row = $this->findLetterOrFail($letter);
        $document = $this->buildLetterDocument($row);

        return view('components.sales.letters.show', [
            'row' => $row,
            'letter' => $document['letter'],
        ]);
    }

    public function edit(string $letter)
    {
        $row = $this->findLetterOrFail($letter);
        $document = $this->buildLetterDocument($row);

        return view('components.sales.letters.edit', [
            'row' => $row,
            'letter' => $document['letter'],
        ]);
    }

    public function update(Request $request, string $letter)
    {
        $row = $this->findLetterOrFail($letter);

        $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_org' => ['nullable', 'string', 'max:255'],
            'recipient_email' => ['nullable', 'email', 'max:255'],
            'recipient_address' => ['required', 'string', 'max:1000'],
            'letter_date' => ['required', 'date'],
            'subject' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Draft,Sent,Signed'],
            'body' => ['required', 'string'],
            'closing' => ['nullable', 'string', 'max:255'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_title' => ['nullable', 'string', 'max:255'],
        ]);

        return redirect()
            ->route('sales.letters.show', $row['number'])
            ->with('success', 'Letter updated successfully (UI only).');
    }

    public function pdf(string $letter)
    {
        $row = $this->findLetterOrFail($letter);
        $document = $this->buildLetterDocument($row);
        $watermarkText = $this->watermarkForStatus($row['status'] ?? 'Draft');
        [$template, $templateColor] = $this->resolveTemplateSettings();
        $generatedAt = now();
        $company = $this->companyProfile();

        $pdf = Pdf::loadView('components.sales.letters.single-pdf', [
            'row' => $row,
            'letter' => $document['letter'],
            'company' => $company,
            'generatedAt' => $generatedAt,
            'watermarkText' => $watermarkText,
            'template' => $template,
            'templateColor' => $templateColor,
            'documentQr' => $this->qrCodeDataUri($this->letterQrPayload($row, $document['letter'], $company)),
            'stampDataUri' => $this->stampDataUri($generatedAt),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($row['number'] . '.pdf')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function downloadPdf(string $letter)
    {
        $row = $this->findLetterOrFail($letter);
        $document = $this->buildLetterDocument($row);
        $watermarkText = $this->watermarkForStatus($row['status'] ?? 'Draft');
        [$template, $templateColor] = $this->resolveTemplateSettings();
        $generatedAt = now();
        $company = $this->companyProfile();

        $pdf = Pdf::loadView('components.sales.letters.single-pdf', [
            'row' => $row,
            'letter' => $document['letter'],
            'company' => $company,
            'generatedAt' => $generatedAt,
            'watermarkText' => $watermarkText,
            'template' => $template,
            'templateColor' => $templateColor,
            'documentQr' => $this->qrCodeDataUri($this->letterQrPayload($row, $document['letter'], $company)),
            'stampDataUri' => $this->stampDataUri($generatedAt),
        ])->setPaper('a4', 'portrait');

        return $pdf->download($row['number'] . '.pdf')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function send(Request $request, string $letter)
    {
        $this->findLetterOrFail($letter);

        return response()->json([
            'success' => true,
            'message' => 'Letter sent successfully (UI only)',
        ]);
    }

    private function findLetterOrFail(string $letterNumber): array
    {
        $letter = $this->letterRows()->firstWhere('number', $letterNumber);

        abort_unless($letter, 404);

        return $letter;
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

    private function buildLetterDocument(array $row): array
    {
        $recipientName = $row['recipient'];
        $recipientOrg = '';

        if ($row['recipient'] === 'Copyright Society of Malawi (COSOMA)') {
            $recipientName = 'The Internal Procurement and Disposal Committee';
            $recipientOrg = $row['recipient'];
        }

        $recipientAddressLines = [
            'P.O. Box 30784',
            'Area 5, Off Paul Kagame Road',
            'Lilongwe 3, Malawi',
        ];

        $recipientLines = array_filter(array_merge(
            [$recipientName, $recipientOrg],
            $recipientAddressLines,
        ));

        $body = implode("\n\n", [
            'Terex Innovation Lab is pleased to submit this proposal in response to the call for bids for the development of a Membership Management Portal for the Copyright Society of Malawi (COSOMA).',
            'The rapid growth of the creative sector and digital media distribution has increased the demand for efficient registration, management, and verification of rights holders. We understand that COSOMA requires a secure, modern, and user-friendly digital platform that streamlines membership registration, the submission of creative works, payment of membership fees, communication between the institution and members, and record management.',
            'Our team proposes the development of a modern, secure, and scalable membership management platform that will support the full lifecycle of membership from online registration and verification to subscription payment processing and royalty summaries. The platform will reduce administrative workload, minimize manual processing errors, enhance data security, and improve member service delivery nationwide and internationally.',
        ]);

        $settings = SalesSettings::get();
        $profile = $settings['profile'] ?? [];

        $letter = [
            'recipient_name' => $recipientName,
            'recipient_org' => $recipientOrg,
            'recipient_address' => implode("\n", $recipientAddressLines),
            'recipient_lines' => $recipientLines,
            'subject' => $row['subject'],
            'date_issued' => $row['date'],
            'reference' => $row['number'],
            'body' => $body,
            'closing' => 'Sincerely,',
            'sender_name' => $profile['full_name'] ?? 'Richard Chilipa',
            'sender_title' => $profile['role'] ?? 'Chief Executive Officer',
        ];

        return compact('letter');
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
            'brand_top' => 'TEREX',
            'brand_middle' => 'INNOVATION',
            'brand_bottom' => 'LAB',
            'tagline' => "Innovating for Malawi's Digital Economy",
            'address_lines' => [
                'Smythe Road,',
                'Sunny Side,',
                'P/Bag 266',
                'Blantyre, Malawi',
            ],
            'email' => 'info@terexlab.com',
            'website' => 'www.terexlab.com',
            'phone' => '265 999852 222',
            'logo' => $logo,
            'signature' => $signature,
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
            $company['address_lines'] = SalesSettings::addressLines($companySettings['address']);
        }
        if (! empty($profile['full_name'])) {
            $company['sender_name'] = $profile['full_name'];
        }

        return $company;
    }

    private function letterQrPayload(array $row, array $letter, array $company): string
    {
        $companyName = (string) ($company['name'] ?? 'Terex Innovation Lab');
        $companyEmail = (string) ($company['email'] ?? '');
        $companyPhone = (string) ($company['phone'] ?? '');
        $contact = trim($companyEmail . ($companyEmail && $companyPhone ? ' | ' : '') . $companyPhone);

        return implode("\n", [
            'DOCUMENT: LETTER',
            'NUMBER: ' . ($letter['reference'] ?? $row['number']),
            'STATUS: ' . strtoupper((string) ($row['status'] ?? '')),
            'DATE ISSUED: ' . ($letter['date_issued'] ?? $row['date']),
            'RECIPIENT: ' . ($letter['recipient_name'] ?? $row['recipient'] ?? ''),
            'SUBJECT: ' . ($letter['subject'] ?? ''),
            'OWNER: ' . $companyName,
            'CONTACT: ' . ($contact !== '' ? $contact : 'N/A'),
            'VIEW: ' . route('sales.letters.show', $row['number']),
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

    private function watermarkForStatus(string $status): ?string
    {
        $status = strtoupper(trim($status));

        if ($status === 'SENT' || $status === 'SIGNED') {
            return 'SIGNED';
        }

        if ($status === 'DRAFT') {
            return 'DRAFT';
        }

        return null;
    }
}

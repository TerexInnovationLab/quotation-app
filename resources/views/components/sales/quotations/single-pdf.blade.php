<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Quotation {{ $row['number'] }}</title>
    @php
        $templateLabel = $template ?? 'Template 3';
        $templateNumber = (int) preg_replace('/\D+/', '', (string) $templateLabel);
        if ($templateNumber < 1 || $templateNumber > 6) {
            $templateNumber = 3;
        }
        $templateStyles = [
            1 => ['accent' => '#465fff', 'accentSoft' => '#eef2ff', 'accentDark' => '#1e293b'],
            2 => ['accent' => '#0f766e', 'accentSoft' => '#ecfdf5', 'accentDark' => '#115e59'],
            3 => ['accent' => '#e11d48', 'accentSoft' => '#fff1f2', 'accentDark' => '#9f1239'],
            4 => ['accent' => '#d97706', 'accentSoft' => '#fffbeb', 'accentDark' => '#92400e'],
            5 => ['accent' => '#0284c7', 'accentSoft' => '#f0f9ff', 'accentDark' => '#0c4a6e'],
            6 => ['accent' => '#475569', 'accentSoft' => '#f8fafc', 'accentDark' => '#1f2937'],
        ];
        $style = $templateStyles[$templateNumber];
        $normalizeHex = function (?string $value): ?string {
            $value = strtolower(trim((string) $value));
            if ($value === '') {
                return null;
            }
            $value = ltrim($value, '#');
            if (!preg_match('/^[0-9a-f]{6}$/', $value)) {
                return null;
            }
            return '#' . $value;
        };
        $toRgb = function (string $hex): array {
            $hex = ltrim($hex, '#');
            return [
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2)),
            ];
        };
        $mixColor = function (string $hex, string $target, float $ratio) use ($toRgb): string {
            [$r, $g, $b] = $toRgb($hex);
            [$tr, $tg, $tb] = $toRgb($target);
            $r = (int) round($r * (1 - $ratio) + $tr * $ratio);
            $g = (int) round($g * (1 - $ratio) + $tg * $ratio);
            $b = (int) round($b * (1 - $ratio) + $tb * $ratio);
            return sprintf('#%02x%02x%02x', $r, $g, $b);
        };
        $accentOverride = $normalizeHex($templateColor ?? '');
        if ($accentOverride) {
            $style = [
                'accent' => $accentOverride,
                'accentSoft' => $mixColor($accentOverride, '#ffffff', 0.88),
                'accentDark' => $mixColor($accentOverride, '#000000', 0.35),
            ];
        }
    @endphp
    <style>
        @page {
            margin: 14px 16px 34px;
        }

        :root {
            --accent: {{ $style['accent'] }};
            --accent-soft: {{ $style['accentSoft'] }};
            --accent-dark: {{ $style['accentDark'] }};
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.45;
            position: relative;
            padding-bottom: 34px;
        }

        .top-accent {
            height: 7px;
            background: var(--accent);
            margin-bottom: 16px;
        }

        .watermark {
            position: fixed;
            width: 100%;
            text-align: center;
            font-size: 56px;
            letter-spacing: 2px;
            font-weight: 700;
            color: var(--accent-dark);
            opacity: 0.06;
            transform: rotate(-25deg);
            z-index: -1;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .watermark-top {
            top: 18%;
            left: 0;
        }

        .watermark-middle {
            top: 45%;
            left: 0;
        }

        .watermark-bottom {
            top: 72%;
            left: 0;
        }

        .header-table,
        .bill-table,
        .items-table,
        .totals-table,
        .notes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            page-break-inside: avoid;
        }

        .header-table td {
            vertical-align: top;
        }

        .brand-wrap {
            width: 58%;
        }

        .meta-wrap {
            width: 42%;
            text-align: right;
        }

        .logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
            display: block;
            margin-bottom: 8px;
        }

        .logo-fallback {
            width: 56px;
            height: 56px;
            line-height: 56px;
            text-align: center;
            font-size: 22px;
            color: #ffffff;
            background: var(--accent);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .company-tagline {
            margin: 2px 0 0;
            color: #475569;
            font-size: 11px;
        }

        .company-details {
            margin-top: 10px;
            color: #334155;
            font-size: 11px;
        }

        .doc-title {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 10px;
            border: 0;
            background: var(--accent-soft);
            color: #334155;
            font-size: 11px;
            font-weight: 700;
        }

        .meta {
            margin-top: 10px;
        }

        .meta .row {
            margin-top: 3px;
            color: #334155;
            font-size: 11px;
        }

        .meta .label {
            color: #64748b;
            width: 92px;
            display: inline-block;
            text-align: left;
        }

        .subject {
            margin-top: 12px;
            padding: 10px 12px;
            background: var(--accent-soft);
            border: 0;
            font-size: 11px;
        }

        .section-title {
            color: #64748b;
            font-size: 10px;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .bill-table {
            margin-top: 14px;
        }

        .bill-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px 12px;
            border: 0;
            background: #ffffff;
        }

        .bill-name {
            font-size: 14px;
            font-weight: 700;
            margin: 2px 0 6px;
        }

        .bill-line {
            color: #334155;
            font-size: 11px;
            margin: 2px 0;
        }

        .items-table {
            margin-top: 14px;
        }

        .items-table th,
        .items-table td {
            border: 0;
            padding: 7px 9px;
        }

        .items-table th {
            background: var(--accent-soft);
            color: #334155;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            text-align: left;
        }

        .items-table .right {
            text-align: right;
        }

        .item-name {
            font-weight: 700;
            color: #0f172a;
        }

        .item-note {
            margin-top: 2px;
            font-size: 10px;
            color: #64748b;
        }

        .totals-table {
            width: 42%;
            margin-left: auto;
            margin-top: 10px;
        }

        .totals-table td {
            padding: 5px 2px;
            border: 0;
            font-size: 11px;
        }

        .totals-table .value {
            text-align: right;
            font-weight: 700;
        }

        .totals-table .grand td {
            border-top: 0;
            font-size: 13px;
            font-weight: 700;
            padding-top: 7px;
        }

        .notes-table {
            margin-top: 12px;
        }

        .notes-table td {
            width: 50%;
            border: 0;
            padding: 10px;
            vertical-align: top;
        }

        .notes-content {
            font-size: 11px;
            color: #334155;
            white-space: pre-line;
        }

        .security-bottom {
            margin-top: 8px;
            width: 100%;
            page-break-inside: avoid;
        }

        .qr-panel,
        .signature-panel {
            margin-top: 0;
            width: 160px;
            height: 170px;
            box-sizing: border-box;
            border: 0;
            border-radius: 10px;
            padding: 10px;
            background: #ffffff;
            display: inline-block;
            text-align: center;
            overflow: hidden;
        }

        .seal-image {
            width: 108px;
            height: 108px;
            object-fit: contain;
            opacity: 0.88;
        }

        .qr-image {
            width: 120px;
            height: 120px;
            border: 0;
            padding: 4px;
            background: #ffffff;
        }

        .qr-fallback {
            display: inline-block;
            width: 108px;
            height: 108px;
            line-height: 108px;
            text-align: center;
            border: 0;
            color: #94a3b8;
            font-size: 8px;
            text-transform: uppercase;
        }

        .auth-note {
            margin-top: 5px;
            color: #64748b;
            font-size: 8px;
            word-break: break-all;
        }

        .signature-image {
            width: 145px;
            height: 50px;
            object-fit: contain;
            display: block;
            margin: 2px auto 0;
        }

        .signature-fallback {
            width: 132px;
            margin: 2px auto 0;
            text-align: center;
            font-size: 10px;
            font-style: normal;
            font-weight: 700;
            color: #0f172a;
        }

        .signature-line {
            border-top: 0;
            margin: 2px auto 4px;
            width: 132px;
        }

        .signature-meta {
            text-align: center;
            color: #0f172a;
            font-size: 10px;
            font-weight: 700;
            margin-top: 2px;
        }

        .signature-panel .section-title {
            margin-bottom: 4px;
            font-size: 10px;
            font-weight: 700;
            color: #0f172a;
        }

        .signature-panel {
            font-size: 10px;
            font-weight: 700;
            color: #0f172a;
        }

        .security-section {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-top: 0;
            margin-top: 16px;
            page-break-inside: avoid;
        }

        .security-section td {
            vertical-align: top;
            padding-top: 12px;
            page-break-inside: avoid;
        }

        .security-cell-left {
            text-align: left;
        }

        .security-cell-center {
            text-align: center;
        }

        .security-cell-right {
            text-align: right;
        }

        .stamp-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stamp-image {
            width: 155px;
            height: 155px;
            object-fit: contain;
            display: block;
        }

        .qr-box {
            border: 0;
            border-radius: 10px;
            padding: 8px;
            background: white;
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 10px;
            text-align: center;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -18px;
            border-top: 0;
            padding-top: 7px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }

        .footer-link {
            display: inline-flex;
            align-items: flex-start;
            gap: 6px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
            position: relative;
            top: 6px;
        }

        .footer-text {
            position: relative;
            top: -10px;
        }

        .side-accent {
            position: fixed;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: var(--accent);
            opacity: 0;
        }

        .header-card {
            border: 1px solid transparent;
            border-radius: 14px;
            padding: 12px;
            background: #ffffff;
            page-break-inside: avoid;
        }

        .template-2 .side-accent {
            opacity: 1;
        }

        .template-2 .subject {
            border-left: 4px solid var(--accent);
        }

        .template-3 .header-card {
            background: var(--accent);
            border-color: var(--accent);
        }

        .template-3 .header-card * {
            color: #ffffff;
        }

        .template-3 .header-card .label,
        .template-3 .company-tagline {
            color: var(--accent-soft);
        }

        .template-3 .status-badge {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .template-4 .top-accent {
            height: 3px;
        }

        .template-4 .watermark {
            opacity: 0.02;
        }

        .template-4 .items-table th {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
        }

        .template-4 .subject {
            border: 1px dashed #e2e8f0;
            background: #ffffff;
        }

        .template-5 .items-table th,
        .template-5 .items-table td {
            border: 1px solid #e2e8f0;
        }

        .template-5 .items-table tbody tr:nth-child(even) td {
            background: var(--accent-soft);
        }

        .template-6 {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px;
        }

        .template-6 .top-accent {
            height: 10px;
        }

        .template-6 .subject {
            background: var(--accent-soft);
        }
    </style>
</head>

<body class="template-{{ $templateNumber }}">
    <div class="side-accent"></div>
    <div class="watermark watermark-top">{{ strtoupper($company['name'] ?? ($watermarkText ?? 'QUOTATION')) }}</div>
    <div class="watermark watermark-middle">{{ strtoupper($company['name'] ?? ($watermarkText ?? 'QUOTATION')) }}</div>
    <div class="watermark watermark-bottom">{{ strtoupper($company['name'] ?? ($watermarkText ?? 'QUOTATION')) }}</div>
    <div class="top-accent"></div>

    <div class="header-card">
    <table class="header-table">
        <tr>
            <td class="brand-wrap">
                @php($defaultLogoPath = public_path('images/terex_innovation_lab_logo.jpg'))
                @if(!empty($company['logo']))
                    <img src="{{ $company['logo'] }}" alt="Company logo" class="logo">
                @elseif(file_exists($defaultLogoPath))
                    <img src="{{ $defaultLogoPath }}" alt="Terex Innovation Lab logo" class="logo">
                @else
                    <div class="logo-fallback">A</div>
                @endif
                <h1 class="company-name">{{ $company['name'] }}</h1>
                <p class="company-tagline">{{ $company['tagline'] }}</p>
                <div class="company-details">
                    {{ $company['address'] }}<br>
                    {{ $company['email'] }} | {{ $company['phone'] }}
                </div>
            </td>
            <td class="meta-wrap">
                <h2 class="doc-title">QUOTATION</h2>
                <span class="status-badge">{{ strtoupper($row['status']) }}</span>
                <div class="meta">
                    <div class="row"><span class="label">Quotation No:</span> {{ $quotation['quotation_number'] }}</div>
                    <div class="row"><span class="label">Issue Date:</span> {{ $quotation['quotation_date'] }}</div>
                    <div class="row"><span class="label">Valid Until:</span> {{ $quotation['expiry_date'] }}</div>
                    <div class="row"><span class="label">Generated:</span> {{ $generatedAt->format('Y-m-d H:i') }}</div>
                </div>
            </td>
        </tr>
    </table>
    </div>

    <div class="subject">
        <strong>Subject:</strong> {{ $quotation['subject'] }}
    </div>

    <table class="bill-table">
        <tr>
            <td>
                <div class="section-title">Prepared For</div>
                <div class="bill-name">{{ $quotation['customer_name'] }}</div>
                <div class="bill-line">Valid until {{ $quotation['expiry_date'] }}</div>
                <div class="bill-line">Currency: {{ $quotation['currency'] }}</div>
            </td>
            <td>
                <div class="section-title">Prepared By</div>
                <div class="bill-name">{{ $company['name'] }}</div>
                <div class="bill-line">{{ $company['address'] }}</div>
                <div class="bill-line">{{ $company['email'] }}</div>
                <div class="bill-line">{{ $company['phone'] }}</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 46%;">Item</th>
                <th style="width: 12%;" class="right">Qty</th>
                <th style="width: 20%;" class="right">Rate</th>
                <th style="width: 22%;" class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item['name'] }}</div>
                        @if(!empty($item['note']))
                            <div class="item-note">{{ $item['note'] }}</div>
                        @endif
                    </td>
                    <td class="right">{{ number_format($item['qty'], 2) }}</td>
                    <td class="right">{{ number_format($item['rate'], 0) }}</td>
                    <td class="right">{{ number_format($item['line_total'], 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Sub Total</td>
            <td class="value">{{ $quotation['currency'] }} {{ number_format($quotation['sub_total'], 0) }}</td>
        </tr>
        <tr>
            <td>VAT ({{ number_format($quotation['vat_rate'], 1) }}%)</td>
            <td class="value">{{ $quotation['currency'] }} {{ number_format($quotation['vat_amount'], 0) }}</td>
        </tr>
        <tr class="grand">
            <td>Total</td>
            <td class="value">{{ $quotation['currency'] }} {{ number_format($quotation['grand_total'], 0) }}</td>
        </tr>
    </table>

    <table class="notes-table">
        <tr>
            <td>
                <div class="section-title">Notes</div>
                <div class="notes-content">{{ $quotation['notes'] }}</div>
            </td>
            <td>
                <div class="section-title">Terms</div>
                <div class="notes-content">{{ $quotation['terms'] }}</div>
            </td>
        </tr>
    </table>

    <div class="security-bottom">
        <table class="security-section">
            <tr>
                <td class="security-cell-left">
                    <div class="stamp-container">
                        @php($stampImagePath = public_path('images/terex_stamp.jpg'))
                        @if(!empty($stampDataUri ?? null))
                            <img src="{{ $stampDataUri }}" alt="Approved stamp" class="stamp-image">
                        @elseif(!empty($company['seal']))
                            <img src="{{ $company['seal'] }}" alt="Approved stamp" class="stamp-image">
                        @elseif(file_exists($stampImagePath))
                            <img src="{{ $stampImagePath }}" alt="Approved stamp" class="stamp-image">
                        @else
                            <div class="signature-meta">Stamp image unavailable</div>
                        @endif
                    </div>
                </td>
                <td class="security-cell-center">
                    <div class="qr-panel">
                        @if(!empty($documentQr))
                            <img src="{{ $documentQr }}" alt="Quotation QR code" class="qr-image">
                        @else
                            <div class="qr-fallback">QR unavailable</div>
                        @endif
                    </div>
                </td>
               <td class="security-cell-right">
                    <div class="signature-panel">
                        <div class="section-title">Digital Signature</div>
                        @if(!empty($company['ceo_signature']))
                            <img src="{{ $company['ceo_signature'] }}" alt="CEO signature" class="signature-image">
                        @else
                            <div class="signature-fallback">{{ $company['ceo_name'] }}</div>
                        @endif
                        <div class="signature-line"></div>
                        <div class="signature-meta">Signed by {{ $company['ceo_name'] }}</div>
                        <div class="signature-meta">{{ $company['ceo_title'] ?? 'CEO' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <a class="footer-link" href="https://terexlab.com">
                <img src="{{ public_path('images/terex_innovation_lab_logo.jpg') }}" alt="Terex Innovation Lab logo" class="footer-logo">
                <span class="footer-text">Powered By Terex Innovation Lab</span>
            </a>
        </div>
    </div>
</body>

</html>

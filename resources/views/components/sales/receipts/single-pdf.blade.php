<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $row['number'] }}</title>
    @php
        $templateLabel = $template ?? 'Template 4';
        $templateNumber = (int) preg_replace('/\D+/', '', (string) $templateLabel);
        if ($templateNumber < 1 || $templateNumber > 6) {
            $templateNumber = 4;
        }
        $templateStyles = [
            1 => ['accent' => '#465fff', 'accentSoft' => '#eef2ff', 'accentDark' => '#1e293b'],
            2 => ['accent' => '#0f766e', 'accentSoft' => '#ecfdf5', 'accentDark' => '#115e59'],
            3 => ['accent' => '#e11d48', 'accentSoft' => '#fff1f2', 'accentDark' => '#9f1239'],
            4 => ['accent' => '#1e3a8a', 'accentSoft' => '#e0e7ff', 'accentDark' => '#1e40af'],
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
            margin: 16px 18px 28px;
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
            line-height: 1.5;
        }

        .top-accent {
            height: 6px;
            background: var(--accent);
            margin-bottom: 14px;
        }

        .header-table,
        .info-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
            display: block;
            margin-bottom: 8px;
        }

        .logo-fallback {
            width: 54px;
            height: 54px;
            line-height: 54px;
            text-align: center;
            font-size: 20px;
            color: #ffffff;
            background: var(--accent);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 20px;
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
            margin-top: 8px;
            color: #334155;
            font-size: 11px;
        }

        .receipt-title {
            margin: 0;
            font-size: 26px;
            letter-spacing: 2px;
            font-weight: 700;
            text-align: right;
            color: var(--accent-dark);
        }

        .receipt-meta {
            margin-top: 8px;
            text-align: right;
            font-size: 11px;
            color: #334155;
        }

        .receipt-meta .label {
            color: #64748b;
            display: inline-block;
            width: 90px;
            text-align: left;
        }

        .section-title {
            margin: 18px 0 6px;
            color: var(--accent-dark);
            font-size: 10px;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
            padding: 8px 10px;
        }

        .info-line {
            margin-top: 3px;
            font-size: 11px;
            color: #334155;
        }

        .summary-table {
            margin-top: 10px;
        }

        .summary-table th,
        .summary-table td {
            border: 0;
            padding: 7px 8px;
            font-size: 11px;
        }

        .summary-table th {
            background: var(--accent-soft);
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #334155;
        }

        .summary-table .right {
            text-align: right;
        }

        .total-row td {
            font-weight: 700;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 9px;
        }

        .notes {
            margin-top: 14px;
            font-size: 11px;
            color: #334155;
            white-space: pre-line;
        }

        .signature-block {
            margin-top: 18px;
            text-align: left;
        }

        .signature-image {
            width: 150px;
            height: 48px;
            object-fit: contain;
            display: block;
            margin-top: 6px;
        }

        .signature-name {
            font-weight: 700;
            margin-top: 6px;
        }

        .signature-title {
            color: #475569;
            font-size: 11px;
            margin-top: 2px;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -12px;
            border-top: 0;
            padding-top: 6px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
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
            border-radius: 16px;
            padding: 12px;
            background: #ffffff;
        }

        .template-2 .side-accent {
            opacity: 1;
        }

        .template-2 .info-table {
            background: var(--accent-soft);
            border-radius: 12px;
        }

        .template-3 .header-card {
            background: var(--accent);
            border-color: var(--accent);
        }

        .template-3 .header-card * {
            color: #ffffff;
        }

        .template-3 .receipt-meta .label {
            color: var(--accent-soft);
        }

        .template-4 .top-accent {
            height: 3px;
        }

        .template-4 .summary-table th {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
        }

        .template-4 .summary-table td {
            border-bottom: 1px dashed #e2e8f0;
        }

        .template-5 .summary-table th,
        .template-5 .summary-table td {
            border: 1px solid #e2e8f0;
        }

        .template-6 {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px;
        }

        .template-6 .top-accent {
            height: 10px;
        }
    </style>
</head>
<body class="template-{{ $templateNumber }}">
    <div class="side-accent"></div>
    <div class="top-accent"></div>

    <div class="header-card">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    @if(!empty($company['logo']))
                        <img src="{{ $company['logo'] }}" alt="Company logo" class="logo">
                    @else
                        <div class="logo-fallback">R</div>
                    @endif
                    <h1 class="company-name">{{ $company['name'] }}</h1>
                    <p class="company-tagline">{{ $company['tagline'] }}</p>
                    <div class="company-details">
                        {{ $company['address'] }}<br>
                        {{ $company['email'] }} | {{ $company['phone'] }}
                    </div>
                </td>
                <td style="width: 40%;">
                    <h2 class="receipt-title">RECEIPT</h2>
                    <div class="receipt-meta">
                        <div><span class="label">Receipt No:</span> {{ $receipt['receipt_number'] }}</div>
                        <div><span class="label">Date Issued:</span> {{ $receipt['receipt_date'] }}</div>
                        <div><span class="label">Status:</span> {{ $receipt['status'] }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Received From</div>
    <table class="info-table">
        <tr>
            <td>
                <div class="info-line"><strong>{{ $receipt['customer_name'] }}</strong></div>
                <div class="info-line">Reference: {{ $receipt['reference'] ?: '-' }}</div>
            </td>
            <td>
                <div class="info-line">Payment Method: {{ $receipt['method'] }}</div>
                <div class="info-line">Amount Received: {{ $receipt['currency'] }} {{ number_format($receipt['amount'], 0) }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Payment Summary</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Payment received for {{ $receipt['reference'] ?: 'services rendered' }}</td>
                <td class="right">{{ $receipt['currency'] }} {{ number_format($receipt['amount'], 0) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Received</td>
                <td class="right">{{ $receipt['currency'] }} {{ number_format($receipt['amount'], 0) }}</td>
            </tr>
        </tbody>
    </table>

    @if(!empty($receipt['notes']))
        <div class="notes"><strong>Notes:</strong> {{ $receipt['notes'] }}</div>
    @endif

    <div class="signature-block">
        @if(!empty($company['signature']))
            <img src="{{ $company['signature'] }}" alt="Signature" class="signature-image">
        @endif
        <div class="signature-name">{{ $company['signatory_name'] }}</div>
        <div class="signature-title">{{ $company['signatory_title'] }}</div>
    </div>

    <div class="footer">
        Generated on {{ $generatedAt->format('Y-m-d H:i') }}
    </div>
</body>
</html>

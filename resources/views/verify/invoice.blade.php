<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice Verification {{ $row['number'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #465fff;
            --ink: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --bg: #f1f5f9;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #0284c7;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        .page {
            min-height: 100vh;
            padding: 32px 16px;
        }

        .card {
            max-width: 920px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 40px rgba(15, 23, 42, 0.08);
        }

        .top-accent {
            height: 6px;
            background: var(--primary);
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .logo-fallback {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: var(--primary);
            color: #ffffff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 20px;
        }

        .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.25em;
            font-size: 10px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .brand h1 {
            margin: 0;
            font-size: 20px;
        }

        .brand p {
            margin: 2px 0 0;
            font-size: 12px;
            color: var(--muted);
        }

        .scan {
            text-align: right;
            min-width: 220px;
        }

        .scan-title {
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 11px;
            color: var(--muted);
        }

        .scan-status {
            margin-top: 4px;
            font-size: 22px;
            font-weight: 700;
            color: var(--success);
        }

        .scan-meta {
            margin-top: 4px;
            font-size: 12px;
            color: var(--muted);
        }

        .content {
            padding: 20px 24px 26px;
        }

        .grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .panel {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            background: #f8fafc;
        }

        .panel-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 12px;
        }

        .row:last-child {
            border-bottom: 0;
        }

        .row span {
            color: var(--muted);
            min-width: 90px;
        }

        .row strong {
            font-weight: 600;
            text-align: right;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid #cbd5f5;
            background: #eef2ff;
            color: #4338ca;
        }

        .status-success {
            background: #ecfdf3;
            border-color: #bbf7d0;
            color: #166534;
        }

        .status-warning {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #9a3412;
        }

        .status-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .status-info {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .notice {
            margin-top: 16px;
            border-radius: 14px;
            border: 1px solid #0f172a;
            background: #0f172a;
            color: #e2e8f0;
            padding: 12px 16px;
            font-size: 12px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .notice strong {
            color: #ffffff;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            word-break: break-all;
        }
    </style>
</head>
<body>
@php
    $status = strtoupper((string) ($row['status'] ?? 'UNKNOWN'));
    $statusClass = 'status-info';
    if ($status === 'PAID') {
        $statusClass = 'status-success';
    } elseif ($status === 'UNPAID') {
        $statusClass = 'status-warning';
    } elseif ($status === 'OVERDUE') {
        $statusClass = 'status-danger';
    }
    $contactParts = array_filter([$company['email'] ?? null, $company['phone'] ?? null]);
    $contact = implode(' | ', $contactParts);
    $defaultLogoPath = public_path('images/terex_innovation_lab_logo.jpg');
    $defaultLogoUrl = file_exists($defaultLogoPath) ? asset('images/terex_innovation_lab_logo.jpg') : null;
    $logoUrl = $company['logo'] ?: $defaultLogoUrl;
@endphp

<div class="page">
    <div class="card">
        <div class="top-accent"></div>
        <div class="header">
            <div class="brand">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="Company logo" class="logo">
                @else
                    <div class="logo-fallback">A</div>
                @endif
                <div>
                    <div class="eyebrow">TEREX DOCUMENT VERIFICATION</div>
                    <h1>{{ $company['name'] }}</h1>
                    <p>{{ $company['tagline'] ?? '' }}</p>
                </div>
            </div>
            <div class="scan">
                <div class="scan-title">Scan Result</div>
                <div class="scan-status">Verified</div>
                <div class="scan-meta">Scanned {{ $verifiedAt->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <div class="content">
            <div class="grid">
                <div class="panel">
                    <div class="panel-title">Document Details</div>
                    <div class="row"><span>Type</span><strong>Invoice</strong></div>
                    <div class="row"><span>Document #</span><strong>{{ $row['number'] }}</strong></div>
                    <div class="row">
                        <span>Status</span>
                        <strong><span class="status-pill {{ $statusClass }}">{{ $status }}</span></strong>
                    </div>
                    <div class="row"><span>Issue Date</span><strong>{{ $invoice['invoice_date'] }}</strong></div>
                    <div class="row"><span>Due Date</span><strong>{{ $invoice['due_date'] }}</strong></div>
                    <div class="row"><span>Customer</span><strong>{{ $invoice['customer_name'] }}</strong></div>
                    <div class="row"><span>Total</span><strong>{{ $invoice['currency'] }} {{ number_format($invoice['grand_total'], 0) }}</strong></div>
                </div>

                <div class="panel">
                    <div class="panel-title">Ownership &amp; Security</div>
                    <div class="row"><span>Owner</span><strong>{{ $company['name'] }}</strong></div>
                    <div class="row"><span>Issued By</span><strong>{{ $company['name'] }}</strong></div>
                    <div class="row"><span>Contact</span><strong>{{ $contact !== '' ? $contact : 'N/A' }}</strong></div>
                    <div class="row"><span>Verification</span><strong>Verified by {{ $company['name'] }}</strong></div>
                    <div class="row"><span>Verify URL</span><strong class="mono">{{ $verifyUrl }}</strong></div>
                </div>
            </div>

            <div class="notice">
                <strong>Security note:</strong>
                This QR scan confirms the invoice details and ownership match Terex Innovation Lab records. If any detail looks suspicious, contact the issuer immediately.
            </div>
        </div>
    </div>
</div>
</body>
</html>

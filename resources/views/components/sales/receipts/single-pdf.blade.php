<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $row['number'] }}</title>
    <style>
        @page {
            margin: 16px 18px 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.5;
        }

        .top-accent {
            height: 6px;
            background: #1e3a8a;
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
            background: #1e3a8a;
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
            color: #64748b;
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
            background: #f1f5f9;
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
    </style>
</head>
<body>
    <div class="top-accent"></div>

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

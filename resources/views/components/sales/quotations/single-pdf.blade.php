<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $row['number'] }}</title>
    <style>
        @page {
            margin: 28px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.5;
            position: relative;
        }

        .top-accent {
            height: 8px;
            background: #465fff;
            margin-bottom: 18px;
        }

        .watermark {
            position: fixed;
            top: 41%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 76px;
            letter-spacing: 4px;
            font-weight: 700;
            color: #64748b;
            opacity: 0.08;
            transform: rotate(-25deg);
            z-index: -1;
        }

        .header-table,
        .bill-table,
        .items-table,
        .totals-table,
        .notes-table {
            width: 100%;
            border-collapse: collapse;
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
            width: 58px;
            height: 58px;
            object-fit: contain;
            display: block;
            margin-bottom: 8px;
        }

        .logo-fallback {
            width: 58px;
            height: 58px;
            line-height: 58px;
            text-align: center;
            font-size: 22px;
            color: #ffffff;
            background: #465fff;
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
            margin-top: 7px;
            padding: 3px 10px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #334155;
            font-size: 11px;
            font-weight: 700;
        }

        .meta {
            margin-top: 12px;
        }

        .meta .row {
            margin-top: 4px;
            color: #334155;
            font-size: 11px;
        }

        .meta .label {
            color: #64748b;
            width: 102px;
            display: inline-block;
            text-align: left;
        }

        .subject {
            margin-top: 14px;
            padding: 10px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
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
            margin-top: 20px;
        }

        .bill-table td {
            width: 50%;
            vertical-align: top;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
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
            margin-top: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #dbe3ee;
            padding: 9px 10px;
        }

        .items-table th {
            background: #f8fafc;
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
            margin-top: 14px;
        }

        .totals-table td {
            padding: 6px 2px;
            border: 0;
            font-size: 11px;
        }

        .totals-table .value {
            text-align: right;
            font-weight: 700;
        }

        .totals-table .grand td {
            border-top: 1px solid #cbd5e1;
            font-size: 13px;
            font-weight: 700;
            padding-top: 8px;
        }

        .notes-table {
            margin-top: 18px;
        }

        .notes-table td {
            width: 50%;
            border: 1px solid #e2e8f0;
            padding: 12px;
            vertical-align: top;
        }

        .notes-content {
            font-size: 11px;
            color: #334155;
            white-space: pre-line;
        }

        .auth-table {
            margin-top: 16px;
        }

        .auth-table td {
            width: 50%;
            border: 0;
            padding: 0;
            vertical-align: top;
        }

        .stamp-cell {
            text-align: left;
        }

        .seal-image {
            width: 130px;
            height: 130px;
            object-fit: contain;
            opacity: 0.88;
        }

        .seal-fallback {
            width: 126px;
            height: 126px;
            border: 3px solid #dc2626;
            color: #dc2626;
            border-radius: 50%;
            text-align: center;
            transform: rotate(-13deg);
            box-sizing: border-box;
            padding: 30px 8px 8px;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.3;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .qr-cell {
            text-align: right;
        }

        .qr-image {
            width: 112px;
            height: 112px;
            border: 1px solid #cbd5e1;
            padding: 4px;
            background: #ffffff;
        }

        .qr-fallback {
            display: inline-block;
            width: 112px;
            height: 112px;
            line-height: 112px;
            text-align: center;
            border: 1px dashed #cbd5e1;
            color: #94a3b8;
            font-size: 10px;
            text-transform: uppercase;
        }

        .auth-note {
            margin-top: 6px;
            color: #64748b;
            font-size: 10px;
        }

        .footer {
            margin-top: 18px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $watermarkText ?? 'QUOTATION' }}</div>
    <div class="top-accent"></div>

    <table class="header-table">
        <tr>
            <td class="brand-wrap">
                @if(!empty($company['logo']))
                    <img src="{{ $company['logo'] }}" alt="Company logo" class="logo">
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

    <table class="auth-table">
        <tr>
            <td class="stamp-cell">
                <div class="section-title">Seal / Stamp</div>
                @if(!empty($company['seal']))
                    <img src="{{ $company['seal'] }}" alt="Company seal" class="seal-image">
                @else
                    <div class="seal-fallback">
                        {{ $company['name'] }}<br>Authorized
                    </div>
                @endif
                <div class="auth-note">Digitally approved by {{ $company['name'] }}</div>
            </td>
            <td class="qr-cell">
                <div class="section-title">QR Verification</div>
                @if(!empty($documentQr))
                    <img src="{{ $documentQr }}" alt="Quotation QR code" class="qr-image">
                @else
                    <div class="qr-fallback">QR unavailable</div>
                @endif
                <div class="auth-note">{{ $documentUrl ?? '' }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        This quotation is generated digitally by {{ $company['name'] }}.
    </div>
</body>
</html>

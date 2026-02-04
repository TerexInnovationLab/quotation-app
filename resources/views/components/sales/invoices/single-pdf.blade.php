<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $row['number'] }}</title>
    <style>
        @page {
            margin: 28px 30px 64px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.5;
            position: relative;
            padding-bottom: 62px;
        }

        .top-accent {
            height: 8px;
            background: #465fff;
            margin-bottom: 18px;
        }

        .watermark {
            position: fixed;
            top: 43%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 56px;
            letter-spacing: 2px;
            font-weight: 700;
            color: #1e293b;
            opacity: 0.06;
            transform: rotate(-25deg);
            z-index: -1;
            text-transform: uppercase;
            white-space: nowrap;
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

        .invoice-meta-wrap {
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

        .invoice-title {
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

        .invoice-meta {
            margin-top: 12px;
        }

        .invoice-meta .row {
            margin-top: 4px;
            color: #334155;
            font-size: 11px;
        }

        .invoice-meta .label {
            color: #64748b;
            width: 92px;
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

        .verification-stack {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            gap: 8px;
            page-break-inside: avoid;
        }

        .company-stamp {
            margin-top: 12px;
            text-align: left;
        }

        .signature-bottom {
            margin-top: 18px;
            page-break-inside: avoid;
        }

        .qr-panel,
        .stamp-panel {
            display: inline-block;
            width: 104px;
            box-sizing: border-box;
            border: 1px solid #dbe3ee;
            border-radius: 10px;
            padding: 6px;
            background: #ffffff;
        }

        .stamp-panel {
            background: #fff7f7;
            border-color: #fecaca;
        }

        .signature-panel {
            margin-top: 8px;
            width: 170px;
            box-sizing: border-box;
            border: 1px solid #dbe3ee;
            border-radius: 10px;
            padding: 6px 8px;
            background: #ffffff;
        }

        .seal-image {
            width: 84px;
            height: 84px;
            object-fit: contain;
            opacity: 0.88;
        }

        .qr-image {
            width: 84px;
            height: 84px;
            border: 1px solid #cbd5e1;
            padding: 4px;
            background: #ffffff;
        }

        .qr-fallback {
            display: inline-block;
            width: 84px;
            height: 84px;
            line-height: 84px;
            text-align: center;
            border: 1px dashed #cbd5e1;
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
            width: 140px;
            height: 40px;
            object-fit: contain;
            display: block;
            margin: 2px auto 0;
        }

        .signature-fallback {
            width: 140px;
            margin: 2px auto 0;
            text-align: center;
            font-size: 18px;
            font-style: italic;
            color: #334155;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            margin: 2px auto 4px;
            width: 140px;
        }

        .signature-meta {
            text-align: center;
            color: #64748b;
            font-size: 8px;
            margin-top: 2px;
        }

        .stamp-realistic {
            width: 88px;
            height: 88px;
            margin: 0 auto;
            position: relative;
            box-sizing: border-box;
            border: 2px solid #dc2626;
            border-radius: 50%;
            background: #ffffff;
            color: #dc2626;
            overflow: hidden;
            transform: rotate(-12deg);
        }

        .stamp-realistic .stamp-edge {
            position: absolute;
            top: -3px;
            right: -3px;
            bottom: -3px;
            left: -3px;
            border: 3px dashed rgba(220, 38, 38, 0.9);
            border-radius: 50%;
            z-index: 0;
        }

        .stamp-realistic .stamp-core {
            position: absolute;
            top: 7px;
            right: 7px;
            bottom: 7px;
            left: 7px;
            border: 2px solid rgba(220, 38, 38, 0.8);
            border-radius: 50%;
            z-index: 0;
        }

        .stamp-realistic .stamp-top,
        .stamp-realistic .stamp-bottom {
            position: absolute;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            letter-spacing: 0.9px;
            font-weight: 800;
            z-index: 2;
        }

        .stamp-realistic .stamp-top {
            top: 12px;
        }

        .stamp-realistic .stamp-bottom {
            bottom: 11px;
        }

        .stamp-realistic .stamp-stars-top,
        .stamp-realistic .stamp-stars-bottom {
            position: absolute;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 7px;
            letter-spacing: 1px;
            font-weight: 700;
            z-index: 2;
        }

        .stamp-realistic .stamp-stars-top {
            top: 22px;
        }

        .stamp-realistic .stamp-stars-bottom {
            bottom: 22px;
        }

        .stamp-realistic .stamp-band {
            position: absolute;
            left: -8px;
            right: -8px;
            top: 35px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            font-size: 11px;
            letter-spacing: 1.2px;
            font-weight: 900;
            color: #dc2626;
            background: #ffffff;
            border-top: 2px solid #dc2626;
            border-bottom: 2px solid #dc2626;
            z-index: 3;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -44px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ strtoupper($company['name'] ?? ($watermarkText ?? 'INVOICE')) }}</div>
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
                <div class="company-stamp">
                    <div class="stamp-panel">
                        <div class="section-title">Official Stamp</div>
                        @if(!empty($company['seal']))
                            <img src="{{ $company['seal'] }}" alt="Company seal" class="seal-image">
                        @else
                            <div class="stamp-realistic">
                                <div class="stamp-edge"></div>
                                <div class="stamp-core"></div>
                                <div class="stamp-top">APPROVED</div>
                                <div class="stamp-stars-top">* * *</div>
                                <div class="stamp-band">APPROVED</div>
                                <div class="stamp-stars-bottom">* * *</div>
                                <div class="stamp-bottom">APPROVED</div>
                            </div>
                        @endif
                        <div class="auth-note">Digitally approved by {{ $company['name'] }}</div>
                    </div>
                </div>
            </td>
            <td class="invoice-meta-wrap">
                <h2 class="invoice-title">INVOICE</h2>
                <span class="status-badge">{{ strtoupper($row['status']) }}</span>
                <div class="invoice-meta">
                    <div class="row"><span class="label">Invoice No:</span> {{ $invoice['invoice_number'] }}</div>
                    <div class="row"><span class="label">Invoice Date:</span> {{ $invoice['invoice_date'] }}</div>
                    <div class="row"><span class="label">Due Date:</span> {{ $invoice['due_date'] }}</div>
                    <div class="row"><span class="label">Generated:</span> {{ $generatedAt->format('Y-m-d H:i') }}</div>
                </div>
                <div class="verification-stack">
                    <div class="qr-panel">
                        <div class="section-title">QR Verification</div>
                        @if(!empty($documentQr))
                            <img src="{{ $documentQr }}" alt="Invoice QR code" class="qr-image">
                        @else
                            <div class="qr-fallback">QR unavailable</div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="subject">
        <strong>Subject:</strong> {{ $invoice['subject'] }}
    </div>

    <table class="bill-table">
        <tr>
            <td>
                <div class="section-title">Bill To</div>
                <div class="bill-name">{{ $invoice['customer_name'] }}</div>
                <div class="bill-line">Payment due by {{ $invoice['due_date'] }}</div>
                <div class="bill-line">Currency: {{ $invoice['currency'] }}</div>
            </td>
            <td>
                <div class="section-title">Bill From</div>
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
            <td class="value">{{ $invoice['currency'] }} {{ number_format($invoice['sub_total'], 0) }}</td>
        </tr>
        <tr>
            <td>VAT ({{ number_format($invoice['vat_rate'], 1) }}%)</td>
            <td class="value">{{ $invoice['currency'] }} {{ number_format($invoice['vat_amount'], 0) }}</td>
        </tr>
        <tr class="grand">
            <td>Total</td>
            <td class="value">{{ $invoice['currency'] }} {{ number_format($invoice['grand_total'], 0) }}</td>
        </tr>
    </table>

    <table class="notes-table">
        <tr>
            <td>
                <div class="section-title">Notes</div>
                <div class="notes-content">{{ $invoice['notes'] }}</div>
            </td>
            <td>
                <div class="section-title">Terms</div>
                <div class="notes-content">{{ $invoice['terms'] }}</div>
            </td>
        </tr>
    </table>

    <div class="signature-bottom">
        <div class="signature-panel">
            <div class="section-title">Digital Signature (CEO)</div>
            @if(!empty($company['ceo_signature']))
                <img src="{{ $company['ceo_signature'] }}" alt="CEO signature" class="signature-image">
            @else
                <div class="signature-fallback">{{ $company['ceo_name'] }}</div>
            @endif
            <div class="signature-line"></div>
            <div class="signature-meta">Signed by {{ $company['ceo_name'] }}</div>
            <div class="signature-meta">{{ $generatedAt->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    <div class="footer">
        This invoice is generated digitally by AccountYanga Ltd.
    </div>
</body>
</html>

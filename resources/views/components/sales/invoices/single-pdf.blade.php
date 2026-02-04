<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $row['number'] }}</title>
    <style>
        @page {
            margin: 14px 16px 34px;
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
            background: #465fff;
            margin-bottom: 16px;
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
            margin-top: 6px;
            padding: 3px 10px;
            border: 0;
            background: #f8fafc;
            color: #334155;
            font-size: 11px;
            font-weight: 700;
        }

        .invoice-meta {
            margin-top: 10px;
        }

        .invoice-meta .row {
            margin-top: 3px;
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
            margin-top: 12px;
            padding: 10px 12px;
            background: #f8fafc;
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
            width: 140px;
            height: 140px;
            box-sizing: border-box;
            border: 0;
            border-radius: 10px;
            padding: 8px;
            background: #ffffff;
            display: inline-block;
            text-align: center;
            overflow: hidden;
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
            border: 0;
            padding: 4px;
            background: #ffffff;
        }

        .qr-fallback {
            display: inline-block;
            width: 84px;
            height: 84px;
            line-height: 84px;
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
            width: 108px;
            height: 32px;
            object-fit: contain;
            display: block;
            margin: 2px auto 0;
        }

        .signature-fallback {
            width: 108px;
            margin: 2px auto 0;
            text-align: center;
            font-size: 13px;
            font-style: italic;
            color: #334155;
        }

        .signature-line {
            border-top: 0;
            margin: 2px auto 4px;
            width: 108px;
        }

        .signature-meta {
            text-align: center;
            color: #64748b;
            font-size: 7px;
            margin-top: 2px;
        }

        .signature-panel .section-title {
            margin-bottom: 4px;
            font-size: 9px;
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
            width: 84px;
            height: 84px;
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
            bottom: -36px;
            border-top: 0;
            padding-top: 7px;
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

    <div class="security-bottom">
        <table class="security-section">
            <tr>
                <td class="security-cell-left">
                    <div class="stamp-container">
                        @php($stampImagePath = public_path('images/approved-stamp.jpg'))
                        @if(file_exists($stampImagePath))
                            <img src="{{ $stampImagePath }}" alt="Approved stamp" class="stamp-image">
                        @else
                            <div class="signature-meta">Stamp image unavailable</div>
                        @endif
                    </div>
                </td>
                <td class="security-cell-center">
                    <div class="qr-panel">
                        @if(!empty($documentQr))
                            <img src="{{ $documentQr }}" alt="Invoice QR code" class="qr-image">
                        @else
                            <div class="qr-fallback">QR unavailable</div>
                        @endif
                    </div>
                </td>
                <td class="security-cell-right">
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
                </td>
            </tr>
        </table>

        <div class="footer">
            Powered by TerexLab.
        </div>
</body>

</html>

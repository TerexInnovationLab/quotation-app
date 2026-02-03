<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $row['number'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
        }
        .header {
            margin-bottom: 20px;
        }
        .title {
            font-size: 20px;
            margin: 0 0 6px;
            font-weight: 700;
        }
        .meta {
            margin: 0;
            color: #475569;
            font-size: 11px;
        }
        .grid {
            width: 100%;
            margin: 14px 0;
        }
        .grid td {
            width: 50%;
            padding: 4px 0;
            vertical-align: top;
        }
        .label {
            color: #475569;
            font-size: 11px;
        }
        .value {
            font-weight: 700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
        }
        th {
            background: #f8fafc;
            text-align: left;
            font-weight: 700;
        }
        .right {
            text-align: right;
        }
        .totals {
            width: 40%;
            margin-left: auto;
            margin-top: 14px;
        }
        .totals td {
            border: none;
            padding: 4px 0;
        }
        .totals .total td {
            border-top: 1px solid #cbd5e1;
            font-weight: 700;
            padding-top: 8px;
        }
        .footer {
            margin-top: 20px;
            color: #475569;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Invoice {{ $row['number'] }}</div>
        <p class="meta">Generated: {{ $generatedAt->format('Y-m-d H:i') }}</p>
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="label">Customer</div>
                <div class="value">{{ $invoice['customer_name'] }}</div>
            </td>
            <td>
                <div class="label">Status</div>
                <div class="value">{{ $row['status'] }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Invoice Date</div>
                <div class="value">{{ $invoice['invoice_date'] }}</div>
            </td>
            <td>
                <div class="label">Due Date</div>
                <div class="value">{{ $invoice['due_date'] }}</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Rate</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <div>{{ $item['name'] }}</div>
                        @if(!empty($item['note']))
                            <div class="meta">{{ $item['note'] }}</div>
                        @endif
                    </td>
                    <td class="right">{{ number_format($item['qty'], 2) }}</td>
                    <td class="right">{{ number_format($item['rate'], 0) }}</td>
                    <td class="right">{{ number_format($item['line_total'], 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Sub Total</td>
            <td class="right">{{ $invoice['currency'] }} {{ number_format($invoice['sub_total'], 0) }}</td>
        </tr>
        <tr>
            <td>VAT ({{ number_format($invoice['vat_rate'], 1) }}%)</td>
            <td class="right">{{ $invoice['currency'] }} {{ number_format($invoice['vat_amount'], 0) }}</td>
        </tr>
        <tr class="total">
            <td>Total</td>
            <td class="right">{{ $invoice['currency'] }} {{ number_format($invoice['grand_total'], 0) }}</td>
        </tr>
    </table>

    <div class="footer">
        <div><strong>Notes:</strong> {{ $invoice['notes'] }}</div>
        <div><strong>Terms:</strong> {{ $invoice['terms'] }}</div>
    </div>
</body>
</html>

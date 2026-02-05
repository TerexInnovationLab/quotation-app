<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quotations Export</title>
    <style>
        @page {
            margin: 26px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
        }

        .top-accent {
            height: 7px;
            background: #465fff;
            margin-bottom: 14px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .header-table td {
            vertical-align: top;
        }

        .brand {
            width: 60%;
        }

        .meta-wrap {
            width: 40%;
            text-align: right;
        }

        .logo {
            width: 46px;
            height: 46px;
            object-fit: contain;
            display: block;
            margin-bottom: 6px;
        }

        .logo-fallback {
            width: 46px;
            height: 46px;
            line-height: 46px;
            text-align: center;
            font-size: 18px;
            color: #ffffff;
            background: #465fff;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .company {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .company-sub {
            margin: 2px 0 0;
            color: #475569;
            font-size: 11px;
        }

        .title {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.8px;
        }

        .meta {
            margin: 4px 0 0;
            color: #475569;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #dbe3ee;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 11px;
        }

        .amount {
            text-align: right;
        }

        .summary {
            width: 40%;
            margin-left: auto;
            margin-top: 12px;
            border-collapse: collapse;
        }

        .summary td {
            border: 0;
            padding: 3px 0;
            font-size: 11px;
        }

        .summary .value {
            text-align: right;
            font-weight: 700;
        }

        .summary .total td {
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="top-accent"></div>

    <table class="header-table">
        <tr>
            <td class="brand">
                @if(!empty($company['logo']))
                    <img src="{{ $company['logo'] }}" alt="Company logo" class="logo">
                @else
                    <div class="logo-fallback">A</div>
                @endif
                <h1 class="company">{{ $company['name'] }}</h1>
                <p class="company-sub">{{ $company['address'] }} | {{ $company['email'] }}</p>
            </td>
            <td class="meta-wrap">
                <h2 class="title">QUOTATIONS</h2>
                <p class="meta">Generated: {{ $generatedAt->format('Y-m-d H:i') }}</p>
                <p class="meta">Total records: {{ $rows->count() }}</p>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Quotation #</th>
                <th>Customer Name</th>
                <th>Status</th>
                <th>Expiry Date</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['number'] }}</td>
                    <td>{{ $row['customer'] }}</td>
                    <td>{{ $row['status'] }}</td>
                    <td>{{ $row['expiry'] ?? '-' }}</td>
                    <td class="amount">{{ number_format($row['amount'] ?? 0, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No quotations found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $grand = (int) $rows->sum('amount');
    @endphp

    <table class="summary">
        <tr>
            <td>Total Quotations</td>
            <td class="value">{{ $rows->count() }}</td>
        </tr>
        <tr class="total">
            <td>Grand Total</td>
            <td class="value">MWK {{ number_format($grand, 0) }}</td>
        </tr>
    </table>
</body>
</html>

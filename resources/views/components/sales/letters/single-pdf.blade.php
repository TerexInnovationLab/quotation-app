<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Letter {{ $row['number'] }}</title>
    <style>
        @page {
            margin: 20px 22px 28px;
        }

        body {
            font-family: "DejaVu Serif", serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.55;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 42%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 64px;
            letter-spacing: 10px;
            font-weight: 700;
            color: #cbd5e1;
            opacity: 0.22;
            transform: rotate(-28deg);
            z-index: -1;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .letterhead {
            border: 2px solid #1e3a8a;
            border-radius: 18px;
            padding: 12px 14px;
        }

        .letterhead-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 112px;
        }

        .logo-box {
            width: 96px;
            height: 96px;
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            display: table;
            background: #ffffff;
        }

        .logo-box-inner {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .logo {
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .logo-fallback {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
        }

        .brand-cell {
            padding-left: 6px;
        }

        .brand-line {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 4px;
            line-height: 1.12;
            color: #1e40af;
        }

        .brand-top {
            color: #b91c1c;
        }

        .meta-cell {
            text-align: right;
            font-size: 10px;
            color: #475569;
        }

        .meta-cell .tagline {
            font-style: italic;
            color: #1e3a8a;
            margin-bottom: 4px;
        }

        .meta-line {
            margin-top: 2px;
        }

        .date-issued {
            margin-top: 12px;
            text-align: right;
            font-size: 10px;
            letter-spacing: 2px;
            color: #1e3a8a;
            font-weight: 700;
            text-transform: uppercase;
        }

        .reference {
            margin-top: 4px;
            text-align: right;
            font-size: 10px;
            letter-spacing: 1.4px;
            color: #1e3a8a;
            font-weight: 600;
            text-transform: uppercase;
        }

        .recipient {
            margin-top: 18px;
        }

        .recipient-line {
            margin-top: 2px;
        }

        .subject {
            margin-top: 20px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #1e3a8a;
            line-height: 1.6;
        }

        .letter-body {
            margin-top: 16px;
            white-space: pre-line;
            font-size: 12px;
            color: #0f172a;
        }

        .signature-block {
            margin-top: 24px;
        }

        .signature-image {
            width: 160px;
            height: 50px;
            object-fit: contain;
            display: block;
            margin-top: 8px;
        }

        .signature-name {
            margin-top: 6px;
            font-weight: 700;
        }

        .signature-title {
            font-size: 11px;
            color: #475569;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    @if(!empty($watermarkText))
        <div class="watermark">{{ $watermarkText }}</div>
    @endif

    <div class="letterhead">
        <table class="letterhead-table">
            <tr>
                <td class="logo-cell">
                    <div class="logo-box">
                        <div class="logo-box-inner">
                            @if(!empty($company['logo']))
                                <img src="{{ $company['logo'] }}" alt="Company logo" class="logo">
                            @else
                                <div class="logo-fallback">{{ strtoupper(substr($company['name'] ?? 'T', 0, 1)) }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="brand-cell">
                    <div class="brand-line brand-top">{{ $company['brand_top'] ?? 'TEREX' }}</div>
                    <div class="brand-line">{{ $company['brand_middle'] ?? 'INNOVATION' }}</div>
                    <div class="brand-line">{{ $company['brand_bottom'] ?? 'LAB' }}</div>
                </td>
                <td class="meta-cell">
                    <div class="tagline">{{ $company['tagline'] ?? '' }}</div>
                    @foreach(($company['address_lines'] ?? []) as $line)
                        <div class="meta-line">{{ $line }}</div>
                    @endforeach
                    @if(!empty($company['email']))
                        <div class="meta-line">Email: {{ $company['email'] }}</div>
                    @endif
                    @if(!empty($company['website']))
                        <div class="meta-line">Website: {{ $company['website'] }}</div>
                    @endif
                    @if(!empty($company['phone']))
                        <div class="meta-line">Phone: {{ $company['phone'] }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="date-issued">Date issued: {{ $letter['date_issued'] }}</div>
    <div class="reference">Ref: {{ $letter['reference'] }}</div>

    <div class="recipient">
        <div class="recipient-line">{{ $letter['recipient_name'] }}</div>
        @if(!empty($letter['recipient_org']))
            <div class="recipient-line">{{ $letter['recipient_org'] }}</div>
        @endif
        @foreach(preg_split("/\r\n|\n|\r/", $letter['recipient_address']) as $line)
            @if(trim($line) !== '')
                <div class="recipient-line">{{ $line }}</div>
            @endif
        @endforeach
    </div>

    <div class="subject">{{ $letter['subject'] }}</div>

    <div class="letter-body">{{ $letter['body'] }}</div>

    <div class="signature-block">
        <div>{{ $letter['closing'] }}</div>
        @if(!empty($company['signature']))
            <img src="{{ $company['signature'] }}" alt="Signature" class="signature-image">
        @endif
        <div class="signature-name">{{ $letter['sender_name'] }}</div>
        <div class="signature-title">{{ $letter['sender_title'] }}</div>
    </div>
</body>
</html>

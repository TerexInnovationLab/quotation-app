@extends('layout.app')

@section('title','View Quotation')
@section('breadcrumb','Sales / Quotations')
@section('page_title','Quotation ' . $row['number'])

@section('primary_action')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.quotations.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Back to List
        </a>
        <a href="{{ route('sales.quotations.edit', $row['number']) }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
            Edit Quotation
        </a>
    </div>
@endsection

@section('content')
    @php
        $shareText = 'Quotation ' . $row['number'] . ' for ' . $row['customer'] . ' (' . number_format($row['amount'], 0) . ' MWK)';
        $shareUrl = route('sales.quotations.show', $row['number']);
    @endphp

    <div class="space-y-4">
        <div class="grid grid-cols-1 xl:grid-cols-[1.2fr,2fr] gap-4">
            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="text-sm font-semibold mb-3">Actions</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button type="button"
                                onclick="window.shareQuotation && window.shareQuotation({ text: @js($shareText), url: @js($shareUrl) })"
                                class="px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Share
                        </button>
                        <a href="{{ route('sales.quotations.download', $row['number']) }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Download PDF
                        </a>
                        <a href="{{ route('sales.quotations.convert.invoice', $row['number']) }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6] sm:col-span-2">
                            Convert to Invoice
                        </a>
                    </div>
                    <div class="text-xs text-slate-500 mt-3">
                        Share sends/opens the quotation link. Download exports this quotation PDF.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-xs text-slate-500">Quotation #</div>
                            <div class="font-semibold">{{ $row['number'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Customer</div>
                            <div class="font-semibold">{{ $row['customer'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Quotation Date</div>
                            <div class="font-semibold">{{ $row['date'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Expiry Date</div>
                            <div class="font-semibold">{{ $row['expiry'] ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Status</div>
                            <div class="font-semibold">{{ $row['status'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Amount</div>
                            <div class="font-semibold">{{ $quotation['currency'] }} {{ number_format($row['amount'], 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold">Quotation PDF Preview</div>
                        <div class="text-xs text-slate-500">Live preview of {{ $row['number'] }}</div>
                    </div>
                    <a href="{{ route('sales.quotations.pdf', $row['number']) }}" target="_blank"
                       class="inline-flex items-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                        Open Full PDF
                    </a>
                </div>

                <iframe src="{{ route('sales.quotations.pdf', $row['number']) }}"
                        class="w-full h-[900px] bg-white"
                        title="Quotation PDF preview">
                </iframe>

                <div class="px-5 py-3 border-t border-slate-200 text-xs text-slate-500">
                    If preview does not load in your browser, use "Open Full PDF" or "Download PDF".
                </div>
            </div>
        </div>
    </div>

    <script>
        window.shareQuotation = async function(payload) {
            try {
                if (navigator.share) {
                    await navigator.share({
                        title: payload.text,
                        text: payload.text,
                        url: payload.url
                    });
                    return;
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(payload.url);
                    alert('Quotation link copied to clipboard.');
                    return;
                }

                prompt('Copy this quotation link:', payload.url);
            } catch (error) {
                // User cancelled or browser blocked sharing.
            }
        }
    </script>
@endsection

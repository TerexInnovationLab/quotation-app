@extends('layout.app')

@section('title','View Invoice')
@section('breadcrumb','Sales / Invoices')
@section('page_title','Invoice ' . $row['number'])

@section('primary_action')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.invoices.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Back to List
        </a>
        <a href="{{ route('sales.invoices.edit', $row['number']) }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
            Edit Invoice
        </a>
    </div>
@endsection

@section('content')
    @php
        $paymentNumber = 'PAY-' . (preg_replace('/\D+/', '', $row['number']) ?: '00000');
        $recordPaymentUrl = route('sales.payments.create', [
            'payment_number' => $paymentNumber,
            'customer_name' => $row['customer'],
            'reference' => $row['number'],
            'amount' => $row['amount'],
            'notes' => 'Payment for invoice ' . $row['number'],
        ]);
        $shareText = 'Invoice ' . $row['number'] . ' for ' . $row['customer'] . ' (' . number_format($row['amount'], 0) . ' MWK)';
        $shareUrl = route('sales.invoices.show', $row['number']);
    @endphp

    <div class="space-y-4">
        <div class="grid grid-cols-1 xl:grid-cols-[1.2fr,2fr] gap-4">
            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="text-sm font-semibold mb-3">Actions</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button type="button"
                                onclick="window.shareInvoice && window.shareInvoice({ text: @js($shareText), url: @js($shareUrl) })"
                                class="px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Share
                        </button>
                        <a href="{{ route('sales.invoices.download', $row['number']) }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Download PDF
                        </a>
                        <a href="{{ route('sales.invoices.convert.receipt', $row['number']) }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Convert to Receipt
                        </a>
                        <a href="{{ $recordPaymentUrl }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                            Record Payment
                        </a>
                    </div>
                    <div class="text-xs text-slate-500 mt-3">
                        Share sends/opens the invoice link. Download exports this invoice PDF.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-xs text-slate-500">Invoice #</div>
                            <div class="font-semibold">{{ $row['number'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Customer</div>
                            <div class="font-semibold">{{ $row['customer'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Invoice Date</div>
                            <div class="font-semibold">{{ $row['date'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Due Date</div>
                            <div class="font-semibold">{{ $row['due'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Status</div>
                            <div class="font-semibold">{{ $row['status'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Amount</div>
                            <div class="font-semibold">{{ $invoice['currency'] }} {{ number_format($row['amount'], 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold">Invoice PDF Preview</div>
                        <div class="text-xs text-slate-500">Live preview of {{ $row['number'] }}</div>
                    </div>
                    <a href="{{ route('sales.invoices.pdf', $row['number']) }}" target="_blank"
                       class="inline-flex items-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                        Open Full PDF
                    </a>
                </div>

                <iframe src="{{ route('sales.invoices.pdf', $row['number']) }}"
                        class="w-full h-[900px] bg-white"
                        title="Invoice PDF preview">
                </iframe>

                <div class="px-5 py-3 border-t border-slate-200 text-xs text-slate-500">
                    If preview does not load in your browser, use "Open Full PDF" or "Download PDF".
                </div>
            </div>
        </div>
    </div>

    <script>
        window.shareInvoice = async function(payload) {
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
                    alert('Invoice link copied to clipboard.');
                    return;
                }

                prompt('Copy this invoice link:', payload.url);
            } catch (error) {
                // User cancelled or browser blocked sharing.
            }
        }
    </script>
@endsection


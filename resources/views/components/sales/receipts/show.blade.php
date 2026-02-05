@extends('layout.app')

@section('title','View Receipt')
@section('breadcrumb','Sales / Receipts')
@section('page_title','Receipt ' . $row['number'])

@section('primary_action')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.receipts.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Back to List
        </a>
        <a href="{{ route('sales.receipts.edit', $row['number']) }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
            Edit Receipt
        </a>
    </div>
@endsection

@section('content')
    @php
        $shareText = 'Receipt ' . $row['number'] . ' for ' . $row['customer'];
        $shareUrl = route('sales.receipts.show', $row['number']);
        $sendUrl = route('sales.receipts.send', $row['number']);
    @endphp

    <div class="space-y-4">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-[1.2fr,2fr] gap-4">
            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="text-sm font-semibold mb-3">Actions</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button type="button"
                                onclick="window.shareReceipt && window.shareReceipt({ text: @js($shareText), url: @js($shareUrl) })"
                                class="px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Share
                        </button>
                        <a href="{{ route('sales.receipts.download', $row['number']) }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Download PDF
                        </a>
                        <button type="button"
                                onclick="window.sendReceipt && window.sendReceipt(@js($sendUrl))"
                                class="px-3 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6] sm:col-span-2">
                            Send Receipt
                        </button>
                    </div>
                    <div class="text-xs text-slate-500 mt-3">
                        Share sends/opens the receipt link. Download exports the receipt PDF.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-xs text-slate-500">Receipt #</div>
                            <div class="font-semibold">{{ $row['number'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Customer</div>
                            <div class="font-semibold">{{ $row['customer'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Date</div>
                            <div class="font-semibold">{{ $row['date'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Status</div>
                            <div class="font-semibold">{{ $row['status'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Method</div>
                            <div class="font-semibold">{{ $row['method'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Reference</div>
                            <div class="font-semibold">{{ $row['reference'] ?: '-' }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-xs text-slate-500">Amount</div>
                            <div class="font-semibold">{{ $receipt['currency'] }} {{ number_format($receipt['amount'], 0) }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-xs text-slate-500">Notes</div>
                            <div class="font-semibold">{{ $receipt['notes'] ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold">Receipt PDF Preview</div>
                        <div class="text-xs text-slate-500">Live preview of {{ $row['number'] }}</div>
                    </div>
                    <a href="{{ route('sales.receipts.pdf', $row['number']) }}" target="_blank"
                       class="inline-flex items-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                        Open Full PDF
                    </a>
                </div>

                <iframe src="{{ route('sales.receipts.pdf', $row['number']) }}"
                        class="w-full h-[900px] bg-white"
                        title="Receipt PDF preview">
                </iframe>

                <div class="px-5 py-3 border-t border-slate-200 text-xs text-slate-500">
                    If preview does not load in your browser, use "Open Full PDF" or "Download PDF".
                </div>
            </div>
        </div>
    </div>

    <script>
        window.shareReceipt = async function(payload) {
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
                    alert('Receipt link copied to clipboard.');
                    return;
                }

                prompt('Copy this receipt link:', payload.url);
            } catch (error) {
                // User cancelled or browser blocked sharing.
            }
        }

        window.sendReceipt = async function(url) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': @js(csrf_token()),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                alert(data.message || 'Receipt sent.');
            } catch (error) {
                alert('Unable to send receipt. Try again.');
            }
        }
    </script>
@endsection

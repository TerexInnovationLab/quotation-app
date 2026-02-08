@extends('layout.app')

@section('title','View Letter')
@section('breadcrumb','Sales / Letters')
@section('page_title','Letter ' . $row['number'])

@section('primary_action')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.letters.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Back to List
        </a>
        <a href="{{ route('sales.letters.edit', $row['number']) }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
            Edit Letter
        </a>
    </div>
@endsection

@section('content')
    @php
        $shareText = 'Letter ' . $row['number'] . ' to ' . $row['recipient'];
        $shareUrl = route('sales.letters.show', $row['number']);
        $sendUrl = route('sales.letters.send', $row['number']);
        $templateCookie = collect([
            request()->cookie('document_template', ''),
            request()->cookie('invoice_template', ''),
            request()->cookie('quotation_template', ''),
            request()->cookie('letter_template', ''),
            request()->cookie('receipt_template', ''),
        ])->first(fn ($value) => is_string($value) && $value !== '');
        $templateColorCookie = collect([
            request()->cookie('document_template_color', ''),
            request()->cookie('invoice_template_color', ''),
            request()->cookie('quotation_template_color', ''),
            request()->cookie('letter_template_color', ''),
            request()->cookie('receipt_template_color', ''),
        ])->first(fn ($value) => is_string($value) && $value !== '');
        $pdfCacheKey = md5((string) $templateCookie . '|' . (string) $templateColorCookie);
        $pdfUrl = route('sales.letters.pdf', ['letter' => $row['number'], 'v' => $pdfCacheKey]);
    @endphp

    <div class="space-y-4">
        <div class="grid grid-cols-1 xl:grid-cols-[1.2fr,2fr] gap-4">
            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="text-sm font-semibold mb-3">Actions</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button type="button"
                                onclick="window.shareLetter && window.shareLetter({ text: @js($shareText), url: @js($shareUrl) })"
                                class="px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Share
                        </button>
                        <a href="{{ route('sales.letters.download', $row['number']) }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                            Download PDF
                        </a>
                        <button type="button"
                                onclick="window.sendLetter && window.sendLetter(@js($sendUrl))"
                                class="px-3 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6] sm:col-span-2">
                            Send Letter
                        </button>
                    </div>
                    <div class="text-xs text-slate-500 mt-3">
                        Share sends/opens the letter link. Download exports the letter PDF.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-xs text-slate-500">Letter #</div>
                            <div class="font-semibold">{{ $row['number'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Recipient</div>
                            <div class="font-semibold">{{ $row['recipient'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Date Issued</div>
                            <div class="font-semibold">{{ $row['date'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Status</div>
                            <div class="font-semibold">{{ $row['status'] }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-xs text-slate-500">Subject</div>
                            <div class="font-semibold">{{ $letter['subject'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold">Letter PDF Preview</div>
                        <div class="text-xs text-slate-500">Live preview of {{ $row['number'] }}</div>
                    </div>
                    <a href="{{ $pdfUrl }}" target="_blank"
                       class="inline-flex items-center px-3 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                        Open Full PDF
                    </a>
                </div>

                <iframe src="{{ $pdfUrl }}"
                        class="w-full h-[900px] bg-white"
                        title="Letter PDF preview">
                </iframe>

                <div class="px-5 py-3 border-t border-slate-200 text-xs text-slate-500">
                    If preview does not load in your browser, use "Open Full PDF" or "Download PDF".
                </div>
            </div>
        </div>
    </div>

    <script>
        window.shareLetter = async function(payload) {
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
                    alert('Letter link copied to clipboard.');
                    return;
                }

                prompt('Copy this letter link:', payload.url);
            } catch (error) {
                // User cancelled or browser blocked sharing.
            }
        }

        window.sendLetter = async function(url) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': @js(csrf_token()),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                alert(data.message || 'Letter sent.');
            } catch (error) {
                alert('Unable to send letter. Try again.');
            }
        }
    </script>
@endsection

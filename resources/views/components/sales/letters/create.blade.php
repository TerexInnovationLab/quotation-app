@extends('layout.app')

@section('title','New Letter')
@section('breadcrumb','Sales / Letters')
@section('page_title','New Letter')

@section('primary_action')
    <a href="{{ route('sales.letters.index') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
        Back to List
    </a>
@endsection

@section('content')
<div class="max-w-6xl space-y-4">
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4">
            <div class="font-semibold mb-2">Please fix the following:</div>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sales.letters.store') }}" class="space-y-4">
        @csrf

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold">Letter Details</div>
                    <div class="text-xs text-slate-500">Recipient, subject, and reference details</div>
                </div>
                <button type="submit"
                        class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                    Save Letter
                </button>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Recipient Name *</label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200"
                           placeholder="e.g. The Internal Procurement Committee">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Recipient Organization</label>
                    <select name="recipient_org"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        <option value="">Select organization</option>
                        @foreach($recipients as $recipient)
                            <option value="{{ $recipient }}" @selected(old('recipient_org') === $recipient)>{{ $recipient }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-500">Recipient Email</label>
                    <input type="email" name="recipient_email" value="{{ old('recipient_email') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200"
                           placeholder="e.g. procurement@example.com">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Date Issued *</label>
                    <input type="date" name="letter_date" value="{{ old('letter_date', now()->toDateString()) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Letter Number *</label>
                    <input type="text" name="letter_number" value="{{ old('letter_number', 'LTR-') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Status *</label>
                    <select name="status"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach(['Draft', 'Sent', 'Signed'] as $status)
                            <option value="{{ $status }}" @selected(old('status', 'Draft') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500">Recipient Address *</label>
                    <textarea name="recipient_address" rows="4"
                              class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200"
                              placeholder="Street, city, country">{{ old('recipient_address') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500">Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200"
                           placeholder="e.g. Proposal for membership management portal">
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-sm font-semibold">Letter Body</div>
            <div class="text-xs text-slate-500">Write the main content of the letter</div>
            <textarea name="body" rows="10"
                      class="mt-3 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200"
                      placeholder="Type your letter here...">{{ old('body') }}</textarea>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-sm font-semibold">Signature</div>
            <div class="text-xs text-slate-500">Closing line and sender details</div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Closing</label>
                    <input type="text" name="closing" value="{{ old('closing', 'Sincerely,') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Sender Name *</label>
                    <input type="text" name="sender_name" value="{{ old('sender_name', 'Richard Chilipa') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Sender Title</label>
                    <input type="text" name="sender_title" value="{{ old('sender_title', 'Chief Executive Officer') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Signature image will use the company signature file if available.
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('sales.letters.index') }}"
               class="px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                Save Letter
            </button>
        </div>
    </form>
</div>
@endsection

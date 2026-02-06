@extends('layout.app')

@section('title','Edit Letter')
@section('breadcrumb','Sales / Letters')
@section('page_title','Edit ' . $row['number'])

@section('primary_action')
    <a href="{{ route('sales.letters.show', $row['number']) }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
        Back to Letter
    </a>
@endsection

@section('content')
<div class="max-w-6xl space-y-4">
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

    <form method="POST" action="{{ route('sales.letters.update', $row['number']) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold">Letter Details</div>
                    <div class="text-xs text-slate-500">Recipient, subject, and reference details</div>
                </div>
                <button type="submit"
                        class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                    Save Changes
                </button>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Recipient Name *</label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name', $letter['recipient_name']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Recipient Organization</label>
                    <input type="text" name="recipient_org" value="{{ old('recipient_org', $letter['recipient_org']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Recipient Email</label>
                    <input type="email" name="recipient_email" value="{{ old('recipient_email', $row['email'] ?? '') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Date Issued *</label>
                    <input type="date" name="letter_date" value="{{ old('letter_date', $letter['date_issued']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Letter Number</label>
                    <input type="text" value="{{ $row['number'] }}" disabled
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-100 text-slate-500">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Status *</label>
                    <select name="status"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach(['Draft', 'Sent', 'Signed'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $row['status']) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500">Recipient Address *</label>
                    <textarea name="recipient_address" rows="4"
                              class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">{{ old('recipient_address', $letter['recipient_address']) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500">Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject', $letter['subject']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-sm font-semibold">Letter Body</div>
            <div class="text-xs text-slate-500">Write the main content of the letter</div>
            <textarea name="body" rows="10"
                      class="mt-3 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">{{ old('body', $letter['body']) }}</textarea>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-sm font-semibold">Signature</div>
            <div class="text-xs text-slate-500">Closing line and sender details</div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Closing</label>
                    <input type="text" name="closing" value="{{ old('closing', $letter['closing']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Sender Name *</label>
                    <input type="text" name="sender_name" value="{{ old('sender_name', $letter['sender_name']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Sender Title</label>
                    <input type="text" name="sender_title" value="{{ old('sender_title', $letter['sender_title']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('sales.letters.show', $row['number']) }}"
               class="px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection

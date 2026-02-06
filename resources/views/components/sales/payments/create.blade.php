@extends('layout.app')

@section('title','Record Payment')
@section('breadcrumb','Sales / Payments Received')
@section('page_title','Record Payment')

@section('primary_action')
    <a href="{{ route('sales.payments.index') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
        Back to List
    </a>
@endsection

@section('content')
    <div class="max-w-3xl space-y-4">
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

        <form method="POST" action="{{ route('sales.payments.store') }}" class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Payment # *</label>
                    <input type="text" name="payment_number" value="{{ old('payment_number', request('payment_number')) }}" placeholder="e.g. PAY-00045"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Customer Name *</label>
                    <select name="customer_name"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        <option value="">Select customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer }}" @selected(old('customer_name', request('customer_name')) === $customer)>{{ $customer }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500">Method *</label>
                    <select name="method"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        <option value="">Select method</option>
                        @foreach($methods as $method)
                            <option value="{{ $method }}" @selected(old('method', request('method')) === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500">Reference</label>
                    <input type="text" name="reference" value="{{ old('reference', request('reference')) }}" placeholder="e.g. NBS-TRX-39401"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Amount *</label>
                    <input type="number" min="0.01" step="1" name="amount" value="{{ old('amount', request('amount')) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500">Notes</label>
                    <textarea name="notes" rows="4"
                              class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200"
                              placeholder="Optional internal note">{{ old('notes', request('notes')) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('sales.payments.index') }}"
                   class="px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                    Save Payment
                </button>
            </div>
        </form>
    </div>
@endsection

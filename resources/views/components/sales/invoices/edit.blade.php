@extends('layout.app')

@section('title','Edit Invoice')
@section('breadcrumb','Sales / Invoices')
@section('page_title','Edit ' . $row['number'])

@section('primary_action')
    <a href="{{ route('sales.invoices.show', $row['number']) }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
        Back to Invoice
    </a>
@endsection

@section('content')
    <div class="max-w-4xl space-y-4">
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

        <form method="POST" action="{{ route('sales.invoices.update', $row['number']) }}" class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Invoice #</label>
                    <input type="text" value="{{ $row['number'] }}" disabled
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-slate-600">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Customer Name *</label>
                    <select name="customer_name"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        <option value="">Select customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer }}" @selected(old('customer_name', $row['customer']) === $customer)>
                                {{ $customer }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500">Invoice Date *</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $row['date']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Due Date *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $row['due']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>

                <div>
                    <label class="text-xs text-slate-500">Status *</label>
                    <select name="status"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach(['Draft', 'Sent', 'Paid', 'Unpaid', 'Overdue'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $row['status']) === $status)>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500">Amount (MWK) *</label>
                    <input type="number" min="0" step="1" name="amount" value="{{ old('amount', $row['amount']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('sales.invoices.show', $row['number']) }}"
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

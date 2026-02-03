@extends('layout.app')

@section('title','New Client')
@section('breadcrumb','Sales / Clients')
@section('page_title','New Client')

@section('primary_action')
    <a href="{{ route('sales.clients.index') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
        Back to List
    </a>
@endsection

@section('content')
    <div class="max-w-5xl space-y-4">
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

        <form method="POST" action="{{ route('sales.clients.store') }}" class="space-y-4">
            @csrf

            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-sm font-semibold">Client Profile</div>
                <div class="text-xs text-slate-500">Basic customer details and contact information</div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Client Code *</label>
                        <input type="text" name="client_code" value="{{ old('client_code') }}" placeholder="e.g. CL-00023"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Status *</label>
                        <select name="status" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach(['Active', 'Inactive'] as $option)
                                <option value="{{ $option }}" @selected(old('status', 'Active') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Company Name *</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Contact Person *</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Phone *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-sm font-semibold">Billing Profile</div>
                <div class="text-xs text-slate-500">Save billing defaults for this client</div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Currency *</label>
                        <select name="currency" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency }}" @selected(old('currency', 'MWK') === $currency)>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Payment Terms *</label>
                        <select name="payment_terms" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach($paymentTerms as $term)
                                <option value="{{ $term }}" @selected(old('payment_terms', '30 days') === $term)>{{ $term }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Tax ID</label>
                        <input type="text" name="tax_id" value="{{ old('tax_id') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Credit Limit</label>
                        <input type="number" min="0" step="1" name="credit_limit" value="{{ old('credit_limit') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs text-slate-500">Billing Address *</label>
                        <textarea name="billing_address" rows="4"
                                  class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">{{ old('billing_address') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('sales.clients.index') }}"
                   class="px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                    Save Client
                </button>
            </div>
        </form>
    </div>
@endsection

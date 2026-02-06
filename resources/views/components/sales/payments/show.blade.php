@extends('layout.app')

@section('title','View Payment')
@section('breadcrumb','Sales / Payments Received')
@section('page_title','Payment ' . $row['number'])

@section('primary_action')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.payments.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Back to List
        </a>
        <a href="{{ route('sales.payments.edit', $row['number']) }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
            Edit Payment
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl space-y-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-xs text-slate-500">Payment #</div>
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
                    <div class="text-xs text-slate-500">Method</div>
                    <div class="font-semibold">{{ $row['method'] }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Reference</div>
                    <div class="font-semibold">{{ $row['reference'] ?: '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Amount</div>
                    <div class="font-semibold">MWK {{ number_format($row['amount'], 0) }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-xs text-slate-500">Notes</div>
                    <div class="font-semibold">{{ $row['notes'] ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

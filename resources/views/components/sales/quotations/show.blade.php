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
    <div class="max-w-4xl space-y-4">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4">
                {{ session('success') }}
            </div>
        @endif

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
                    <div class="font-semibold">MWK {{ number_format($row['amount'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

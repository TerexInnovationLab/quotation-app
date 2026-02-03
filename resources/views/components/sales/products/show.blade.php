@extends('layout.app')

@section('title','Product / Service')
@section('breadcrumb','Sales / Products & Services')
@section('page_title', $row['name'])

@section('primary_action')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.products.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Back to List
        </a>
        <a href="{{ route('sales.products.edit', $row['code']) }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
            Edit Item
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
                    <div class="text-xs text-slate-500">Code</div>
                    <div class="font-semibold">{{ $row['code'] }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Name</div>
                    <div class="font-semibold">{{ $row['name'] }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Type</div>
                    <div class="font-semibold">{{ $row['type'] }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Unit</div>
                    <div class="font-semibold">{{ $row['unit'] }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Default Price</div>
                    <div class="font-semibold">{{ number_format($row['default_price'], 0) }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Tax Rate</div>
                    <div class="font-semibold">{{ $row['tax_rate'] }}%</div>
                </div>
                <div>
                    <div class="text-xs text-slate-500">Status</div>
                    <div class="font-semibold">{{ $row['status'] }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-xs text-slate-500">Description</div>
                    <div class="font-semibold">{{ $row['description'] ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

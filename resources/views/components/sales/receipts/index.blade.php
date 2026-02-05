@extends('layout.app')

@section('title','Receipts')
@section('breadcrumb','Sales')
@section('page_title','Receipts')

@section('primary_action')
    <a href="{{ route('sales.receipts.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
        + New Receipt
    </a>
@endsection

@section('content')
    <div class="space-y-4">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4">
                {{ session('success') }}
            </div>
        @endif

        <x-sales.list-toolbar
            searchPlaceholder="Search receipts (number, customer, reference)..."
            :statusOptions="['All Statuses','Draft','Issued','Sent']"
        />

        <x-sales.table
            :columns="[
                ['key'=>'date','label'=>'Date'],
                ['key'=>'number','label'=>'Receipt #'],
                ['key'=>'customer','label'=>'Customer Name'],
                ['key'=>'status','label'=>'Status'],
                ['key'=>'method','label'=>'Method'],
                ['key'=>'reference','label'=>'Reference'],
                ['key'=>'amount','label'=>'Amount'],
            ]"
            :rows="$rows"
            emptyTitle="No receipts yet"
            emptyHint="Create a receipt to acknowledge customer payments."
        />
    </div>
@endsection

@extends('layout.app')

@section('title','Quotations')
@section('breadcrumb','Sales')
@section('page_title','Quotations')

@section('primary_action')
    <a href="{{ route('sales.quotations.export.pdf') }}"
        class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Export PDF
    </a>
    <a href="{{ route('sales.quotations.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
        + New Quotation
    </a>
@endsection

@section('content')
    <div class="space-y-4">
        <x-sales.list-toolbar searchPlaceholder="Search quotations (number, customer)..." />

        <x-sales.table
            :columns="[
                ['key'=>'date','label'=>'Date'],
                ['key'=>'number','label'=>'Quotation #'],
                ['key'=>'customer','label'=>'Customer Name'],
                ['key'=>'status','label'=>'Status'],
                ['key'=>'amount','label'=>'Amount'],
            ]"
            :rows="$rows"
            emptyTitle="No quotations yet"
            emptyHint="Create your first quotation to get started."
        />
    </div>
@endsection

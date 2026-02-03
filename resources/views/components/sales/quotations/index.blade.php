@extends('layout.app')

@section('title','Quotations')
@section('breadcrumb','Sales')
@section('page_title','Quotations')

@section('primary_action')
    <button class="px-4 py-2 text-sm rounded-xl bg-slate-900 text-white hover:bg-slate-800">
        + New Quotation
    </button>
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

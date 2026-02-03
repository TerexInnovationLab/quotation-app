@extends('layout.app')

@section('title','Payments Received')
@section('breadcrumb','Sales')
@section('page_title','Payments Received')

@section('primary_action')
    <button class="px-4 py-2 text-sm rounded-xl bg-slate-900 text-white hover:bg-slate-800">
        + Record Payment
    </button>
@endsection

@section('content')
    <div class="space-y-4">
        <x-sales.list-toolbar searchPlaceholder="Search payments (number, customer, reference)..." :statusOptions="['All','Bank Transfer','Cash','Card','Mobile Money']"/>

        <x-sales.table
            :columns="[
                ['key'=>'date','label'=>'Date'],
                ['key'=>'number','label'=>'Payment #'],
                ['key'=>'customer','label'=>'Customer Name'],
                ['key'=>'method','label'=>'Method'],
                ['key'=>'reference','label'=>'Reference'],
                ['key'=>'amount','label'=>'Amount'],
            ]"
            :rows="$rows"
            emptyTitle="No payments recorded"
            emptyHint="Record payments received from customers to track collections."
        />
    </div>
@endsection

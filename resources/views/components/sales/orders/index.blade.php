@extends('layout.app')

@section('title','Sales Orders')
@section('breadcrumb','Sales')
@section('page_title','Sales Orders')

@section('primary_action')
    <button class="px-4 py-2 text-sm rounded-xl bg-slate-900 text-white hover:bg-slate-800">
        + New Sales Order
    </button>
@endsection

@section('content')
    <div class="space-y-4">
        <x-sales.list-toolbar searchPlaceholder="Search sales orders (number, customer)..." :statusOptions="['All Statuses','Open','Closed','Cancelled']"/>

        <x-sales.table
            :columns="[
                ['key'=>'date','label'=>'Date'],
                ['key'=>'number','label'=>'Sales Order #'],
                ['key'=>'customer','label'=>'Customer Name'],
                ['key'=>'status','label'=>'Status'],
                ['key'=>'amount','label'=>'Amount'],
            ]"
            :rows="$rows"
            emptyTitle="No sales orders yet"
            emptyHint="Sales Orders will show up here once created."
        />
    </div>
@endsection

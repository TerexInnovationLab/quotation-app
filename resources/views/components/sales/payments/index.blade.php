@extends('layout.app')

@section('title','Payments Received')
@section('breadcrumb','Sales')
@section('page_title','Payments Received')

@section('primary_action')
    <a href="{{ route('sales.payments.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
        + Record Payment
    </a>
@endsection

@section('content')
    <div class="space-y-4">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4">
                {{ session('success') }}
            </div>
        @endif

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

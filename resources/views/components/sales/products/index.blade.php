@extends('layout.app')

@section('title','Products / Services')
@section('breadcrumb','Sales')
@section('page_title','Products / Services')

@section('primary_action')
    <a href="{{ route('sales.products.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
        + New Item
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
            searchPlaceholder="Search products/services by name or code..."
            :statusOptions="['All', 'Product', 'Service', 'Active', 'Inactive']"
        />

        <x-sales.table
            :columns="[
                ['key'=>'code','label'=>'Code'],
                ['key'=>'name','label'=>'Name'],
                ['key'=>'type','label'=>'Type'],
                ['key'=>'unit','label'=>'Unit'],
                ['key'=>'tax_rate','label'=>'Tax Rate'],
                ['key'=>'amount','label'=>'Default Price'],
                ['key'=>'status','label'=>'Status'],
            ]"
            :rows="$rows"
            emptyTitle="No products/services yet"
            emptyHint="Create default items with price, tax rate, and units."
        />
    </div>
@endsection

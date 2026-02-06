@extends('layout.app')

@section('title','Clients / Customers')
@section('breadcrumb','Sales')
@section('page_title','Clients / Customers')

@section('primary_action')
    <a href="{{ route('sales.clients.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
        + New Client
    </a>
@endsection

@section('content')
    <div class="space-y-4">
        <x-sales.list-toolbar
            searchPlaceholder="Search clients (name, email, phone)..."
            :statusOptions="['All Statuses', 'Active', 'Inactive']"
        />

        <x-sales.table
            :columns="[
                ['key'=>'code','label'=>'Client Code'],
                ['key'=>'name','label'=>'Company'],
                ['key'=>'contact','label'=>'Contact Person'],
                ['key'=>'email','label'=>'Email'],
                ['key'=>'phone','label'=>'Phone'],
                ['key'=>'status','label'=>'Status'],
                ['key'=>'amount','label'=>'Outstanding Balance'],
            ]"
            :rows="$rows"
            emptyTitle="No clients yet"
            emptyHint="Add clients/customers to manage profiles and billing."
        />
    </div>
@endsection

@extends('layout.app')

@section('title','Letters')
@section('breadcrumb','Sales')
@section('page_title','Letters')

@section('primary_action')
    <a href="{{ route('sales.letters.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
        + New Letter
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
            searchPlaceholder="Search letters (number, recipient, subject)..."
            :statusOptions="['All Statuses','Draft','Sent','Signed']"
        />

        <x-sales.table
            :columns="[
                ['key'=>'date','label'=>'Date'],
                ['key'=>'number','label'=>'Letter #'],
                ['key'=>'recipient','label'=>'Recipient'],
                ['key'=>'subject','label'=>'Subject'],
                ['key'=>'status','label'=>'Status'],
            ]"
            :rows="$rows"
            emptyTitle="No letters yet"
            emptyHint="Write your first letter to start sending formal correspondence."
        />
    </div>
@endsection

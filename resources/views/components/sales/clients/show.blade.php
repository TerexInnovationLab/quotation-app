@extends('layout.app')

@section('title','Client Profile')
@section('breadcrumb','Sales / Clients')
@section('page_title', $row['company_name'])

@section('primary_action')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.clients.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            Back to List
        </a>
        <a href="{{ route('sales.clients.edit', $row['code']) }}"
           class="inline-flex items-center px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
            Edit Client
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-4 max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-sm font-semibold">Client Profile</div>
                <div class="mt-4 space-y-3 text-sm">
                    <div><span class="text-slate-500">Code:</span> <span class="font-medium">{{ $row['code'] }}</span></div>
                    <div><span class="text-slate-500">Company:</span> <span class="font-medium">{{ $row['company_name'] }}</span></div>
                    <div><span class="text-slate-500">Contact:</span> <span class="font-medium">{{ $row['contact_person'] }}</span></div>
                    <div><span class="text-slate-500">Email:</span> <span class="font-medium">{{ $row['email'] }}</span></div>
                    <div><span class="text-slate-500">Phone:</span> <span class="font-medium">{{ $row['phone'] }}</span></div>
                    <div><span class="text-slate-500">Status:</span> <span class="font-medium">{{ $row['status'] }}</span></div>
                    <div><span class="text-slate-500">Outstanding:</span> <span class="font-medium">{{ $row['billing']['currency'] }} {{ number_format($row['balance'], 0) }}</span></div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-sm font-semibold">Billing Profile</div>
                <div class="mt-4 space-y-3 text-sm">
                    <div><span class="text-slate-500">Currency:</span> <span class="font-medium">{{ $row['billing']['currency'] }}</span></div>
                    <div><span class="text-slate-500">Payment Terms:</span> <span class="font-medium">{{ $row['billing']['payment_terms'] }}</span></div>
                    <div><span class="text-slate-500">Tax ID:</span> <span class="font-medium">{{ $row['billing']['tax_id'] }}</span></div>
                    <div><span class="text-slate-500">Credit Limit:</span> <span class="font-medium">{{ number_format($row['billing']['credit_limit'], 0) }}</span></div>
                    <div><span class="text-slate-500">Billing Address:</span> <span class="font-medium">{{ $row['billing']['address'] }}</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <div class="text-sm font-semibold">Client History</div>
                <div class="text-xs text-slate-500">Recent quotations, invoices, and payments</div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Type</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Reference</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Note</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($row['history'] as $event)
                            <tr>
                                <td class="px-4 py-3">{{ $event['date'] }}</td>
                                <td class="px-4 py-3">{{ $event['type'] }}</td>
                                <td class="px-4 py-3">{{ $event['reference'] }}</td>
                                <td class="px-4 py-3">{{ $event['note'] }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($event['amount'], 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

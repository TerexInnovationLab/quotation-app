@extends('layout.app')

@section('title','New Product / Service')
@section('breadcrumb','Sales / Products & Services')
@section('page_title','New Product / Service')

@section('primary_action')
    <a href="{{ route('sales.products.index') }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
        Back to List
    </a>
@endsection

@section('content')
    <div class="max-w-4xl space-y-4">
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4">
                <div class="font-semibold mb-2">Please fix the following:</div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('sales.products.store') }}" class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Item Code *</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. PS-00083"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Type *</label>
                    <select name="type" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach(['Product', 'Service'] as $option)
                            <option value="{{ $option }}" @selected(old('type', 'Service') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-500">Unit *</label>
                    <select name="unit" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" @selected(old('unit', 'Item') === $unit)>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-500">Default Price *</label>
                    <input type="number" min="0" step="1" name="default_price" value="{{ old('default_price') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Tax Rate (%) *</label>
                    <input type="number" min="0" max="100" step="0.1" name="tax_rate" value="{{ old('tax_rate', '16.5') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Status *</label>
                    <select name="status" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach(['Active', 'Inactive'] as $option)
                            <option value="{{ $option }}" @selected(old('status', 'Active') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500">Description</label>
                    <textarea name="description" rows="4"
                              class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('sales.products.index') }}"
                   class="px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                    Save Item
                </button>
            </div>
        </form>
    </div>
@endsection

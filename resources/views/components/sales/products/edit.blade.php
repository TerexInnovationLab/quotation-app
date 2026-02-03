@extends('layout.app')

@section('title','Edit Product / Service')
@section('breadcrumb','Sales / Products & Services')
@section('page_title','Edit ' . $row['name'])

@section('primary_action')
    <a href="{{ route('sales.products.show', $row['code']) }}"
       class="inline-flex items-center px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
        Back to Item
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

        <form method="POST" action="{{ route('sales.products.update', $row['code']) }}" class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-slate-500">Item Code</label>
                    <input type="text" value="{{ $row['code'] }}" disabled
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-slate-600">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $row['name']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Type *</label>
                    <select name="type" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach(['Product', 'Service'] as $option)
                            <option value="{{ $option }}" @selected(old('type', $row['type']) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-500">Unit *</label>
                    <select name="unit" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" @selected(old('unit', $row['unit']) === $unit)>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-500">Default Price *</label>
                    <input type="number" min="0" step="1" name="default_price" value="{{ old('default_price', $row['default_price']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Tax Rate (%) *</label>
                    <input type="number" min="0" max="100" step="0.1" name="tax_rate" value="{{ old('tax_rate', $row['tax_rate']) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Status *</label>
                    <select name="status" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                        @foreach(['Active', 'Inactive'] as $option)
                            <option value="{{ $option }}" @selected(old('status', $row['status']) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500">Description</label>
                    <textarea name="description" rows="4"
                              class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">{{ old('description', $row['description']) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('sales.products.show', $row['code']) }}"
                   class="px-4 py-2 text-sm rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection

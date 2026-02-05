@extends('layout.app')

@section('title','Settings')
@section('breadcrumb','Sales')
@section('page_title','Settings')

@section('primary_action')
    <span class="text-xs text-slate-500">Manage your workspace configuration</span>
@endsection

@section('content')
    @php
        $tabs = [
            'profile' => 'Profile, Company & Logo',
            'template' => 'Template Settings',
            'theme' => 'Theme Settings',
            'preferences' => 'Other Settings',
        ];
    @endphp

    <div class="space-y-4 max-w-5xl">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl p-3 overflow-x-auto">
            <div class="flex items-center gap-2 min-w-max">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('sales.settings.index', ['tab' => $key]) }}"
                       class="px-3 py-2 text-sm rounded-xl border {{ $tab === $key ? 'bg-[#465FFF] text-white border-[#465FFF]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('sales.settings.update') }}" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
            @csrf
            <input type="hidden" name="tab" value="{{ $tab }}">

            @if($tab === 'profile')
                <div class="space-y-5">
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold text-slate-700">Profile Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500">Full Name</label>
                                <input type="text" name="full_name" value="{{ old('full_name', 'Team Admin') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Email</label>
                                <input type="email" name="email" value="{{ old('email', 'admin@example.com') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', '+265 99 000 0000') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Role</label>
                                <input type="text" name="role" value="{{ old('role', 'Administrator') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <h3 class="text-sm font-semibold text-slate-700">Company Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500">Company Name</label>
                                <input type="text" name="company_name" value="{{ old('company_name', 'AccountYanga Ltd') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Tax ID</label>
                                <input type="text" name="tax_id" value="{{ old('tax_id', 'TAX-000123') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Phone</label>
                                <input type="text" name="company_phone" value="{{ old('company_phone', '+265 88 000 0000') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Email</label>
                                <input type="email" name="company_email" value="{{ old('company_email', 'billing@accountyanga.com') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs text-slate-500">Address</label>
                                <textarea name="company_address" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">{{ old('company_address', 'Lilongwe, Malawi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <h3 class="text-sm font-semibold text-slate-700">Logo Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500">Upload Company Logo</label>
                                <input type="file" name="logo" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500">Logo Position</label>
                                <select name="logo_position" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                                    @foreach(['Left', 'Center', 'Right'] as $option)
                                        <option value="{{ $option }}" @selected(old('logo_position', 'Left') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2 text-xs text-slate-500">Accepted formats: PNG, JPG, SVG. Max: 2MB.</div>
                        </div>
                    </div>
                </div>
            @elseif($tab === 'template')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Default Invoice Template</label>
                        <select name="invoice_template" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach(['Classic', 'Modern', 'Compact'] as $option)
                                <option value="{{ $option }}" @selected(old('invoice_template', 'Modern') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Default Quotation Template</label>
                        <select name="quotation_template" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach(['Classic', 'Modern', 'Detailed'] as $option)
                                <option value="{{ $option }}" @selected(old('quotation_template', 'Detailed') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs text-slate-500">Footer Message</label>
                        <textarea name="footer_message" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">{{ old('footer_message', 'Thank you for your business.') }}</textarea>
                    </div>
                </div>
            @elseif($tab === 'theme')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Theme Mode</label>
                        <select name="theme_mode" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach(['light' => 'Light', 'dark' => 'Dark', 'system' => 'System'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('theme_mode', $appearance ?? 'system') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Primary Color</label>
                        <select name="primary_color" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach([
                                'indigo' => 'Indigo',
                                'emerald' => 'Emerald',
                                'rose' => 'Rose',
                                'amber' => 'Amber',
                                'sky' => 'Sky',
                                'slate' => 'Slate',
                            ] as $value => $label)
                                <option value="{{ $value }}" @selected(old('primary_color', $primaryColor ?? 'indigo') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @elseif($tab === 'preferences')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-500">Default Currency</label>
                        <select name="default_currency" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                            @foreach(['MWK', 'USD', 'ZAR', 'EUR', 'GBP'] as $option)
                                <option value="{{ $option }}" @selected(old('default_currency', 'MWK') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Default VAT (%)</label>
                        <input type="number" name="default_vat" min="0" max="100" step="0.1" value="{{ old('default_vat', '16.5') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Invoice Number Prefix</label>
                        <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', 'INV-') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Payment Number Prefix</label>
                        <input type="text" name="payment_prefix" value="{{ old('payment_prefix', 'PAY-') }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-200">
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-end">
                <button type="submit" class="px-4 py-2 text-sm rounded-xl bg-[#465FFF] text-white hover:bg-[#3d54e6]">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection

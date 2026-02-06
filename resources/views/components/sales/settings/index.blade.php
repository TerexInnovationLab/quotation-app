@extends('layout.app')

@section('title','Settings')
@section('breadcrumb','Sales')
@section('page_title','Settings')

@section('primary_action')
    <span class="text-xs text-slate-500 dark:text-slate-400">Manage your workspace configuration</span>
@endsection

@section('content')
    @php
        $tabs = [
            'profile' => 'Profile, Company & Logo',
            'template' => 'Template Settings',
            'theme' => 'Theme Settings',
            'preferences' => 'Other Settings',
        ];
        $inputClass = 'mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:border-slate-400 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:placeholder-slate-500 dark:focus:border-slate-500 dark:focus:ring-slate-700';
        $selectClass = 'mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-slate-400 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-500 dark:focus:ring-slate-700';
        $textareaClass = 'mt-1 w-full rounded-2xl border-slate-200 bg-white text-slate-900 focus:border-slate-400 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-500 dark:focus:ring-slate-700';
        $panelClass = 'rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900';
        $sectionEyebrow = 'text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500';
        $sectionTitle = 'mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100';
        $sectionDesc = 'text-xs text-slate-500 dark:text-slate-400';
        $settings = $settings ?? [];
        $profile = $settings['profile'] ?? [];
        $company = $settings['company'] ?? [];
        $branding = $settings['branding'] ?? [];
        $preferences = $settings['preferences'] ?? [];
    @endphp

    <div class="space-y-4 max-w-5xl">
        <div class="bg-white border border-slate-200 rounded-2xl p-3 overflow-x-auto dark:bg-slate-900 dark:border-slate-800">
            <div class="flex items-center gap-2 min-w-max">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('sales.settings.index', ['tab' => $key]) }}"
                       class="px-3 py-2 text-sm rounded-xl border {{ $tab === $key ? 'bg-[#465FFF] text-white border-[#465FFF] shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-200 dark:border-slate-700 dark:hover:bg-slate-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('sales.settings.update') }}" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4 dark:bg-slate-900 dark:border-slate-800">
            @csrf
            <input type="hidden" name="tab" value="{{ $tab }}">

            @if($tab === 'profile')
                <div class="space-y-5">
                    <div class="{{ $panelClass }}">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <div class="{{ $sectionEyebrow }}">Profile</div>
                                <div class="{{ $sectionTitle }}">Your workspace identity</div>
                                <div class="{{ $sectionDesc }}">Shown on dashboards, notifications, and signatures.</div>
                            </div>
                            <div class="h-11 w-11 rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300 grid place-items-center">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Full Name</label>
                                <input type="text" name="full_name" value="{{ old('full_name', $profile['full_name'] ?? 'Team Admin') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Email</label>
                                <input type="email" name="email" value="{{ old('email', $profile['email'] ?? 'admin@example.com') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $profile['phone'] ?? '+265 99 000 0000') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Role</label>
                                <input type="text" name="role" value="{{ old('role', $profile['role'] ?? 'Administrator') }}" class="{{ $inputClass }}">
                            </div>
                        </div>
                    </div>

                    <div class="{{ $panelClass }}">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <div class="{{ $sectionEyebrow }}">Company</div>
                                <div class="{{ $sectionTitle }}">Organization details</div>
                                <div class="{{ $sectionDesc }}">Used on invoices, letters, and receipts.</div>
                            </div>
                            <div class="h-11 w-11 rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300 grid place-items-center">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Company Name</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $company['name'] ?? 'AccountYanga Ltd') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Tax ID</label>
                                <input type="text" name="tax_id" value="{{ old('tax_id', $company['tax_id'] ?? 'TAX-000123') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Phone</label>
                                <input type="text" name="company_phone" value="{{ old('company_phone', $company['phone'] ?? '+265 88 000 0000') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Email</label>
                                <input type="email" name="company_email" value="{{ old('company_email', $company['email'] ?? 'billing@accountyanga.com') }}" class="{{ $inputClass }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs text-slate-500 dark:text-slate-400">Address</label>
                                <textarea name="company_address" rows="3" class="{{ $textareaClass }}">{{ old('company_address', $company['address'] ?? 'Lilongwe, Malawi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="{{ $panelClass }}">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <div class="{{ $sectionEyebrow }}">Branding</div>
                                <div class="{{ $sectionTitle }}">Logo & positioning</div>
                                <div class="{{ $sectionDesc }}">Upload your logo for a consistent document identity.</div>
                            </div>
                            <div class="h-11 w-11 rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300 grid place-items-center">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 10l5 5 5-5M12 15V3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Upload Company Logo</label>
                                <input type="file" name="logo" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Logo Position</label>
                                <select name="logo_position" class="{{ $selectClass }}">
                                    @foreach(['Left', 'Center', 'Right'] as $option)
                                        <option value="{{ $option }}" @selected(old('logo_position', $branding['logo_position'] ?? 'Left') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2 text-xs text-slate-500 dark:text-slate-400">Accepted formats: PNG, JPG, SVG. Max: 2MB.</div>
                        </div>
                    </div>
                </div>
            @elseif($tab === 'template')
                @php
                    $templateOptions = [
                        [
                            'id' => 1,
                            'label' => 'Template 1',
                            'tag' => 'Classic',
                            'desc' => 'Balanced layout with top accent and watermark.',
                            'accent' => '#465fff',
                            'accentSoft' => '#eef2ff',
                            'accentDark' => '#1e293b',
                            'layout' => 'classic',
                        ],
                        [
                            'id' => 2,
                            'label' => 'Template 2',
                            'tag' => 'Sidebar',
                            'desc' => 'Left accent rail with a clean header frame.',
                            'accent' => '#0f766e',
                            'accentSoft' => '#ecfdf5',
                            'accentDark' => '#115e59',
                            'layout' => 'sidebar',
                        ],
                        [
                            'id' => 3,
                            'label' => 'Template 3',
                            'tag' => 'Bold',
                            'desc' => 'High-contrast banner for decisive documents.',
                            'accent' => '#e11d48',
                            'accentSoft' => '#fff1f2',
                            'accentDark' => '#9f1239',
                            'layout' => 'band',
                        ],
                        [
                            'id' => 4,
                            'label' => 'Template 4',
                            'tag' => 'Minimal',
                            'desc' => 'Low-ink layout with subtle dividers.',
                            'accent' => '#d97706',
                            'accentSoft' => '#fffbeb',
                            'accentDark' => '#92400e',
                            'layout' => 'minimal',
                        ],
                        [
                            'id' => 5,
                            'label' => 'Template 5',
                            'tag' => 'Grid',
                            'desc' => 'Structured tables for detailed line items.',
                            'accent' => '#0284c7',
                            'accentSoft' => '#f0f9ff',
                            'accentDark' => '#0c4a6e',
                            'layout' => 'grid',
                        ],
                        [
                            'id' => 6,
                            'label' => 'Template 6',
                            'tag' => 'Framed',
                            'desc' => 'Bordered layout with a premium finish.',
                            'accent' => '#475569',
                            'accentSoft' => '#f8fafc',
                            'accentDark' => '#1f2937',
                            'layout' => 'frame',
                        ],
                    ];
                    $selectedInvoiceTemplate = old('invoice_template', request()->cookie('invoice_template', 'Template 2'));
                    $selectedQuotationTemplate = old('quotation_template', request()->cookie('quotation_template', 'Template 3'));
                    $selectedLetterTemplate = old('letter_template', request()->cookie('letter_template', 'Template 1'));
                    $selectedReceiptTemplate = old('receipt_template', request()->cookie('receipt_template', 'Template 4'));
                    $footerMessage = old('footer_message', request()->cookie('footer_message', 'Thank you for your business.'));
                    $findAccent = function (string $label) use ($templateOptions) {
                        foreach ($templateOptions as $option) {
                            if ($option['label'] === $label) {
                                return $option['accent'];
                            }
                        }
                        return '#465fff';
                    };
                    $normalizeHex = function (?string $value): ?string {
                        $value = strtolower(trim((string) $value));
                        if ($value === '') {
                            return null;
                        }
                        $value = ltrim($value, '#');
                        if (!preg_match('/^[0-9a-f]{6}$/', $value)) {
                            return null;
                        }
                        return '#' . $value;
                    };
                    $toRgb = function (string $hex): array {
                        $hex = ltrim($hex, '#');
                        return [
                            hexdec(substr($hex, 0, 2)),
                            hexdec(substr($hex, 2, 2)),
                            hexdec(substr($hex, 4, 2)),
                        ];
                    };
                    $mixColor = function (string $hex, string $target, float $ratio) use ($toRgb): string {
                        [$r, $g, $b] = $toRgb($hex);
                        [$tr, $tg, $tb] = $toRgb($target);
                        $r = (int) round($r * (1 - $ratio) + $tr * $ratio);
                        $g = (int) round($g * (1 - $ratio) + $tg * $ratio);
                        $b = (int) round($b * (1 - $ratio) + $tb * $ratio);
                        return sprintf('#%02x%02x%02x', $r, $g, $b);
                    };
                    $invoiceTemplateColor = $normalizeHex(old('invoice_template_color', request()->cookie('invoice_template_color', '')))
                        ?: $findAccent($selectedInvoiceTemplate);
                    $quotationTemplateColor = $normalizeHex(old('quotation_template_color', request()->cookie('quotation_template_color', '')))
                        ?: $findAccent($selectedQuotationTemplate);
                    $letterTemplateColor = $normalizeHex(old('letter_template_color', request()->cookie('letter_template_color', '')))
                        ?: $findAccent($selectedLetterTemplate);
                    $receiptTemplateColor = $normalizeHex(old('receipt_template_color', request()->cookie('receipt_template_color', '')))
                        ?: $findAccent($selectedReceiptTemplate);
                    $hasInvoiceColor = (string) request()->cookie('invoice_template_color', '') !== '';
                    $hasQuotationColor = (string) request()->cookie('quotation_template_color', '') !== '';
                    $hasLetterColor = (string) request()->cookie('letter_template_color', '') !== '';
                    $hasReceiptColor = (string) request()->cookie('receipt_template_color', '') !== '';
                    $documentGroups = [
                        [
                            'title' => 'Invoices',
                            'label' => 'Invoice',
                            'name' => 'invoice_template',
                            'selected' => $selectedInvoiceTemplate,
                            'subtitle' => 'Applied to invoice PDFs and exports.',
                            'color' => $invoiceTemplateColor,
                            'color_name' => 'invoice_template_color',
                            'has_custom_color' => $hasInvoiceColor,
                        ],
                        [
                            'title' => 'Quotations',
                            'label' => 'Quotation',
                            'name' => 'quotation_template',
                            'selected' => $selectedQuotationTemplate,
                            'subtitle' => 'Applied to quotation PDFs and exports.',
                            'color' => $quotationTemplateColor,
                            'color_name' => 'quotation_template_color',
                            'has_custom_color' => $hasQuotationColor,
                        ],
                        [
                            'title' => 'Letters',
                            'label' => 'Letter',
                            'name' => 'letter_template',
                            'selected' => $selectedLetterTemplate,
                            'subtitle' => 'Applied to letter PDFs and exports.',
                            'color' => $letterTemplateColor,
                            'color_name' => 'letter_template_color',
                            'has_custom_color' => $hasLetterColor,
                        ],
                        [
                            'title' => 'Receipts',
                            'label' => 'Receipt',
                            'name' => 'receipt_template',
                            'selected' => $selectedReceiptTemplate,
                            'subtitle' => 'Applied to receipt PDFs and exports.',
                            'color' => $receiptTemplateColor,
                            'color_name' => 'receipt_template_color',
                            'has_custom_color' => $hasReceiptColor,
                        ],
                    ];
                @endphp
                <style>
                    .template-card {
                        border: 1px solid #e2e8f0;
                        border-radius: 18px;
                        padding: 14px;
                        background: #ffffff;
                        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }

                    .template-card:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.1);
                    }

                    .template-preview {
                        position: relative;
                        height: 118px;
                        border-radius: 14px;
                        border: 1px solid #e2e8f0;
                        background: #ffffff;
                        overflow: hidden;
                    }

                    .template-preview .tp-top {
                        height: 6px;
                        background: var(--accent);
                    }

                    .template-preview .tp-left {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 6px;
                        height: 100%;
                        background: var(--accent);
                        opacity: 0;
                    }

                    .template-preview .tp-content {
                        padding: 8px 10px;
                    }

                    .template-preview .tp-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 6px;
                    }

                    .template-preview .tp-logo {
                        width: 14px;
                        height: 14px;
                        border-radius: 4px;
                        background: var(--accent);
                    }

                    .template-preview .tp-title {
                        flex: 1;
                        height: 6px;
                        background: #e2e8f0;
                        border-radius: 999px;
                    }

                    .template-preview .tp-sub {
                        margin-top: 4px;
                        height: 4px;
                        width: 60%;
                        background: #e2e8f0;
                        border-radius: 999px;
                    }

                    .template-preview .tp-table {
                        margin-top: 8px;
                        display: grid;
                        gap: 4px;
                    }

                    .template-preview .tp-row {
                        height: 4px;
                        background: #e2e8f0;
                        border-radius: 999px;
                    }

                    .template-preview .tp-total {
                        margin-top: 6px;
                        height: 6px;
                        width: 40%;
                        background: var(--accent-soft);
                        border-radius: 999px;
                        margin-left: auto;
                    }

                    .layout-sidebar .tp-left {
                        opacity: 1;
                    }

                    .layout-sidebar .tp-top {
                        height: 3px;
                    }

                    .layout-band .tp-top {
                        height: 16px;
                    }

                    .layout-band .tp-content {
                        padding-top: 6px;
                    }

                    .layout-minimal .tp-top {
                        height: 2px;
                    }

                    .layout-minimal .tp-row {
                        background: #cbd5e1;
                    }

                    .layout-grid .tp-row {
                        height: 6px;
                        border: 1px solid #e2e8f0;
                        background: transparent;
                        border-radius: 4px;
                    }

                    .layout-frame {
                        box-shadow: inset 0 0 0 1px #cbd5e1;
                    }

                    .layout-frame .tp-top {
                        height: 8px;
                    }

                    .dark .template-card {
                        background: #0f172a;
                        border-color: #1f2937;
                        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.35);
                    }

                    .dark .template-card:hover {
                        box-shadow: 0 18px 30px rgba(0, 0, 0, 0.45);
                    }

                    .dark .template-preview {
                        background: #0b1220;
                        border-color: #1f2937;
                    }

                    .dark .template-preview .tp-title,
                    .dark .template-preview .tp-sub,
                    .dark .template-preview .tp-row {
                        background: #334155;
                    }

                    .dark .layout-grid .tp-row {
                        border-color: #334155;
                    }
                </style>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 p-6 text-white shadow-xl dark:border-slate-800 dark:from-slate-950 dark:via-slate-900 dark:to-slate-800">
                        <div class="text-xs uppercase tracking-[0.3em] text-white/60">Template Studio</div>
                        <div class="mt-3 text-2xl font-semibold">Design your document identity.</div>
                        <p class="mt-2 max-w-2xl text-sm text-white/70">
                            Choose a template for every document type. Your selection updates the PDF layout instantly.
                        </p>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-xs">6 Templates</div>
                            <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-xs">4 Document Types</div>
                            <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-xs">Instant PDF Sync</div>
                        </div>
                    </div>

                    @foreach($documentGroups as $doc)
                        <div class="{{ $panelClass }}">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500">{{ $doc['title'] }}</div>
                                    <div class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">{{ $doc['title'] }} Templates</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $doc['subtitle'] }}</div>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="rounded-full bg-slate-100 px-4 py-2 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        Selected: <span class="font-semibold text-slate-900 dark:text-white">{{ $doc['selected'] }}</span>
                                    </div>
                                    <label class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        <span class="h-3 w-3 rounded-full border border-white" style="background: {{ $doc['color'] }}"></span>
                                        <span>Accent</span>
                                        <input type="color"
                                               name="{{ $doc['color_name'] }}"
                                               value="{{ $doc['color'] }}"
                                               class="h-6 w-8 cursor-pointer rounded border border-slate-200 bg-white p-0 dark:border-slate-700 dark:bg-slate-900">
                                        <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ strtoupper($doc['color']) }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($templateOptions as $option)
                                    @php
                                        $isSelected = $doc['selected'] === $option['label'];
                                        $customAccent = $doc['has_custom_color']
                                            ? ($normalizeHex($doc['color']) ?? $option['accent'])
                                            : $option['accent'];
                                        $customAccentSoft = $doc['has_custom_color']
                                            ? $mixColor($customAccent, '#ffffff', 0.88)
                                            : $option['accentSoft'];
                                        $customAccentDark = $doc['has_custom_color']
                                            ? $mixColor($customAccent, '#000000', 0.35)
                                            : $option['accentDark'];
                                    @endphp
                                    <label class="group relative cursor-pointer">
                                        <input type="radio" name="{{ $doc['name'] }}" value="{{ $option['label'] }}"
                                               class="peer sr-only" @checked($doc['selected'] === $option['label'])>
                                        <div class="template-card peer-checked:border-[#465FFF] peer-checked:ring-2 peer-checked:ring-[#465FFF]/20">
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $option['label'] }}</div>
                                                <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                                      style="background: {{ $customAccentSoft }}; color: {{ $customAccentDark }};">
                                                    {{ $option['tag'] }}
                                                </span>
                                            </div>
                                            <div class="mt-3 template-preview layout-{{ $option['layout'] }}"
                                                 style="--accent: {{ $customAccent }}; --accent-soft: {{ $customAccentSoft }}; --accent-dark: {{ $customAccentDark }};">
                                                <div class="tp-top"></div>
                                                <div class="tp-left"></div>
                                                <div class="tp-content">
                                                    <div class="tp-header">
                                                        <div class="tp-logo"></div>
                                                        <div class="tp-title"></div>
                                                    </div>
                                                    <div class="tp-sub"></div>
                                                    <div class="tp-table">
                                                        <div class="tp-row"></div>
                                                        <div class="tp-row"></div>
                                                        <div class="tp-row"></div>
                                                    </div>
                                                    <div class="tp-total"></div>
                                                </div>
                                            </div>
                                            <div class="mt-3 text-[11px] text-slate-500 dark:text-slate-400">{{ $option['desc'] }}</div>
                                        </div>
                                        <span class="pointer-events-none absolute right-3 top-3 rounded-full bg-[#465FFF] px-2 py-0.5 text-[10px] font-semibold text-white opacity-0 transition peer-checked:opacity-100">
                                            Selected
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="{{ $panelClass }}">
                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-100">Footer Message</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Shown on your generated PDFs across all templates.</div>
                        <textarea name="footer_message" rows="3" class="{{ $textareaClass }} mt-3">{{ $footerMessage }}</textarea>
                    </div>
                </div>
            @elseif($tab === 'theme')
                <div class="space-y-5">
                    <div class="{{ $panelClass }}">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <div class="{{ $sectionEyebrow }}">Appearance</div>
                                <div class="{{ $sectionTitle }}">Theme & brand color</div>
                                <div class="{{ $sectionDesc }}">Control light/dark mode and the primary accent color.</div>
                            </div>
                            <div class="h-11 w-11 rounded-2xl bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300 grid place-items-center">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 3v2M12 19v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M3 12h2M19 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 lg:grid-cols-[1.2fr,1fr] gap-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-slate-500 dark:text-slate-400">Theme Mode</label>
                                    <select name="theme_mode" class="{{ $selectClass }}">
                                        @foreach(['light' => 'Light', 'dark' => 'Dark', 'system' => 'System'] as $value => $label)
                                            <option value="{{ $value }}" @selected(old('theme_mode', $appearance ?? 'system') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 dark:text-slate-400">Primary Color</label>
                                    <select name="primary_color" class="{{ $selectClass }}">
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

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Preview</div>
                                <div class="mt-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                    <div class="text-sm font-semibold text-slate-800 dark:text-slate-100">Workspace Accent</div>
                                    <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">Buttons and highlights</div>
                                    <div class="mt-4 flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-[var(--app-primary)]"></div>
                                        <button type="button" class="rounded-xl bg-[var(--app-primary)] px-4 py-2 text-xs font-semibold text-white">
                                            Primary Action
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tab === 'preferences')
                <div class="space-y-5">
                    <div class="{{ $panelClass }}">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <div class="{{ $sectionEyebrow }}">Defaults</div>
                                <div class="{{ $sectionTitle }}">Business preferences</div>
                                <div class="{{ $sectionDesc }}">Set default currency, VAT, and numbering.</div>
                            </div>
                            <div class="h-11 w-11 rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300 grid place-items-center">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M12 1v22M17 5H9a4 4 0 0 0 0 8h6a4 4 0 0 1 0 8H6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Default Currency</label>
                                <select name="default_currency" class="{{ $selectClass }}">
                                    @foreach(['MWK', 'USD', 'ZAR', 'EUR', 'GBP'] as $option)
                                        <option value="{{ $option }}" @selected(old('default_currency', $preferences['default_currency'] ?? 'MWK') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Default VAT (%)</label>
                                <input type="number" name="default_vat" min="0" max="100" step="0.1" value="{{ old('default_vat', $preferences['default_vat'] ?? '16.5') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Invoice Number Prefix</label>
                                <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', $preferences['invoice_prefix'] ?? 'INV-') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 dark:text-slate-400">Payment Number Prefix</label>
                                <input type="text" name="payment_prefix" value="{{ old('payment_prefix', $preferences['payment_prefix'] ?? 'PAY-') }}" class="{{ $inputClass }}">
                            </div>
                        </div>
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

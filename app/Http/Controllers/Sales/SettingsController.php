<?php

namespace App\Http\Controllers\Sales;

use App\Support\SalesSettings;
use Illuminate\Http\Request;

class SettingsController
{
    private array $tabs = ['profile', 'template', 'theme', 'preferences'];
    private array $legacyTabAliases = [
        'company' => 'profile',
        'logo' => 'profile',
    ];
    private array $themeModes = ['light', 'dark', 'system'];
    private array $primaryColors = ['indigo', 'emerald', 'rose', 'amber', 'sky', 'slate'];
    private array $templateOptions = [
        'Template 1',
        'Template 2',
        'Template 3',
        'Template 4',
        'Template 5',
        'Template 6',
    ];

    public function index(Request $request)
    {
        $tab = $this->normalizeTab((string) $request->query('tab', 'profile'));

        if (! in_array($tab, $this->tabs, true)) {
            $tab = 'profile';
        }

        $settings = SalesSettings::get();

        return view('components.sales.settings.index', compact('tab', 'settings'));
    }

    public function update(Request $request)
    {
        $validTabs = array_merge($this->tabs, array_keys($this->legacyTabAliases));

        $validated = $request->validate([
            'tab' => ['required', 'in:' . implode(',', $validTabs)],
        ]);

        $tab = $this->normalizeTab($validated['tab']);

        if ($tab === 'template') {
            $templates = $request->validate([
                'document_template' => ['required', 'in:' . implode(',', $this->templateOptions)],
                'document_template_color' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
                'footer_message' => ['nullable', 'string', 'max:1000'],
            ]);

            $normalizeHex = function (?string $value): string {
                $value = strtolower(trim((string) $value));
                if ($value === '') {
                    return '';
                }
                $value = ltrim($value, '#');
                if (strlen($value) !== 6) {
                    return '';
                }
                return '#' . $value;
            };

            return redirect()
                ->route('sales.settings.index', ['tab' => 'template'])
                ->withCookie(cookie()->forever('document_template', $templates['document_template']))
                ->withCookie(cookie()->forever('document_template_color', $normalizeHex($templates['document_template_color'] ?? '')))
                ->withCookie(cookie()->forever('footer_message', $templates['footer_message'] ?? ''))
                ->with('success', 'Template settings updated.');
        }

        if ($tab === 'theme') {
            $theme = $request->validate([
                'theme_mode' => ['required', 'in:' . implode(',', $this->themeModes)],
                'primary_color' => ['required', 'in:' . implode(',', $this->primaryColors)],
            ]);

            return redirect()
                ->route('sales.settings.index', ['tab' => 'theme'])
                ->withCookie(cookie()->forever('appearance', $theme['theme_mode']))
                ->withCookie(cookie()->forever('primary_color', $theme['primary_color']))
                ->with('success', 'Theme settings updated.');
        }

        if ($tab === 'profile') {
            $payload = $request->validate([
                'full_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:255'],
                'role' => ['nullable', 'string', 'max:255'],
                'company_name' => ['required', 'string', 'max:255'],
                'tax_id' => ['nullable', 'string', 'max:255'],
                'company_phone' => ['nullable', 'string', 'max:255'],
                'company_email' => ['nullable', 'email', 'max:255'],
                'company_address' => ['nullable', 'string', 'max:1000'],
                'logo_position' => ['nullable', 'in:Left,Center,Right'],
                'logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            ]);

            $settings = SalesSettings::get();
            $logoPath = (string) ($settings['branding']['logo_path'] ?? '');

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension() ?: 'png';
                $stored = $file->storeAs('public/company', 'company-logo.' . $extension);
                $logoPath = str_replace('public/', '', $stored);
            }

            SalesSettings::update([
                'profile' => [
                    'full_name' => $payload['full_name'],
                    'email' => $payload['email'],
                    'phone' => $payload['phone'] ?? '',
                    'role' => $payload['role'] ?? '',
                ],
                'company' => [
                    'name' => $payload['company_name'],
                    'tax_id' => $payload['tax_id'] ?? '',
                    'phone' => $payload['company_phone'] ?? '',
                    'email' => $payload['company_email'] ?? '',
                    'address' => $payload['company_address'] ?? '',
                ],
                'branding' => [
                    'logo_path' => $logoPath,
                    'logo_position' => $payload['logo_position'] ?? 'Left',
                ],
            ]);

            return redirect()
                ->route('sales.settings.index', ['tab' => 'profile'])
                ->with('success', 'Profile settings updated.');
        }

        if ($tab === 'preferences') {
            $prefs = $request->validate([
                'default_currency' => ['required', 'string', 'in:MWK,USD,ZAR,EUR,GBP'],
                'default_vat' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'invoice_prefix' => ['nullable', 'string', 'max:20'],
                'payment_prefix' => ['nullable', 'string', 'max:20'],
                'payment_airtel_enabled' => ['nullable'],
                'payment_airtel_label' => ['nullable', 'string', 'max:255'],
                'payment_airtel_account_name' => ['nullable', 'string', 'max:255'],
                'payment_airtel_account_number' => ['nullable', 'string', 'max:255'],
                'payment_airtel_reference' => ['nullable', 'string', 'max:255'],
                'payment_airtel_logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
                'payment_mpamba_enabled' => ['nullable'],
                'payment_mpamba_label' => ['nullable', 'string', 'max:255'],
                'payment_mpamba_account_name' => ['nullable', 'string', 'max:255'],
                'payment_mpamba_account_number' => ['nullable', 'string', 'max:255'],
                'payment_mpamba_reference' => ['nullable', 'string', 'max:255'],
                'payment_mpamba_logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
                'payment_bank_enabled' => ['nullable'],
                'payment_bank_label' => ['nullable', 'string', 'max:255'],
                'payment_bank_name' => ['nullable', 'string', 'max:255'],
                'payment_bank_account_name' => ['nullable', 'string', 'max:255'],
                'payment_bank_account_number' => ['nullable', 'string', 'max:255'],
                'payment_bank_branch' => ['nullable', 'string', 'max:255'],
                'payment_bank_swift' => ['nullable', 'string', 'max:255'],
                'payment_bank_logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            ]);

            $settings = SalesSettings::get();
            $payments = $settings['payments'] ?? [];

            $airtelLogoPath = (string) ($payments['airtel_money']['logo_path'] ?? '');
            if ($request->hasFile('payment_airtel_logo')) {
                $file = $request->file('payment_airtel_logo');
                $extension = $file->getClientOriginalExtension() ?: 'png';
                $stored = $file->storeAs('public/payments', 'airtel-money.' . $extension);
                $airtelLogoPath = str_replace('public/', '', $stored);
            }

            $mpambaLogoPath = (string) ($payments['mpamba']['logo_path'] ?? '');
            if ($request->hasFile('payment_mpamba_logo')) {
                $file = $request->file('payment_mpamba_logo');
                $extension = $file->getClientOriginalExtension() ?: 'png';
                $stored = $file->storeAs('public/payments', 'mpamba.' . $extension);
                $mpambaLogoPath = str_replace('public/', '', $stored);
            }

            $bankLogoPath = (string) ($payments['bank']['logo_path'] ?? '');
            if ($request->hasFile('payment_bank_logo')) {
                $file = $request->file('payment_bank_logo');
                $extension = $file->getClientOriginalExtension() ?: 'png';
                $stored = $file->storeAs('public/payments', 'bank-transfer.' . $extension);
                $bankLogoPath = str_replace('public/', '', $stored);
            }

            $airtelLabel = trim((string) ($prefs['payment_airtel_label'] ?? ''));
            if ($airtelLabel === '') {
                $airtelLabel = 'Airtel Money';
            }
            $mpambaLabel = trim((string) ($prefs['payment_mpamba_label'] ?? ''));
            if ($mpambaLabel === '') {
                $mpambaLabel = 'TNM Mpamba';
            }
            $bankLabel = trim((string) ($prefs['payment_bank_label'] ?? ''));
            if ($bankLabel === '') {
                $bankLabel = 'Bank Transfer';
            }

            SalesSettings::update([
                'preferences' => [
                    'default_currency' => $prefs['default_currency'],
                    'default_vat' => $prefs['default_vat'] ?? 0,
                    'invoice_prefix' => $prefs['invoice_prefix'] ?? '',
                    'payment_prefix' => $prefs['payment_prefix'] ?? '',
                ],
                'payments' => [
                    'airtel_money' => [
                        'enabled' => $request->boolean('payment_airtel_enabled'),
                        'label' => $airtelLabel,
                        'account_name' => trim((string) ($prefs['payment_airtel_account_name'] ?? '')),
                        'account_number' => trim((string) ($prefs['payment_airtel_account_number'] ?? '')),
                        'reference' => trim((string) ($prefs['payment_airtel_reference'] ?? '')),
                        'logo_path' => $airtelLogoPath,
                    ],
                    'mpamba' => [
                        'enabled' => $request->boolean('payment_mpamba_enabled'),
                        'label' => $mpambaLabel,
                        'account_name' => trim((string) ($prefs['payment_mpamba_account_name'] ?? '')),
                        'account_number' => trim((string) ($prefs['payment_mpamba_account_number'] ?? '')),
                        'reference' => trim((string) ($prefs['payment_mpamba_reference'] ?? '')),
                        'logo_path' => $mpambaLogoPath,
                    ],
                    'bank' => [
                        'enabled' => $request->boolean('payment_bank_enabled'),
                        'label' => $bankLabel,
                        'bank_name' => trim((string) ($prefs['payment_bank_name'] ?? '')),
                        'account_name' => trim((string) ($prefs['payment_bank_account_name'] ?? '')),
                        'account_number' => trim((string) ($prefs['payment_bank_account_number'] ?? '')),
                        'branch' => trim((string) ($prefs['payment_bank_branch'] ?? '')),
                        'swift' => trim((string) ($prefs['payment_bank_swift'] ?? '')),
                        'logo_path' => $bankLogoPath,
                    ],
                ],
            ]);

            return redirect()
                ->route('sales.settings.index', ['tab' => 'preferences'])
                ->with('success', 'Preference settings updated.');
        }

        return redirect()
            ->route('sales.settings.index', ['tab' => $tab])
            ->with('success', 'Settings updated successfully (UI only).');
    }

    private function normalizeTab(string $tab): string
    {
        return $this->legacyTabAliases[$tab] ?? $tab;
    }
}

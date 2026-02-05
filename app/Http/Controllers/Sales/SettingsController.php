<?php

namespace App\Http\Controllers\Sales;

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

        return view('components.sales.settings.index', compact('tab'));
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
                'invoice_template' => ['required', 'in:' . implode(',', $this->templateOptions)],
                'quotation_template' => ['required', 'in:' . implode(',', $this->templateOptions)],
                'letter_template' => ['required', 'in:' . implode(',', $this->templateOptions)],
                'receipt_template' => ['required', 'in:' . implode(',', $this->templateOptions)],
                'invoice_template_color' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
                'quotation_template_color' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
                'letter_template_color' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
                'receipt_template_color' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
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
                ->withCookie(cookie()->forever('invoice_template', $templates['invoice_template']))
                ->withCookie(cookie()->forever('quotation_template', $templates['quotation_template']))
                ->withCookie(cookie()->forever('letter_template', $templates['letter_template']))
                ->withCookie(cookie()->forever('receipt_template', $templates['receipt_template']))
                ->withCookie(cookie()->forever('invoice_template_color', $normalizeHex($templates['invoice_template_color'] ?? '')))
                ->withCookie(cookie()->forever('quotation_template_color', $normalizeHex($templates['quotation_template_color'] ?? '')))
                ->withCookie(cookie()->forever('letter_template_color', $normalizeHex($templates['letter_template_color'] ?? '')))
                ->withCookie(cookie()->forever('receipt_template_color', $normalizeHex($templates['receipt_template_color'] ?? '')))
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

        return redirect()
            ->route('sales.settings.index', ['tab' => $tab])
            ->with('success', 'Settings updated successfully (UI only).');
    }

    private function normalizeTab(string $tab): string
    {
        return $this->legacyTabAliases[$tab] ?? $tab;
    }
}

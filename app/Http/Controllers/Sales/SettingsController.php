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

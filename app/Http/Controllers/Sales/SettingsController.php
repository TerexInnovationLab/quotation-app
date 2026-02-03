<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class SettingsController
{
    private array $tabs = ['profile', 'template', 'company', 'logo', 'theme', 'preferences'];
    private array $themeModes = ['light', 'dark', 'system'];
    private array $primaryColors = ['indigo', 'emerald', 'rose', 'amber', 'sky', 'slate'];

    public function index(Request $request)
    {
        $tab = (string) $request->query('tab', 'profile');

        if (! in_array($tab, $this->tabs, true)) {
            $tab = 'profile';
        }

        return view('components.sales.settings.index', compact('tab'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'tab' => ['required', 'in:' . implode(',', $this->tabs)],
        ]);

        if ($validated['tab'] === 'theme') {
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
            ->route('sales.settings.index', ['tab' => $validated['tab']])
            ->with('success', 'Settings updated successfully (UI only).');
    }
}

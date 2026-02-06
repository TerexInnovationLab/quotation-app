<?php

namespace App\Support;

use Illuminate\Support\Arr;

class SalesSettings
{
    public static function defaults(): array
    {
        return [
            'profile' => [
                'full_name' => 'Richard Chilipa',
                'email' => 'richard@terexlab.com',
                'phone' => '+265 999 852 222',
                'role' => 'CEO',
            ],
            'company' => [
                'name' => 'Terex Innovation Lab',
                'tax_id' => 'TAX-000123',
                'phone' => '+265 999 852 222',
                'email' => 'info@terexlab.com',
                'address' => 'Blantyre, Malawi',
                'tagline' => 'Innovating for Malawi Digital Economy',
            ],
            'branding' => [
                'logo_path' => '',
                'logo_position' => 'Left',
            ],
            'preferences' => [
                'default_currency' => 'MWK',
                'default_vat' => 17.5,
                'invoice_prefix' => 'INV-',
                'payment_prefix' => 'PAY-',
            ],
        ];
    }

    public static function get(): array
    {
        $defaults = self::defaults();
        $path = self::path();

        if (! is_file($path)) {
            return $defaults;
        }

        $raw = json_decode((string) file_get_contents($path), true);

        if (! is_array($raw)) {
            return $defaults;
        }

        return array_replace_recursive($defaults, $raw);
    }

    public static function update(array $payload): array
    {
        $settings = array_replace_recursive(self::get(), $payload);
        $path = self::path();

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT));

        return $settings;
    }

    public static function value(string $key, mixed $default = null): mixed
    {
        return Arr::get(self::get(), $key, $default);
    }

    public static function logoStoragePath(): ?string
    {
        $logoPath = trim((string) self::value('branding.logo_path', ''));

        if ($logoPath === '') {
            return null;
        }

        return storage_path('app/public/' . ltrim($logoPath, '/'));
    }

    public static function logoDataUri(array $fallbackPaths = []): ?string
    {
        $paths = [];
        $stored = self::logoStoragePath();

        if ($stored) {
            $paths[] = $stored;
        }

        $paths = array_merge($paths, $fallbackPaths);

        return self::firstDataUri($paths);
    }

    public static function defaultVat(): float
    {
        $value = (float) self::value('preferences.default_vat', 16.5);

        return min(max($value, 0), 100);
    }

    public static function defaultCurrency(): string
    {
        return (string) self::value('preferences.default_currency', 'MWK');
    }

    public static function addressLines(?string $address): array
    {
        if (! $address) {
            return [];
        }

        $parts = preg_split('/\\r\\n|\\r|\\n/', $address) ?: [];
        $lines = [];

        foreach ($parts as $part) {
            $chunk = trim($part);
            if ($chunk === '') {
                continue;
            }
            $lines[] = $chunk;
        }

        if (empty($lines)) {
            $lines = array_filter(array_map('trim', explode(',', $address)));
        }

        return $lines;
    }

    private static function path(): string
    {
        return storage_path('app/sales_settings.json');
    }

    private static function firstDataUri(array $paths): ?string
    {
        foreach ($paths as $path) {
            if (! $path || ! is_file($path)) {
                continue;
            }

            $mime = mime_content_type($path) ?: 'image/png';
            $contents = file_get_contents($path);

            if ($contents === false) {
                continue;
            }

            return 'data:' . $mime . ';base64,' . base64_encode($contents);
        }

        return null;
    }
}

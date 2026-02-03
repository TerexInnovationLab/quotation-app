<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;

class ProductServiceController
{
    private function productRows(): \Illuminate\Support\Collection
    {
        return collect([
            [
                'code' => 'PS-00081',
                'name' => 'Website Maintenance',
                'type' => 'Service',
                'unit' => 'Month',
                'default_price' => 350000,
                'tax_rate' => 16.5,
                'status' => 'Active',
                'description' => 'Monthly maintenance and support retainer.',
            ],
            [
                'code' => 'PS-00082',
                'name' => 'Invoice Paper Pack',
                'type' => 'Product',
                'unit' => 'Pack',
                'default_price' => 85000,
                'tax_rate' => 16.5,
                'status' => 'Active',
                'description' => 'Printable invoice paper pack (100 sheets).',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $rows = $this->productRows()->map(function (array $row) {
            return [
                'code' => $row['code'],
                'name' => $row['name'],
                'type' => $row['type'],
                'unit' => $row['unit'],
                'tax_rate' => $row['tax_rate'] . '%',
                'amount' => $row['default_price'],
                'status' => $row['status'],
                'view_url' => route('sales.products.show', $row['code']),
                'edit_url' => route('sales.products.edit', $row['code']),
            ];
        });

        return view('components.sales.products.index', compact('rows'));
    }

    public function create()
    {
        $units = ['Item', 'Hour', 'Day', 'Week', 'Month', 'Kg', 'Litre', 'Pack'];

        return view('components.sales.products.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Product,Service'],
            'unit' => ['required', 'string', 'max:255'],
            'default_price' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:Active,Inactive'],
            'description' => ['nullable', 'string'],
        ]);

        return redirect()
            ->route('sales.products.index')
            ->with('success', 'Product/Service created successfully (UI only).');
    }

    public function show(string $product)
    {
        $row = $this->findProductOrFail($product);

        return view('components.sales.products.show', compact('row'));
    }

    public function edit(string $product)
    {
        $row = $this->findProductOrFail($product);
        $units = ['Item', 'Hour', 'Day', 'Week', 'Month', 'Kg', 'Litre', 'Pack'];

        return view('components.sales.products.edit', compact('row', 'units'));
    }

    public function update(Request $request, string $product)
    {
        $row = $this->findProductOrFail($product);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Product,Service'],
            'unit' => ['required', 'string', 'max:255'],
            'default_price' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:Active,Inactive'],
            'description' => ['nullable', 'string'],
        ]);

        return redirect()
            ->route('sales.products.show', $row['code'])
            ->with('success', 'Product/Service updated successfully (UI only).');
    }

    private function findProductOrFail(string $productCode): array
    {
        $product = $this->productRows()->firstWhere('code', $productCode);

        abort_unless($product, 404);

        return $product;
    }
}

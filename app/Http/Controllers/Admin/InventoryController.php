<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        $query = Product::with('category');

        // Filters
        if ($request->filled('status')) {
            if ($request->status === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock', '=', 0);
            } elseif ($request->status === 'low_stock') {
                $query->where('stock', '<', 5)->where('stock', '>', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $products = $query->paginate($perPage);

        return view('admin.inventory.manage', compact('products'));
    }

    public function edit(Product $product)
    {
        // Load stock movements for history (latest first)
        $product->load(['stockMovements' => function ($q) {
            $q->latest();
        }]);

        return view('admin.inventory.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'note'  => 'nullable|string|max:255',
        ]);

        $oldStock = $product->stock;
        $newStock = (int) $request->stock;

        if ($oldStock != $newStock) {
            $product->update(['stock' => $newStock]);

            StockMovement::create([
                'product_id' => $product->id,
                'old_stock'  => $oldStock,
                'new_stock'  => $newStock,
                'change'     => $newStock - $oldStock,
                'note'       => $request->note ?: 'Manual update',
            ]);
        }
        // 🔔 Low stock warning
        if ($newStock === 1) {
            session()->flash('warning', "⚠️ {$product->name} has only 1 item left in stock!");
        }

        return redirect()
            ->route('inventory.index')
            ->with('message', 'Stock updated successfully.');
    }

    public function bulkEdit()
    {
        $products = Product::paginate(20);

        return view('admin.inventory.bulk-edit', compact('products'));
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'stocks'   => 'required|array',
        ]);

        foreach ($request->products as $productId) {
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }

            $oldStock = $product->stock;
            $newStock = (int) $request->stocks[$productId];

            if ($oldStock != $newStock) {
                $product->update(['stock' => $newStock]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'old_stock'  => $oldStock,
                    'new_stock'  => $newStock,
                    'change'     => $newStock - $oldStock,
                    'note'       => 'Bulk update',
                ]);
            }
            // 🔔 Low stock warning
            if ($newStock === 1) {
                session()->flash('warning', "⚠️ {$product->name} has only 1 item left in stock!");
            }
        }

        return redirect()
            ->route('inventory.index')
            ->with('message', 'Bulk stock update completed.');
    }
}

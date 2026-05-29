<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Alerta de Stock Bajo.
     */
    public function lowStockAlert()
    {
        $threshold = 10; // Podría ser configurable
        $products = Product::where('stock', '<=', $threshold)
            ->with('category:id,name')
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'data' => $products
        ]);
    }

    /**
     * Valorización del Inventario actual.
     */
    public function inventoryValuation()
    {
        $valuation = Product::select(
            DB::raw('SUM(stock) as total_items'),
            DB::raw('SUM(stock * price) as total_value')
        )->first();

        return response()->json([
            'success' => true,
            'data' => $valuation
        ]);
    }

    /**
     * Actualización masiva de precios por categoría.
     */
    public function bulkPriceUpdate(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'percentage' => 'required|numeric', // ej: 10 para aumentar 10%, -5 para descontar 5%
        ]);

        $percentage = $request->percentage / 100;

        $affected = Product::where('category_id', $request->category_id)
            ->update([
                'price' => DB::raw("price * (1 + $percentage)")
            ]);

        return response()->json([
            'success' => true,
            'message' => "Se actualizaron los precios de $affected productos.",
            'affected_rows' => $affected
        ]);
    }
}

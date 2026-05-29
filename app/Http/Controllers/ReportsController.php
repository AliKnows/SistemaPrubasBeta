<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Resumen de ventas diario, semanal o mensual.
     */
    public function salesSummary(Request $request)
    {
        $period = $request->input('period', 'day'); // day, week, month, year

        $query = Sale::select(
            DB::raw('SUM(total_amount) as total_revenue'),
            DB::raw('COUNT(*) as total_sales'),
            DB::raw('SUM(tax_amount) as total_tax'),
            DB::raw('SUM(discount_amount) as total_discounts')
        );

        if ($period == 'day') {
            $query->addSelect(DB::raw('DATE(created_at) as date'))->groupBy('date');
        } elseif ($period == 'month') {
            $query->addSelect(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date"))->groupBy('date');
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('date', 'desc')->get()
        ]);
    }

    /**
     * Ventas por categoría (Productos más vendidos).
     */
    public function salesByCategory()
    {
        $stats = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Mejores clientes (Por volumen de compra).
     */
    public function topClients()
    {
        $clients = Sale::select(
                'client_id',
                DB::raw('SUM(total_amount) as total_spent'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->with('client:id,first_name,last_name')
            ->groupBy('client_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }
}

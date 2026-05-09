<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Listar historial de ventas con trazabilidad completa.
     */
    public function index()
    {
        $sales = Sale::with(['user:id,name', 'client:id,nombre,apellido_paterno', 'items.product:id,nombre'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sales
        ]);
    }

    /**
     * Registrar una nueva venta.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $saleItemsData = [];

                foreach ($request->items as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    // Validar Stock
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para el producto: {$product->nombre}");
                    }

                    $subtotal = $product->precio * $item['quantity'];
                    $totalAmount += $subtotal;

                    $saleItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->precio,
                        'subtotal' => $subtotal,
                    ];

                    // Descontar stock
                    $product->decrement('stock', $item['quantity']);
                }

                // Crear la Venta (Encabezado)
                $sale = Sale::create([
                    'user_id' => Auth::id(), // Trazabilidad del colaborador autenticado
                    'client_id' => $request->client_id,
                    'total_amount' => $totalAmount,
                ]);

                // Crear los Detalles
                foreach ($saleItemsData as $itemData) {
                    $itemData['sale_id'] = $sale->id;
                    SaleItem::create($itemData);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Venta registrada exitosamente',
                    'data' => $sale->load('items')
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Ver detalle de una venta específica.
     */
    public function show($id)
    {
        try {
            $sale = Sale::with(['user', 'client', 'items.product'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $sale
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }
    }

    /**
     * Anular una venta y devolver los productos al inventario.
     */
    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $sale = Sale::with('items')->findOrFail($id);

                // Restaurar stock de cada producto
                foreach ($sale->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }

                $sale->delete(); // Elimina la venta y sus items por cascada en la DB

                return response()->json([
                    'success' => true,
                    'message' => 'Venta anulada y stock restaurado correctamente'
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al anular la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba el reporte de ventas por categoría vía JSON.
     */
    public function test_can_get_sales_by_category_report(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::create(['name' => 'Electrónica']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1000,
            'name' => 'Laptop'
        ]);
        $client = Client::factory()->create();

        // Crear una venta
        $sale = Sale::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'subtotal' => 1000,
            'total_amount' => 1000
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 1000,
            'subtotal' => 1000
        ]);

        $response = $this->getJson('/api/reports/by-category');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.category_name', 'Electrónica')
            ->assertJsonPath('data.0.total_revenue', "1000.00");
    }

    /**
     * Prueba la alerta de stock bajo.
     */
    public function test_can_get_low_stock_alerts(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Product::factory()->create(['name' => 'Producto Agotándose', 'stock' => 3]);
        Product::factory()->create(['name' => 'Producto con Stock', 'stock' => 50]);

        $response = $this->getJson('/api/inventory/low-stock');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Producto Agotándose');
    }
}

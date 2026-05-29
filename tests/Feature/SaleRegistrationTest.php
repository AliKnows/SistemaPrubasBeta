<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class SaleRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_register_a_sale_with_description(): void
    {
        // 1. Setup
        $seller = User::factory()->create(['name' => 'Vendedor de Prueba']);
        $client = Client::factory()->create(['first_name' => 'Cliente', 'last_name' => 'Prueba']);
        $product = Product::factory()->create([
            'name' => 'Producto 1',
            'price' => 100,
            'stock' => 10
        ]);

        Sanctum::actingAs($seller);

        // 2. Action
        $response = $this->postJson('/api/sales', [
            'client_id' => $client->id,
            'description' => 'Esta es una venta de prueba con descripción.',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ]);

        // 3. Assertion
        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.description', 'Esta es una venta de prueba con descripción.')
            ->assertJsonPath('data.total_amount', 200)
            ->assertJsonPath('data.user.name', 'Vendedor de Prueba')
            ->assertJsonPath('data.client.first_name', 'Cliente');

        $this->assertDatabaseHas('sales', [
            'user_id' => $seller->id,
            'client_id' => $client->id,
            'description' => 'Esta es una venta de prueba con descripción.',
            'total_amount' => 200.00,
            'subtotal' => 200.00
        ]);

        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 100.00,
            'subtotal' => 200.00
        ]);

        $this->assertEquals(8, $product->fresh()->stock);
    }
}

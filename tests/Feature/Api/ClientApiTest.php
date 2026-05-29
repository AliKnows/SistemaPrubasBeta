<?php

namespace Tests\Feature\Api;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba la creación de un cliente vía JSON.
     */
    public function test_can_create_client_via_json_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $clientData = [
            'first_name' => 'Carlos',
            'last_name' => 'Sánchez',
            'second_last_name' => 'Ruiz',
            'document_number' => '77665544',
            'phone_number' => '999888777',
            'email' => 'carlos.sanchez@example.com',
            'address' => 'Calle Falsa 123',
            'birth_date' => '1985-10-20'
        ];

        $response = $this->postJson('/api/clients', $clientData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'document_number',
                    'email',
                    'created_at'
                ],
                'message' => []
            ])
            ->assertJsonPath('data.first_name', 'Carlos')
            ->assertJsonPath('data.document_number', '77665544');

        $this->assertDatabaseHas('clients', [
            'document_number' => '77665544',
            'email' => 'carlos.sanchez@example.com'
        ]);
    }

    /**
     * Prueba la validación de duplicados (Documento/Email) vía JSON.
     */
    public function test_cannot_create_client_with_duplicate_document(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Client::factory()->create(['document_number' => '12345678']);

        $response = $this->postJson('/api/clients', [
            'first_name' => 'Doble',
            'last_name' => 'Prueba',
            'document_number' => '12345678', // Duplicado
            'email' => 'nuevo@example.com'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['document_number']);
    }

    /**
     * Prueba obtener la lista de clientes.
     */
    public function test_can_list_clients_via_json_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['success', 'data', 'message']);
    }
}

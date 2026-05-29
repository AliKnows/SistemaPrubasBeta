<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\SaleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Vendedor de Prueba
        User::updateOrCreate(
            ['email' => 'vendedor@test.com'],
            [
                'name' => 'Vendedor Demo',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Crear Métodos de Pago
        PaymentMethod::firstOrCreate(['code' => 'cash'], ['name' => 'Efectivo']);
        PaymentMethod::firstOrCreate(['code' => 'credit_card'], ['name' => 'Tarjeta de Crédito']);
        PaymentMethod::firstOrCreate(['code' => 'transfer'], ['name' => 'Transferencia Bancaria']);

        // 3. Crear Estados de Venta
        SaleStatus::firstOrCreate(['name' => 'Pendiente'], ['color_hex' => '#FFA500']);
        SaleStatus::firstOrCreate(['name' => 'Pagada'], ['color_hex' => '#008000']);
        SaleStatus::firstOrCreate(['name' => 'Anulada'], ['color_hex' => '#FF0000']);

        // 4. Crear Cliente de Prueba
        Client::updateOrCreate(
            ['email' => 'juan.perez@test.com'],
            [
                'first_name' => 'Juan',
                'last_name' => 'Perez',
                'second_last_name' => 'Gomez',
                'document_number' => '12345678',
                'phone_number' => '987654321',
                'address' => 'Av. Siempre Viva 123',
                'birth_date' => '1990-05-15',
            ]
        );

        // 5. Crear Lista de Productos de Prueba
        $products = [
            [
                'name' => 'Laptop Gamer Pro',
                'description' => 'Procesador i9, 32GB RAM, 1TB SSD',
                'price' => 1500.00,
                'stock' => 15,
            ],
            [
                'name' => 'Mouse Ergonómico Inalámbrico',
                'description' => 'Sensor óptico de alta precisión',
                'price' => 45.50,
                'stock' => 50,
            ],
            [
                'name' => 'Teclado Mecánico RGB',
                'description' => 'Switches Blue, retroiluminado',
                'price' => 89.90,
                'stock' => 30,
            ],
            [
                'name' => 'Monitor 27" 4K',
                'description' => 'Panel IPS, 144Hz, HDR',
                'price' => 350.00,
                'stock' => 10,
            ],
            [
                'name' => 'Auriculares con Cancelación de Ruido',
                'description' => 'Bluetooth 5.0, 40h de batería',
                'price' => 120.00,
                'stock' => 25,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }
    }
}

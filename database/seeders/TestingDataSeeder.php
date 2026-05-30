<?php

namespace Database\Seeders;

use App\Models\Category;
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
        // 1. Crear Categorías
        $catElectronics = Category::firstOrCreate(['name' => 'Electrónica'], ['description' => 'Dispositivos y gadgets electrónicos']);
        $catPeripherals = Category::firstOrCreate(['name' => 'Periféricos'], ['description' => 'Accesorios para computadoras']);

        // 2. Crear Vendedor de Prueba
        User::updateOrCreate(
            ['email' => 'vendedor@test.com'],
            [
                'name' => 'Vendedor Demo',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Crear Métodos de Pago
        PaymentMethod::firstOrCreate(['code' => 'cash'], ['name' => 'Efectivo']);
        PaymentMethod::firstOrCreate(['code' => 'credit_card'], ['name' => 'Tarjeta de Crédito']);
        PaymentMethod::firstOrCreate(['code' => 'transfer'], ['name' => 'Transferencia Bancaria']);

        // 4. Crear Estados de Venta
        SaleStatus::firstOrCreate(['name' => 'Pendiente'], ['color_hex' => '#FFA500']);
        SaleStatus::firstOrCreate(['name' => 'Pagada'], ['color_hex' => '#008000']);
        SaleStatus::firstOrCreate(['name' => 'Anulada'], ['color_hex' => '#FF0000']);

        // 5. Crear Cliente de Prueba
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

        // 6. Crear Lista de Productos de Prueba
        $products = [
            [
                'name' => 'Laptop Gamer Pro',
                'description' => 'Procesador i9, 32GB RAM, 1TB SSD',
                'price' => 1500.00,
                'stock' => 15,
                'category_id' => $catElectronics->id,
            ],
            [
                'name' => 'Mouse Ergonómico Inalámbrico',
                'description' => 'Sensor óptico de alta precisión',
                'price' => 45.50,
                'stock' => 50,
                'category_id' => $catPeripherals->id,
            ],
            [
                'name' => 'Teclado Mecánico RGB',
                'description' => 'Switches Blue, retroiluminado',
                'price' => 89.90,
                'stock' => 30,
                'category_id' => $catPeripherals->id,
            ],
            [
                'name' => 'Monitor 27" 4K',
                'description' => 'Panel IPS, 144Hz, HDR',
                'price' => 350.00,
                'stock' => 10,
                'category_id' => $catElectronics->id,
            ],
            [
                'name' => 'Auriculares con Cancelación de Ruido',
                'description' => 'Bluetooth 5.0, 40h de batería',
                'price' => 120.00,
                'stock' => 25,
                'category_id' => $catElectronics->id,
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // para generar 50 registros con el factory
        Client::factory()->count(30)->create();
    //para que el seeder corra siempre con los demas en el futuro, registrado en el archivo maestro
   /* $this ->call([
        ClientSeeder::class,
    ]);
    */
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'columna_bd' => fake()->metodoGenerador(),
            'nombre' => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'documento' => fake()->unique()->numerify('########'), // Genera 8 números (DNI)
            'numero_telefonico' => fake()->phoneNumber(),
            'correo_electronico' => fake()->unique()->safeEmail(),
            'direccion' => fake()->address(),
            'fecha_nacimiento' => fake()->date('Y-m-d', '2005-01-01'), // Fechas hasta 2005
        ];
    }
}
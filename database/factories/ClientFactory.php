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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'second_last_name' => fake()->lastName(),
            'document_number' => fake()->unique()->numerify('########'),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'birth_date' => fake()->date('Y-m-d', '2005-01-01'),
        ];
    }
}

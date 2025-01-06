<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(), // Random product name
            'description' => $this->faker->sentence(), // Random description
            'price' => $this->faker->randomFloat(2, 1, 1000), // Random price between 1 and 1000
            'quantity' => $this->faker->numberBetween(1, 100), // Random quantity between 1 and 100
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceDetails>
 */
class InvoiceDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(), // Creates a related invoice
            'product_id' => Product::factory(), // Creates a related product
            'quantity' => $this->faker->numberBetween(24, 100),
            'unit_price' => $this->faker->randomFloat(2, 2, 4),
            'subtotal' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['unit_price'];
            },
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_no' => $this->faker->unique()->numerify('INV-#####'),
            'client_id' => Client::factory(), // Creates a related customer
            'store_id' => Store::factory(), // Creates a related customer
            'total' => $this->faker->randomFloat(2, 100, 1000),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'net_total' => function (array $attributes) {
                return $attributes['total'] - $attributes['discount'];
            },
            'invoice_date' => $this->faker->date(),
//            'pieces_no' => function (array $attributes) {
//                return $attributes['']
//            }
        ];
    }
}

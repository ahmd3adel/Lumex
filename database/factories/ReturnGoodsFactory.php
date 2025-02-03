<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReturnGoods>
 */
class ReturnGoodsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'return_no'   => 'RET-' . $this->faker->unique()->numberBetween(1000, 9999),
            'client_id'   => Client::inRandomOrder()->first()->id ?? Client::factory(),
            'store_id'    => Store::inRandomOrder()->first()->id ?? Store::factory(),
            'total'       => $this->faker->randomFloat(2, 50, 5000),
            'discount'    => $this->faker->randomFloat(2, 0, 500),
            'net_total'   => function (array $attributes) {
                return $attributes['total'] - $attributes['discount'];
            },
            'notes'       => $this->faker->sentence(),
            'return_date' => $this->faker->date(),
            'pieces_no'   => $this->faker->numberBetween(1, 100),
            'created_by'  => User::inRandomOrder()->first()->id ?? User::factory(),
            'updated_by'  => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReceiptVoucher>
 */
class ReceiptVoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'voucher_no' => 'RV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'client_id'  => Client::inRandomOrder()->first()->id ?? Client::factory(),
            'store_id'  => Store::inRandomOrder()->first()->id ?? Store::factory(),
            'amount'     => $this->faker->randomFloat(2, 50, 5000),
            'payment_method' => $this->faker->randomElement(['cash', 'bank', 'credit card']),
            'notes' => $this->faker->sentence(),
            'receipt_date' => $this->faker->date(),
            'created_by' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}

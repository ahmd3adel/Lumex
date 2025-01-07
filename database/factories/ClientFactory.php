<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\User;
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
            'company_name' => $this->faker->company,
            'name' => $this->faker->name,
            'website' => $this->faker->optional()->url,
            'logo' => $this->faker->optional()->imageUrl(200, 200, 'business', true, 'Faker', true),
            'phone' => $this->faker->phoneNumber,
            'balance' => $this->faker->randomFloat(2, 0, 10000), // قيمة عشوائية بين 0 و 10000
            'last_login' => $this->faker->optional()->dateTimeBetween('-1 years', 'now'),
            'address' => $this->faker->address,
            'store_id' => Store::inRandomOrder()->first()->id,

        ];
    }
}

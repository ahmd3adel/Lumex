<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
                    Store::factory(20)->create();
                    User::factory(50)->create();
                    Client::factory(100)->create();
                    Product::factory(100)->create();
                    Product::factory(100)->create();
                    Profile::factory(100)->create();
//         \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([InitialSetupSeeder::class]);

    }
}

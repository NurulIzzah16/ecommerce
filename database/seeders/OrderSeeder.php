<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::insert([
            ['user_id' => 1, 'total_price' => 50000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Order::factory(5)->create();
    }
}

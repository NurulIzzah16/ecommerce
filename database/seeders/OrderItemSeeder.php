<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        OrderItem::insert([
            ['order_id' => 1, 'product_id' => 1, 'quantity' => 1, 'price' => 350000, 'created_at' => now(), 'updated_at' => now()],
            ['order_id' => 2, 'product_id' => 3, 'quantity' => 2, 'price' => 120000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        OrderItem::factory(5)->create();
    }
}

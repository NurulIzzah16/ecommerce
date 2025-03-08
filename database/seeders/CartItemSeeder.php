<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CartItem;

class CartItemSeeder extends Seeder
{
    public function run()
    {
        CartItem::insert([
            ['user_id' => 2, 'product_id' => 2, 'quantity' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'product_id' => 1, 'quantity' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        CartItem::factory(5)->create();
    }
}

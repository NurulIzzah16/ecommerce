<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            [
                'name' => 'Wooden Chair',
                'description' => 'A comfortable wooden chair with cushion.',
                'price' => 350000,
                'stock' => 15,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartphone XYZ',
                'description' => 'A high-end smartphone with great features.',
                'price' => 5500000,
                'stock' => 10,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'T-Shirt Cotton',
                'description' => 'Soft and comfortable cotton T-shirt.',
                'price' => 120000,
                'stock' => 50,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        Product::factory(7)->create();
    }
}

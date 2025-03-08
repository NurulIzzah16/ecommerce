<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        ProductImage::insert([
            ['product_id' => 1, 'image_url' => 'images/products/wooden-chair.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'image_url' => 'images/products/smartphone-xyz.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 3, 'image_url' => 'images/products/tshirt-cotton.jpg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        ProductImage::factory(10)->create();
    }
}

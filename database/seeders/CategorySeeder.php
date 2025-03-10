<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            [
                'name' => 'Furniture',
                'description' => 'Produk untuk perabotan rumah tangga.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Electronics',
                'description' => 'Perangkat elektronik dan gadget.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Clothing',
                'description' => 'Pakaian dan aksesoris fashion.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Toys',
                'description' => 'Mainan anak-anak dan koleksi.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kitchenware',
                'description' => 'Peralatan dapur dan memasak.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}

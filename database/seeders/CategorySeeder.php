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
            ['name' => 'Furniture', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electronics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Clothing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Toys', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kitchenware', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

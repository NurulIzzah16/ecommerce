<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductImage;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'image_url' => $this->faker->imageUrl(640, 480, 'products'),
        ];
    }
}


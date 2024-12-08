<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'menu_id' => 1,
            'price' => $this->faker->numberBetween(10000, 1000000),
            'price_sale' => $this->faker->numberBetween(10000, 1000000),
            'description' => $this->faker->text,
            'content' => $this->faker->paragraph,
            'thumb' => $this->faker->imageUrl(),
            'active' => 1,
            'quantity' => 100
        ];
    }
}

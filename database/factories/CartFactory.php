<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customers::factory(),
            'product_id' => \App\Models\Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $this->faker->numberBetween(50000, 1000000),
            'status' => 'pending',
            'created_at' => now()
        ];
    }
}
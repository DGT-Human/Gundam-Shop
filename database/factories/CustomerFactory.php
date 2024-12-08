<?php

namespace Database\Factories;

use App\Models\Customers;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customers::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'content' => $this->faker->paragraph, // Thêm trường content
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}

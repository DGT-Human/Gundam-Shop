<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['admin', 'user']),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'created_at' => now(),
        ];
    }

    /**
     * Indicate that the user is admin.
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin',
            ];
        });
    }

    /**
     * Indicate that the user is regular user.
     */
    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'user',
            ];
        });
    }
}


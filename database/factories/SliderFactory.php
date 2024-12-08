<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

class SliderFactory extends Factory
{
    protected $model = Slider::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'url' => $this->faker->url,
            'thumb' => $this->faker->imageUrl(800, 600),
            'sort_by' => $this->faker->numberBetween(1, 10),
            'active' => $this->faker->boolean,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}


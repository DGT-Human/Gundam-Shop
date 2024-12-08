<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->word,               // Tạo tên menu ngẫu nhiên
            'description' => $this->faker->sentence,           // Tạo mô tả ngẫu nhiên
            'parent_id'   => 0, // Hoặc tạo giá trị `Menu::factory()->create()->id`
            'content'     => $this->faker->paragraph,          // Tạo nội dung menu ngẫu nhiên
            'slug'        => $this->faker->slug,               // Tạo slug từ tên ngẫu nhiên
            'active'      => $this->faker->numberBetween(0, 1),// 0 hoặc 1
            'thumb'       => $this->faker->imageUrl(640, 480), // URL ảnh ngẫu nhiên
        ];
    }
}


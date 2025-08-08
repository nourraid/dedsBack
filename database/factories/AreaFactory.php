<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city(),          // اسم مدينة عشوائي
            'description' => $this->faker->sentence(),
            'map_image' => $this->faker->image(),
            'flag_image' => $this->faker->image()

        ];
    }
}

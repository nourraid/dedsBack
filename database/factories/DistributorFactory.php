<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distributor>
 */
class DistributorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
              return [
            'location' => $this->faker->city,
            'tests' => $this->faker->numberBetween(0, 1000),
            'user_id' =>User::factory() ,
            'license' => 'licenses/' . $this->faker->unique()->regexify('[A-Za-z0-9]{10}') . '.jpg',
        ];
    }
}

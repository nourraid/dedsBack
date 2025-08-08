<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\AiTest;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'ai_test_id' => AiTest::factory(), // تأكد عندك AiTest Factory
            'datetime' => $this->faker->date(),
            'note' => $this->faker->sentence,
            'provider_id' => Provider::factory(), // تأكد عندك Provider Factory
        ];
    }
}

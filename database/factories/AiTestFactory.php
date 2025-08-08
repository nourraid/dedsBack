<?php
namespace Database\Factories;

use App\Models\AiTest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AiTestFactory extends Factory
{
    protected $model = AiTest::class;

    public function definition()
    {
        $results = ['positive', 'negative', 'needs_review'];

        return [
            'user_id' => User::factory(), // لإنشاء مستخدم جديد مرتبط
            'test_number' => $this->faker->unique()->numerify('TEST###'),
            'test_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'ai_result' => $this->faker->randomElement($results),
            'ai_details' => $this->faker->paragraph,
            'call' => $this->faker->boolean(20),        // 20% chance true
            'notification_message' => $this->faker->randomElement(["Your test result is ready, please check.","Follow-up is recommended based on your test." ,"Please contact your health center immediately."]),
            'notification' => $this->faker->boolean(30),
            'chat' => $this->faker->boolean(10),
        ];
    }
}

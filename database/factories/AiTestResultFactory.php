<?php

namespace Database\Factories;

use AiTestResult as GlobalAiTestResult;
use App\Models\AiTest;
use App\Models\AiTestResult;
use Illuminate\Database\Eloquent\Factories\Factory;

class AiTestResultFactory extends Factory
{
    protected $model = AiTestResult::class;

    public function definition()
    {
        $details = [
            ["name" => "Urobilinogen", "standard" => "3.2-16"],
            ["name" => "Bilirubin", "standard" => "Negative"],
            ["name" => "Ketone", "standard" => "Negative"],
            ["name" => "creatinine", "standard" => ">0.9"],
            ["name" => "Blood", "standard" => "Negative"],
            ["name" => "Protien", "standard" => "Negative"],
            ["name" => "Micro Albumin", "standard" => ">10.0"],
            ["name" => "Nitrite", "standard" => "Negative"],
            ["name" => "Leukocytes", "standard" => "Negative"],
            ["name" => "Glucose", "standard" => "Negative"],
            ["name" => "Specific Gravity", "standard" => "1000"],
            ["name" => "PH", "standard" => "7.0"],
            ["name" => "Ascorbate", "standard" => "0"],
            ["name" => "Calcium", "standard" => "0-3.0"],
        ];

        $detail = $this->faker->randomElement($details);

        return [
            'ai_test_id' => AiTest::inRandomOrder()->first()->id ?? 1,
            'name' => $detail['name'],
            'standard' => $detail['standard'],
            'value' => $this->faker->randomFloat(2, 0, 20),
        ];
    }
}

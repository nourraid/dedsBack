<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiTest;
use App\Models\AiTestResult;

class AiTestResultSeeder extends Seeder
{
    public function run()
    {
        AiTest::all()->each(function ($aiTest) {
            AiTestResult::factory()->count(10)->create([
                'ai_test_id' => $aiTest->id,
            ]);
        });
    }
}

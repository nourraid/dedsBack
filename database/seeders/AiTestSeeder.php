<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiTest;
use App\Models\User;

class AiTestSeeder extends Seeder
{
    public function run()
    {
        // أنشئ 10 مستخدمين، وكل واحد له 3 فحوصات AI
        User::factory(10)->create()->each(function ($user) {
            AiTest::factory(3)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AiTestDetail;
use App\Models\AiTestResult;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    $this->call([
        AreaSeeder::class,
        UserSeeder::class,
        AiTestSeeder::class,
        AiTestResultSeeder::class,
        ProviderSeeder::class,
        DistributorSeeder::class,
        AppointmentSeeder::class,
                LanguageSeeder::class,
                    PrivacySettingSeeder::class,

                    DataSettingsSeeder::class,


    ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
    }
}

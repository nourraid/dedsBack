<?php

namespace Database\Seeders;

use App\Models\PrivacySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivacySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        PrivacySetting::create([
            'privacy_policy' => 'هذا النص يمثل سياسة الخصوصية الافتراضية.',
            'can_download_reports' => true,
        ]);
    }
}

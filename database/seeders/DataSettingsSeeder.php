<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataSetting;

class DataSettingsSeeder extends Seeder
{
    public function run()
    {
        DataSetting::firstOrCreate([], [
            'allow_data_sharing' => false,
            'data_retention_days' => 365,
            'allow_account_deletion' => true,
        ]);
    }
}


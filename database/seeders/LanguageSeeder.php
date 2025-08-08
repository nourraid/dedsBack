<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
             Language::truncate();

        $languages = [
            ['code' => 'en', 'name' => 'English', 'direction' => 'LTR', 'is_active' => true, 'is_default' => true],
            ['code' => 'ar', 'name' => 'العربية', 'direction' => 'RTL', 'is_active' => true, 'is_default' => false],
            ['code' => 'fr', 'name' => 'Français', 'direction' => 'LTR', 'is_active' => true, 'is_default' => false],
            ['code' => 'es', 'name' => 'Español', 'direction' => 'LTR', 'is_active' => true, 'is_default' => false],
            ['code' => 'tr', 'name' => 'Türkçe', 'direction' => 'LTR', 'is_active' => true, 'is_default' => false],
        ];

        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}

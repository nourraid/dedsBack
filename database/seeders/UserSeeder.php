<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
  User::firstOrCreate(
    ['email' => 'admin@example.com'],
    [
        'name' => 'Admin',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'gender' => 'male',
        'age' => 25,
        'area_id' => 1,
        'phone' => '+972599000000',
        'dob' => '1990-01-01',
        'blood_type' => 'O+',
        'country' => 'Palestine',
        'city' => 'Gaza',
        'street' => 'Main Street',
        'building_number' => '12',
        'weight' => '70kg',
        'id_number' => '123456789',
    ]
);

        User::factory()->count(20)->create();


    }
}

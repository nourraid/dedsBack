<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

public function definition()
{
    return [
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'email_verified_at' => now(),
        'password' => bcrypt('password'), // كلمة سر ثابتة للتجربة
        'role' => $this->faker->randomElement(['user', 'admin', 'distributor', 'provider']),
        'gender' => $this->faker->randomElement(['female', 'male']),
        'age' => $this->faker->numberBetween(15, 90),
        'area_id' => Area::inRandomOrder()->first()?->id,

        // الحقول الجديدة
        'id_number' => $this->faker->unique()->numerify('#########'), // رقم هوية عشوائي مكون من 9 أرقام
        'phone' => $this->faker->phoneNumber(),
        'dob' => $this->faker->date('Y-m-d', '2005-01-01'),
        'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
        'country' => 'Palestine',
        'city' => $this->faker->city(),
        'street' => $this->faker->streetName(),
        'building_number' => $this->faker->buildingNumber(),
        'weight' => $this->faker->numberBetween(40, 120),
        'profile_picture' =>$this->faker->imageUrl(200, 200, 'people'), // أو مسار افتراضي لاحقًا

        'remember_token' => Str::random(10),
    ];
}

}

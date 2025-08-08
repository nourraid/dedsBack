<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory; // 👈 هذه مهمة جدًا

protected $fillable = [
    'ai_test_id',
    'datetime',
    'note',
    'provider_id',
];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(AiTest::class, 'test_id');
    }
}

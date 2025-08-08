<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AiTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_number',
        'test_date',
        'ai_result',
        'ai_details',
        'call',
        'notification',
        'chat',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
{
    return $this->hasMany(AiTestResult::class);
}
    public function aiTestResults()
    {
        return $this->hasMany(AiTestResult::class);
    }

    public function appointment()
{
    return $this->hasOne(Appointment::class);
}

}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;  // مهم جداً
use App\Models\AiTest;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
 'name', 'email', 'phone', 'role', 'password', 
    'gender', 'dob', 'blood_type', 'country', 'city', 'id_number' ,
    'street', 'building_number', 'weight', 'age', 'area_id', 'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


      public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function latestAiTest()
{
    return $this->hasOne(AiTest::class)->latestOfMany();
}

    public function area()
{
    return $this->belongsTo(Area::class, 'area_id');
}
public function aiTests()
{
    return $this->hasMany(AiTest::class, 'user_id', 'id');
}

    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

}



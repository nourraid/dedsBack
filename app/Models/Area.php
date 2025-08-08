<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

protected $fillable = ['name', 'description', 'map_image', 'flag_image'];

    // العلاقة مع users (واحد إلى كثير)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

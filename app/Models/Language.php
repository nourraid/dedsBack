<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
        protected $fillable = ['code', 'name', 'direction', 'is_active', 'is_default'];

}

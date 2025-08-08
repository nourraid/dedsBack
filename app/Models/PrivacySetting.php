<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivacySetting extends Model
{
    use HasFactory;
      protected $fillable = [
            'privacy_policy',
        'can_download_reports',

    ];
}

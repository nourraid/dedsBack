<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSetting extends Model
{
    use HasFactory;

        protected $fillable = [
            'allow_data_sharing',
        'data_retention_days',
                'allow_account_deletion',


    ];

}

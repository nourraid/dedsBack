<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiTestResult extends Model
{
    use HasFactory;

    protected $fillable = ['ai_test_id', 'name', 'standard', 'value'];

    public function aiTest()
    {
        return $this->belongsTo(AiTest::class);
    }
}

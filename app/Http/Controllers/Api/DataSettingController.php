<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DataSetting;
use Illuminate\Http\Request;

class DataSettingController extends Controller
{
      public function index()
    {
        $settings = DataSetting::first();
        return response()->json($settings);
    }

public function update(Request $request)
{
    $data = $request->validate([
        'allow_data_sharing' => 'nullable|boolean',
        'allow_account_deletion' => 'nullable|boolean',
        'data_retention_days' => 'nullable|integer',
    ]);

    $setting = DataSetting::first() ?? new DataSetting();

    // تحديث فقط الحقول الموجودة في الطلب
    foreach ($data as $key => $value) {
        $setting->$key = $value;
    }

    $setting->save();

    return response()->json([
        'message' => 'Data setting updated successfully.',
        'data' => $setting,
    ]);
}


}

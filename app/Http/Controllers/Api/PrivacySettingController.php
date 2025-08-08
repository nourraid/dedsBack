<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PrivacySetting;
use Illuminate\Http\Request;

class PrivacySettingController extends Controller
{
     public function index()
    {
        $setting = PrivacySetting::first();
        return response()->json($setting);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'privacy_policy' => 'nullable|string',
            'can_download_reports' => 'required|boolean',
        ]);

        $setting = PrivacySetting::first();
        if (!$setting) {
            $setting = PrivacySetting::create($data);
        } else {
            $setting->update($data);
        }

        return response()->json(['message' => 'Updated successfully', 'data' => $setting]);
    }
}

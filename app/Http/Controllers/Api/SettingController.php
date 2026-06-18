<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function show()
    {
        $setting = Setting::where('id', 1)->first();

        if (!$setting) {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => with_upload_urls($setting, ['logo', 'favicon', 'banner']),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = ['general', 'exam', 'freemium', 'ads', 'payment', 'ai', 'seo', 'forum'];
        $settings = [];

        foreach ($groups as $group) {
            $settings[$group] = SystemSetting::where('group', $group)->orderBy('id')->get();
        }

        return view('admin.settings', compact('settings', 'groups'));
    }

    public function update(Request $request, SettingsService $service)
    {
        $settingsInput = $request->input('settings', []);

        foreach ($settingsInput as $key => $value) {
            $service->set($key, $value);
        }

        $service->bustAllCache();

        return back()->with('success', 'Settings updated successfully.');
    }
}

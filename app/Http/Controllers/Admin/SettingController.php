<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'activeNav' => 'settings',
            'settings' => [
                'social_facebook' => Setting::get('social_facebook'),
                'social_twitter' => Setting::get('social_twitter'),
                'social_instagram' => Setting::get('social_instagram'),
                'social_youtube' => Setting::get('social_youtube'),
            ],
        ]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        foreach ($request->validated() as $key => $value) {
            Setting::set($key, $value ?: null);
        }

        return redirect()->route('admin.settings.index')->with('status', 'تم حفظ الإعدادات بنجاح');
    }
}

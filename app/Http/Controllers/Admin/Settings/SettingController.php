<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Cms\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * @var list<string>
     */
    private const KEYS = [
        'contact.recipient_email',
        'contact.phone',
        'contact.whatsapp',
        'seo.default_title',
        'seo.default_description',
        'seo.google_analytics_id',
    ];

    public function edit(): View
    {
        $settings = collect(self::KEYS)->mapWithKeys(fn (string $key) => [$key => Setting::get($key)]);

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'values' => ['array'],
            'values.*' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($validated['values'] ?? [] as $key => $value) {
            if (in_array($key, self::KEYS, true)) {
                Setting::set($key, $value, Str::before($key, '.'));
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Paramètres mis à jour.');
    }
}

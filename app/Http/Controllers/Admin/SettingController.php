<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** @var array<string,array<int,mixed>> */
    private array $rules = [
        // General
        'app_name' => ['nullable', 'string', 'max:60'],
        'app_tagline' => ['nullable', 'string', 'max:120'],
        'contact_email' => ['nullable', 'email', 'max:150'],
        'allow_registrations' => ['boolean'],
        'maintenance_mode' => ['boolean'],
        'maintenance_message' => ['nullable', 'string', 'max:300'],
        // SEO
        'seo_description' => ['nullable', 'string', 'max:160'],
        'seo_keywords' => ['nullable', 'string', 'max:300'],
        // Analytics
        'google_analytics_id' => ['nullable', 'string', 'max:50', 'regex:/^(G-[A-Z0-9]+|UA-[0-9]+-[0-9]+)?$/'],
        // Social
        'social_instagram' => ['nullable', 'url', 'max:200'],
        'social_twitter' => ['nullable', 'url', 'max:200'],
        'social_facebook' => ['nullable', 'url', 'max:200'],
        'social_strava' => ['nullable', 'url', 'max:200'],
        'social_youtube' => ['nullable', 'url', 'max:200'],
        // Events
        'events_per_page' => ['nullable', 'integer', 'min:6', 'max:100'],
        'featured_events_count' => ['nullable', 'integer', 'min:1', 'max:10'],
        'event_submissions_open' => ['boolean'],
        'feedback_widget_enabled' => ['boolean'],
        // Appearance
        'primary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    ];

    /** @var list<string> */
    private array $booleanKeys = [
        'allow_registrations',
        'maintenance_mode',
        'event_submissions_open',
        'feedback_widget_enabled',
    ];

    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules);

        foreach ($this->booleanKeys as $key) {
            $data[$key] = $request->boolean($key) ? '1' : '0';
        }

        foreach ($data as $key => $value) {
            Setting::set($key, \in_array($key, $this->booleanKeys) ? $value : ($value ?: null));
        }

        return redirect()->route('admin.settings')->with('success', 'Ajustes guardados.');
    }
}

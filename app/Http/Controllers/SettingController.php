<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Display the settings edit page.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $settings = Setting::all()->keyBy('key');
        
        // Ensure default values exist
        $defaults = [
            'website_name' => 'Flappy Challenge',
            'instagram_page_url' => 'https://www.instagram.com/YOUR_PAGE_USERNAME/',
            'website_description' => 'Play, score, compete, and win!',
            'competition_status' => 'active',
            'max_accepted_score' => '999999',
            'max_submissions_per_session' => '5',
            'competition_start_date' => '',
            'competition_end_date' => '',
            'num_leaderboard_entries' => '10',
            'prize_first' => 'First Place Prize',
            'prize_second' => 'Second Place Prize',
            'prize_third' => 'Third Place Prize',
            'tie_breaker' => 'earliest_submission',
            'allow_new_submissions' => 'true',
            'contact_information' => '',
            'competition_rules' => '',
        ];
        
        foreach ($defaults as $key => $value) {
            if (! $settings->has($key)) {
                Setting::setValue($key, $value, 'string');
            }
        }
        
        $settings = Setting::all()->keyBy('key');
        
        return view('admin.settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'website_name' => 'required|string|max:255',
            'instagram_page_url' => ['required', 'url', 'regex:https://www\\.instagram\\.com/([a-zA-Z0-9_]+)/?$'],
            'website_description' => 'required|string',
            'competition_status' => 'required|in:active, paused, finished',
            'max_accepted_score' => 'required|integer|min:0',
            'max_submissions_per_session' => 'required|integer|min:1',
            'competition_start_date' => 'nullable|date',
            'competition_end_date' => 'nullable|date',
            'num_leaderboard_entries' => 'required|integer|min:1',
            'prize_first' => 'required|string|max:255',
            'prize_second' => 'required|string|max:255',
            'prize_third' => 'required|string|max:255',
            'tie_breaker' => 'required|in:earliest_submission,random',
            'allow_new_submissions' => 'required|boolean',
            'contact_information' => 'nullable|string',
            'competition_rules' => 'nullable|string',
        ]);

        // Update or create settings
        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value, 'string');
        }

        // Log the action
        auth()->user()->adminAuditLogs()->create([
            'action' => 'update_settings',
            'target_record_id' => 0,
            'target_table' => 'settings',
            'before_state' => null,
            'after_state' => json_encode($validated),
        ]);

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Upload the favicon.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function faviconUpload(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:ico,png,jpeg,svg|max:2048',
        ]);

        $file = $request->file('favicon');
        $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/favicons', $filename);

        // Update the setting
        Setting::setValue('favicon_path', 'favicons/' . $filename, 'file');

        // Log the action
        auth()->user()->adminAuditLogs()->create([
            'action' => 'upload_favicon',
            'target_record_id' => 0,
            'target_table' => 'settings',
            'before_state' => null,
            'after_state' => json_encode(['favicon_path' => 'favicons/' . $filename]),
        ]);

        return back()->with('success', 'Favicon uploaded successfully.');
    }
}
@extends('admin._layout')

@section('title', 'Website Settings')

@section('content')
<div class="w-full">
    
    <!-- Header -->
    <header class="mb-6">
        <h1 class="text-2xl font-bold text-primary">Website Settings</h1>
        <p class="text-muted">Configure competition and website settings</p>
    </header>
    
    <!-- Settings Form -->
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        
        <!-- Website Name -->
        <div>
            <label class="block text-sm mb-1 text-muted">Website Name</label>
            <input type="text" name="website_name" class="form-input" value="{{ $settings->get('website_name', 'Flappy Challenge') }}" required>
        </div>
        
        <!-- Instagram Page URL -->
        <div>
            <label class="block text-sm mb-1 text-muted">Instagram Page URL</label>
            <input type="url" name="instagram_page_url" class="form-input" value="{{ $settings->get('instagram_page_url', 'https://www.instagram.com/YOUR_PAGE_USERNAME/') }}" required>
        </div>
        
        <!-- Favicon Upload -->
        <div>
            <label class="block text-sm mb-1 text-muted">Favicon</label>
            <p class="text-xs text-muted">Supported formats: ICO, PNG, SVG</p>
            <p class="text-xs text-muted">Max size: 2MB</p>
            <input type="file" name="favicon" class="form-input mt-2">
            @if ($settings->has('favicon_path'))
                <p class="text-xs text-green-400 mt-2">Current favicon: <kbd class="font-mono">{{ $settings->get('favicon_path') }}</kbd></p>
            @endif
        </div>
        
        <!-- Competition Status -->
        <div>
            <label class="block text-sm mb-1 text-muted">Competition Status</label>
            <select name="competition_status" class="form-input w-full">
                <option value="active" {{ request('competition_status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="paused" {{ request('competition_status') == 'paused' ? 'selected' : '' }}>Paused</option>
                <option value="finished" {{ request('competition_status') == 'finished' ? 'selected' : '' }}>Finished</option>
            </select>
        </div>
        
        <!-- Max Accepted Score -->
        <div>
            <label class="block text-sm mb-1 text-muted">Max Accepted Score</label>
            <input type="number" name="max_accepted_score" class="form-input" value="{{ $settings->get('max_accepted_score', '999999') }}" required>
        </div>
        
        <!-- Max Submissions Per Session -->
        <div>
            <label class="block text-sm mb-1 text-muted">Max Submissions Per Session</label>
            <input type="number" name="max_submissions_per_session" class="form-input" value="{{ $settings->get('max_submissions_per_session', '5') }}" required>
        </div>
        
        <!-- Competition Dates -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1 text-muted">Competition Start Date</label>
                <input type="date" name="competition_start_date" class="form-input" value="{{ $settings->get('competition_start_date') }}">
            </div>
            <div>
                <label class="block text-sm mb-1 text-muted">Competition End Date</label>
                <input type="date" name="competition_end_date" class="form-input" value="{{ $settings->get('competition_end_date') }}">
            </div>
        </div>
        
        <!-- Leaderboard Entries -->
        <div>
            <label class="block text-sm mb-1 text-muted">Number of Leaderboard Entries</label>
            <input type="number" name="num_leaderboard_entries" class="form-input" value="{{ $settings->get('num_leaderboard_entries', '10') }}" required>
        </div>
        
        <!-- Prizes -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1 text-muted">1st Place Prize</label>
                <input type="text" name="prize_first" class="form-input" value="{{ $settings->get('prize_first', 'First Place Prize') }}" required>
            </div>
            <div>
                <label class="block text-sm mb-1 text-muted">2nd Place Prize</label>
                <input type="text" name="prize_second" class="form-input" value="{{ $settings->get('prize_second', 'Second Place Prize') }}" required>
            </div>
            <div>
                <label class="block text-sm mb-1 text-muted">3rd Place Prize</label>
                <input type="text" name="prize_third" class="form-input" value="{{ $settings->get('prize_third', 'Third Place Prize') }}" required>
            </div>
        </div>
        
        <!-- Tie Breaker -->
        <div>
            <label class="block text-sm mb-1 text-muted">Tie-Breaking Rule</label>
            <select name="tie_breaker" class="form-input w-full">
                <option value="earliest_submission" {{ request('tie_breaker') == 'earliest_submission' ? 'selected' : '' }}>Earliest Submission</option>
                <option value="random" {{ request('tie_breaker') == 'random' ? 'selected' : '' }}>Random</option>
            </select>
        </div>
        
        <!-- General Settings -->
        <div>
            <label class="block text-sm mb-1 text-muted">Allow New Submissions</label>
            <div class="flex items-center">
                <input type="checkbox" name="allow_new_submissions" class="form-input" {{ $settings->get('allow_new_submissions', true) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-muted">Enable new score submissions</span>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div>
            <label class="block text-sm mb-1 text-muted">Contact Information</label>
            <textarea name="contact_information" class="form-input h-20">{{ $settings->get('contact_information') }}</textarea>
        </div>
        
        <!-- Competition Rules -->
        <div>
            <label class="block text-sm mb-1 text-muted">Competition Rules</label>
            <textarea name="competition_rules" class="form-input h-40">{{ $settings->get('competition_rules') }}</textarea>
        </div>
        
        <!-- Submit Button -->
        <div>
            <button type="submit" class="btn btn-primary w-full">Save Settings</button>
        </div>
    </form>
</div>
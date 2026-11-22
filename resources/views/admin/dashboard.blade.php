@extends('admin._layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="w-full">
    
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Admin Dashboard</h1>
        <p class="text-muted">Competition management platform</p>
    </header>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card p-4">
            <p class="text-muted small">Total Game Sessions</p>
            <p class="text-3xl font-bold Orbitron">{{ $totalSessions }}</p>
        </div>
        <div class="card p-4">
            <p class="text-muted small">Total Score Submissions</p>
            <p class="text-3xl font-bold Orbitron">{{ $totalSubmissions }}</p>
        </div>
        <div class="card p-4">
            <p class="text-muted small">Pending Verification</p>
            <p class="text-3xl font-bold text-warning">{{ $pendingSubmissions }}</p>
        </div>
        <div class="card p-4">
            <p class="text-muted small">Approved Submissions</p>
            <p class="text-3xl font-bold text-success">{{ $approvedSubmissions }}</p>
        </div>
    </div>
    
    <!-- Key Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card p-4">
            <p class="text-muted small">Unique Usernames</p>
            <p class="text-3xl font-bold Orbitron">{{ $uniqueUsernames }}</p>
        </div>
        <div class="card p-4">
            <p class="text-muted small">Instagram Clicks</p>
            <p class="text-3xl font-bold Orbitron">{{ $instagramClicks }}</p>
        </div>
        <div class="card p-4">
            <p class="text-muted small">Top Player</p>
            @if (count($topThree) > 0)
                <p class="text-3xl font-bold Orbitron" style="color: var(--primary);">{{ $topThree->first()->player->instagram_username }}</p>
            @else
                <p class="text-muted">No entries yet</p>
            @endif
        </div>
        <div class="card p-4">
            <p class="text-muted small">Competition Status</p>
            <span class="badge badge-{{ $competitionStatus === 'active' ? 'approved' : ($competitionStatus === 'paused' ? 'warning' : 'danger') }}">{{ ucfirst($competitionStatus) }}</span>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        @if (count($topThree) > 0)
        <div class="card p-4">
            <p class="text-muted small">Current 1st Place</p>
            <p class="text-xl font-bold Orbitron" style="color: var(--primary);">{{ $topThree->first()->player->display_name ?? $topThree->first()->player->instagram_username }}</p>
            <p class="text-sm text-muted">Score: {{ $topThree->first()->submitted_score }}</p>
        </div>
        @endif
        <div class="card p-4">
            <p class="text-muted small">Rejected Submissions</p>
            <p class="text-3xl font-bold text-danger">{{ $rejectedSubmissions }}</p>
        </div>
    </div>
    
    <!-- Recent Submissions Table -->
    <div class="card p-6">
        <h3 class="text-xl font-bold text-primary mb-4">Recent Submissions</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">#</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Instagram</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Score</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Submitted</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Status</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentSubmissions as $index => $submission)
                    <tr class="border-b transition-colors hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4"><kbd class="font-mono text-sm">{{ $submission->player->instagram_username }}</kbd></td>
                        <td class="py-3 px-4"><span class="font-semibold">{{ $submission->submitted_score }}</span></td>
                        <td class="py-3 px-4"><span class="text-sm text-muted">{{ $submission->submitted_at->diffForHumans() }}</span></td>
                        <td class="py-3 px-4">
                            <span class="badge badge-{{ $submission->verification_status }}">
                                {{ ucfirst($submission->verification_status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="#" class="text-primary text-sm hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
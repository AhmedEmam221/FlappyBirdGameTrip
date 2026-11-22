@extends('admin._layout')

@section('title', 'Analytics')

@section('content')
<div class="w-full">
    <header class="mb-6">
        <h1 class="text-2xl font-extrabold">Analytics</h1>
        <p class="text-muted">Competition statistics at a glance</p>
    </header>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="card p-4"><p class="text-muted text-sm">Game Sessions</p><p class="text-2xl font-extrabold">{{ $totalSessions }}</p></div>
        <div class="card p-4"><p class="text-muted text-sm">Submissions</p><p class="text-2xl font-extrabold">{{ $totalSubmissions }}</p></div>
        <div class="card p-4"><p class="text-muted text-sm">Approved</p><p class="text-2xl font-extrabold text-success">{{ $approvedSubmissions }}</p></div>
        <div class="card p-4"><p class="text-muted text-sm">Pending</p><p class="text-2xl font-extrabold text-warning">{{ $pendingSubmissions }}</p></div>
        <div class="card p-4"><p class="text-muted text-sm">Rejected</p><p class="text-2xl font-extrabold text-danger">{{ $rejectedSubmissions }}</p></div>
        <div class="card p-4"><p class="text-muted text-sm">Instagram Clicks</p><p class="text-2xl font-extrabold">{{ $instagramClicks }}</p></div>
    </div>

    <div class="card p-4 md:p-6">
        <h3 class="text-lg font-bold mb-4">Top 10 Scores</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left">Rank</th>
                        <th class="py-3 px-4 text-left">Instagram</th>
                        <th class="py-3 px-4 text-left">Score</th>
                        <th class="py-3 px-4 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topTen as $index => $entry)
                    <tr>
                        <td class="py-3 px-4 font-semibold">#{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ '@' . $entry->player->instagram_username }}</td>
                        <td class="py-3 px-4 font-bold">{{ $entry->submitted_score }}</td>
                        <td class="py-3 px-4 text-sm text-muted">{{ $entry->submitted_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-muted">No approved scores yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
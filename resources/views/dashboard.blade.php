@extends('layouts.app')

@section('title', 'Flappy Challenge - Live Dashboard')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Header -->
    <header class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Live Dashboard</h1>
        <p class="text-muted">All scores &mdash; approved and pending &mdash; updated live</p>
    </header>

    <!-- Game status + control -->
    <div class="card p-5 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            @if ($gameAvailable)
                <span class="inline-block w-3 h-3 rounded-full" style="background:#16A34A;"></span>
                <div>
                    <div class="font-bold" style="color:#16A34A;">Game Available</div>
                    <div class="text-xs text-muted">Players can play and submit scores.</div>
                </div>
            @else
                <span class="inline-block w-3 h-3 rounded-full" style="background:#DC2626;"></span>
                <div>
                    <div class="font-bold" style="color:#DC2626;">Game Not Available</div>
                    <div class="text-xs text-muted">The game is closed. No one can play or submit.</div>
                </div>
            @endif
        </div>
        <form method="POST" action="{{ route('dashboard.toggle-game') }}">
            @csrf
            @if ($gameAvailable)
                <button type="submit" class="btn btn-danger" onclick="return confirm('Close the game? No one will be able to play or submit scores.')">Close Game</button>
            @else
                <button type="submit" class="btn btn-primary" onclick="return confirm('Open the game and allow everyone to play again?')">Open Game</button>
            @endif
        </form>
    </div>

    @if (session('status'))
        <div class="mb-6 px-4 py-3 rounded-lg text-sm" style="background:#DBEAFE;color:#1E40AF;">{{ session('status') }}</div>
    @endif

    <!-- Top 3 -->
    @if ($submissions->count() > 0)
    <section class="mb-10">
        <h2 class="text-lg font-bold mb-4 text-center">Top 3</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            @foreach ($submissions->take(3) as $index => $entry)
                @php
                    $medals = ['#EAB308', '#9CA3AF', '#B45309'];
                    $order = ['md:order-2', 'md:order-1', 'md:order-3'];
                @endphp
                <div class="card p-5 text-center {{ $order[$index] }}" style="border-top:3px solid {{ $medals[$index] }};">
                    <div class="text-2xl font-extrabold mb-1" style="color:{{ $medals[$index] }};">#{{ $index + 1 }}</div>
                    <div class="font-semibold truncate" title="{{ e($entry->player->display_name ?? $entry->player->instagram_username) }}">
                        {{ $entry->player->display_name ?? $entry->player->instagram_username }}
                    </div>
                    <div class="text-sm text-muted truncate">{{ '@' . $entry->player->instagram_username }}</div>
                    <div class="text-xs mt-2"><span class="badge badge-warning">Bus {{ $entry->bus_number ?? '—' }}</span></div>
                    <div class="text-4xl font-extrabold mt-3">{{ $entry->submitted_score }}</div>
                    <div class="text-xs text-muted mt-1">points</div>
                    <div class="mt-2">
                        <span class="badge badge-{{ $entry->verification_status }}">{{ str_replace('_', ' ', ucfirst($entry->verification_status)) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Full table -->
    <section>
        <div class="card overflow-hidden">
            <div class="px-5 py-4" style="border-bottom:1px solid var(--border);">
                <h2 class="text-lg font-bold">All Players</h2>
            </div>
            @if ($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr style="background:var(--bg);">
                            <th class="py-3 px-5 text-left text-xs font-semibold uppercase tracking-wide text-muted">Rank</th>
                            <th class="py-3 px-5 text-left text-xs font-semibold uppercase tracking-wide text-muted">Instagram Username</th>
                            <th class="py-3 px-5 text-left text-xs font-semibold uppercase tracking-wide text-muted">Bus No.</th>
                            <th class="py-3 px-5 text-left text-xs font-semibold uppercase tracking-wide text-muted">Status</th>
                            <th class="py-3 px-5 text-right text-xs font-semibold uppercase tracking-wide text-muted">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $index => $entry)
                        <tr style="border-top:1px solid var(--border);">
                            <td class="py-3 px-5 font-semibold">
                                @if ($index < 3)
                                    <span style="color: {{ ['#EAB308', '#9CA3AF', '#B45309'][$index] }};">#{{ $index + 1 }}</span>
                                @else
                                    #{{ $index + 1 }}
                                @endif
                            </td>
                            <td class="py-3 px-5">
                                <a href="https://www.instagram.com/{{ e($entry->player->instagram_username) }}/" target="_blank" rel="noopener noreferrer" class="hover:underline">
                                    {{ '@' . $entry->player->instagram_username }}
                                </a>
                            </td>
                            <td class="py-3 px-5"><span class="badge badge-warning">{{ $entry->bus_number ?? '—' }}</span></td>
                            <td class="py-3 px-5">
                                <span class="badge badge-{{ $entry->verification_status }}">{{ str_replace('_', ' ', ucfirst($entry->verification_status)) }}</span>
                            </td>
                            <td class="py-3 px-5 text-right font-bold text-lg">{{ $entry->submitted_score }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-muted">No scores yet. Be the first to play!</p>
            </div>
            @endif
        </div>
    </section>

    <div class="mt-8 text-center flex flex-wrap items-center justify-center gap-4">
        <button onclick="location.reload()" class="btn btn-secondary">Refresh</button>
        <a href="/play" class="btn btn-primary">Play Now</a>
        <a href="/" class="text-primary hover:underline">Back to Home</a>
    </div>

</div>

@push('scripts')
<script>
    // Auto-refresh the public dashboard every 30 seconds
    setTimeout(function () { location.reload(); }, 30000);
</script>
@endpush
@endsection
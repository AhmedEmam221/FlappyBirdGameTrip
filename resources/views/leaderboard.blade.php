@extends('layouts.app')

@section('title', 'Flappy Challenge - Leaderboard')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <header class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Leaderboard</h1>
        <p class="text-muted">Top competitors competing for prizes</p>
    </header>

    <!-- Top 3 Podium -->
    <section class="mb-12">
        <h2 class="text-lg font-bold mb-5 text-center">Current Top 3</h2>

        @if ($submissions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            @foreach ($submissions->take(3) as $index => $entry)
                @php
                    $medals = ['#EAB308', '#9CA3AF', '#B45309'];
                    $order = ['md:order-2', 'md:order-1', 'md:order-3'];
                    $pad = ['md:py-8', 'md:py-6', 'md:py-5'];
                @endphp
                <div class="card p-5 text-center {{ $order[$index] }} {{ $pad[$index] }}" style="border-top: 3px solid {{ $medals[$index] }};">
                    <div class="text-2xl font-extrabold mb-2" style="color: {{ $medals[$index] }};">#{{ $index + 1 }}</div>
                    <div class="font-semibold truncate" title="{{ e($entry->player->display_name ?? $entry->player->instagram_username) }}">
                        {{ $entry->player->display_name ?? $entry->player->instagram_username }}
                    </div>
                    <div class="text-sm text-muted truncate">{{ '@' . $entry->player->instagram_username }}</div>
                    <div class="text-3xl font-extrabold mt-3">{{ $entry->submitted_score }}</div>
                    <div class="text-xs text-muted mt-1">{{ $entry->submitted_at->format('M d, Y') }}</div>
                </div>
            @endforeach
        </div>
        @else
        <div class="card p-10 text-center">
            <p class="text-muted">No approved scores yet. Be the first to compete!</p>
        </div>
        @endif
    </section>

    <!-- Full Leaderboard Table -->
    <section>
        <h2 class="text-lg font-bold mb-5">Full Leaderboard</h2>
        <div class="card overflow-hidden">
            @if ($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr style="background:var(--bg);">
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wide text-muted">Rank</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wide text-muted">Instagram</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wide text-muted">Best Score</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wide text-muted">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $index => $entry)
                        <tr style="border-top:1px solid var(--border);">
                            <td class="py-3 px-4 font-semibold">
                                @if ($index < 3)
                                    <span style="color: {{ ['#EAB308', '#9CA3AF', '#B45309'][$index] }};">#{{ $index + 1 }}</span>
                                @else
                                    #{{ $index + 1 }}
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <a href="https://www.instagram.com/{{ e($entry->player->instagram_username) }}/" target="_blank" rel="noopener noreferrer" class="hover:underline">
                                    {{ '@' . $entry->player->instagram_username }}
                                </a>
                            </td>
                            <td class="py-3 px-4 font-bold">{{ $entry->submitted_score }}</td>
                            <td class="py-3 px-4 text-sm text-muted">{{ $entry->submitted_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4" style="border-top:1px solid var(--border);">
                {{ $submissions->links() }}
            </div>
            @else
            <div class="text-center py-10">
                <p class="text-muted">No approved scores yet.</p>
            </div>
            @endif
        </div>
    </section>

    <div class="mt-8 text-center">
        <button onclick="location.reload()" class="btn btn-secondary">Refresh Leaderboard</button>
        <a href="/" class="text-primary hover:underline ml-4">Back to Home</a>
    </div>

</div>
@endsection
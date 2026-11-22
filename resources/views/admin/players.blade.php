@extends('admin._layout')

@section('title', 'Players')

@section('content')
<div class="w-full">
    <header class="mb-6">
        <h1 class="text-2xl font-extrabold">Players</h1>
        <p class="text-muted">Competition participants</p>
    </header>

    <div class="card p-4 md:p-6">
        <form method="GET" class="mb-5 flex gap-2">
            <input type="text" name="search" class="form-input" placeholder="Search Instagram username" value="{{ $search }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Instagram</th>
                        <th class="py-3 px-4 text-left">Display Name</th>
                        <th class="py-3 px-4 text-left">Submissions</th>
                        <th class="py-3 px-4 text-left">First Seen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($players as $index => $player)
                    <tr>
                        <td class="py-3 px-4">{{ $players->firstItem() + $index }}</td>
                        <td class="py-3 px-4"><a href="https://www.instagram.com/{{ e($player->instagram_username) }}/" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ '@' . $player->instagram_username }}</a></td>
                        <td class="py-3 px-4">{{ $player->display_name ?? '—' }}</td>
                        <td class="py-3 px-4">{{ $player->scoreSubmissions->count() }}</td>
                        <td class="py-3 px-4 text-sm text-muted">{{ $player->first_seen_at ? $player->first_seen_at->format('M d, Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-8 text-center text-muted">No players yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $players->links() }}</div>
    </div>
</div>
@endsection
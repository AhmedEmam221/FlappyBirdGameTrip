@extends('layouts.app')

@section('title', 'Flappy Challenge')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <header class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3">Flappy Challenge</h1>
        <p class="text-lg text-muted">Play, score, compete, and win.</p>
    </header>

    <!-- Hero -->
    <section class="card text-center mb-12 py-12 px-6">
        <h2 class="text-2xl font-bold mb-3">Think you can fly?</h2>
        <p class="text-muted mb-7 max-w-md mx-auto">Tap through the pipes, climb the leaderboard, and take your shot at the top three prizes.</p>
        <a href="/play" class="btn btn-primary text-lg px-8 py-3">Play Now</a>
    </section>

    <!-- How It Works -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6 text-center">How It Works</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="card p-6">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold mb-4" style="background:#DBEAFE;color:var(--accent);">1</div>
                <h3 class="font-bold mb-2">Play the Game</h3>
                <p class="text-sm text-muted">Test your reflexes and score as high as you can.</p>
            </div>
            <div class="card p-6">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold mb-4" style="background:#DBEAFE;color:var(--accent);">2</div>
                <h3 class="font-bold mb-2">Submit Your Score</h3>
                <p class="text-sm text-muted">Enter your Instagram username to join the leaderboard.</p>
            </div>
            <div class="card p-6">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold mb-4" style="background:#DBEAFE;color:var(--accent);">3</div>
                <h3 class="font-bold mb-2">Follow & Verify</h3>
                <p class="text-sm text-muted">Follow our page and compete for the top three prizes.</p>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="mb-10">
        <h2 class="text-2xl font-bold mb-6 text-center">Features</h2>
        <div class="card divide-y" style="border-color:var(--border);">
            <div class="flex items-start p-5">
                <span class="text-primary font-bold mr-4">◆</span>
                <div>
                    <p class="font-semibold">Leaderboard</p>
                    <p class="text-sm text-muted">Compete for the top three spots and win prizes.</p>
                </div>
            </div>
            <div class="flex items-start p-5">
                <span class="text-primary font-bold mr-4">◆</span>
                <div>
                    <p class="font-semibold">Instagram Integration</p>
                    <p class="text-sm text-muted">Submit your username to enter the competition.</p>
                </div>
            </div>
            <div class="flex items-start p-5">
                <span class="text-primary font-bold mr-4">◆</span>
                <div>
                    <p class="font-semibold">Prizes for Top 3</p>
                    <p class="text-sm text-muted">First, second, and third place winners receive prizes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Links -->
    <div class="text-center">
        <p class="text-sm text-muted">Review the <a href="/rules" class="text-primary hover:underline">competition rules</a> and <a href="/privacy" class="text-primary hover:underline">privacy policy</a>.</p>
    </div>

</div>
@endsection
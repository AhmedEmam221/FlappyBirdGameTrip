@extends('layouts.app')

@section('title', 'Flappy Challenge - Play')

@section('content')
<div class="max-w-2xl mx-auto text-center">

    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Flappy Challenge</h1>
        <p class="text-muted">Achieve the highest score.</p>
    </header>

    <!-- Game wrapper (game sits directly under the title) -->
    <div class="relative inline-block" id="game-wrapper">
        <div id="game-container" style="position:relative; width:400px; max-width:100%; margin:0 auto; line-height:0;">
            <canvas id="game-canvas" width="400" height="550" style="display:block; width:100%; max-width:400px; height:auto; border:1px solid var(--border); border-radius:10px; background:#EAF4FB;"></canvas>

            <!-- In-game score -->
            <div style="position:absolute; top:14px; left:0; right:0; pointer-events:none;">
                <div class="text-4xl font-extrabold" style="color:#000;" id="current-score">0</div>
            </div>

            <!-- Start screen -->
            <div id="start-screen" class="overlay-screen">
                <div class="overlay-box">
                    <h2 class="text-2xl font-bold mb-2">Ready?</h2>
                    <p class="text-muted mb-5 text-sm">Tap, click, or press Space to flap.</p>
                    <button id="start-btn" class="btn btn-primary">Start Game</button>
                </div>
            </div>

            <!-- Game over screen -->
            <div id="game-over-screen" class="overlay-screen" style="display:none;">
                <div class="overlay-box">
                    <h2 class="text-2xl font-bold mb-1">Game Over</h2>
                    <p class="text-muted text-sm">Your score</p>
                    <div class="text-5xl font-extrabold my-2" style="color:var(--accent);" id="final-score-display">0</div>
                    <button id="play-again-btn" class="btn btn-primary w-full mb-2">Play Again</button>
                    <button id="open-submit-btn" class="btn btn-secondary w-full">Submit Score</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="mt-5">
        <p class="text-muted text-sm mb-2">Tap or click to flap. Avoid the pipes and the ground.</p>
        <p class="text-sm" style="color:var(--muted);">Press <kbd class="px-2 py-0.5 rounded border" style="border-color:var(--border);font-family:inherit;">Space</kbd> to flap &middot; <kbd class="px-2 py-0.5 rounded border" style="border-color:var(--border);font-family:inherit;">P</kbd> to pause</p>
    </div>

    <!-- Sponsors -->
    <div class="mt-8">
        <p class="text-xs text-muted uppercase tracking-widest mb-3">Powered by</p>
        <div class="flex items-center justify-center gap-5">
            <img src="/images/logos/momento.svg" alt="Momento" class="h-11 w-11 rounded-lg" style="border:1px solid var(--border);">
            <span class="text-muted">·</span>
            <img src="/images/logos/nezoo.svg" alt="Nezoo Press" class="h-11 w-11 rounded-lg" style="border:1px solid var(--border);">
        </div>
    </div>

</div>

<!-- Score Submission Modal -->
<div id="score-modal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4 text-center">Submit Your Score</h3>
        <div id="submit-message" class="mb-3 text-sm" style="display:none;"></div>
        <form id="submit-form">
            <div class="mb-4">
                <label class="block text-sm mb-2 text-left">Instagram Username</label>
                <input type="text" name="instagram_username" id="ig-username" class="form-input" placeholder="e.g., username" required autocomplete="off">
                <p class="text-xs text-muted mt-1 text-left">Leading @ is optional.</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2 text-left">Bus Number</label>
                <input type="text" name="bus_number" id="bus-number" class="form-input" placeholder="e.g., 12 or BUS-05" required autocomplete="off" maxlength="20">
                <p class="text-xs text-muted mt-1 text-left">Enter your bus number.</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2 text-left">Score</label>
                <input type="number" name="score" id="submit-score" class="form-input" min="0" required readonly>
            </div>
            <button type="submit" id="submit-btn" class="btn btn-primary w-full mb-2">Submit Score</button>
            <button type="button" class="btn btn-secondary w-full" id="close-submit-btn">Close</button>
        </form>
        <div class="mt-5 text-center pt-4" style="border-top:1px solid var(--border);">
            <p class="text-xs text-muted mb-3">Want to win? Follow our instagram pages.</p>
            <div class="flex flex-col gap-2">
                <a href="https://www.instagram.com/mome.ntotravel" target="_blank" rel="noopener noreferrer" class="btn btn-secondary w-full">Momento Travel</a>
                <a href="https://www.instagram.com/nezoo_press" target="_blank" rel="noopener noreferrer" class="btn btn-secondary w-full">Nezoo Press</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .overlay-screen {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        background: rgba(241, 245, 249, 0.85);
        border-radius: 10px;
    }
    .overlay-box {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 28px; border-radius: var(--radius);
        text-align: center; width: 88%; max-width: 300px;
    }
</style>
<script>
(function () {
    'use strict';

    // ---- Config ----
    // Movement is frame-rate independent (delta-time based) so the game runs
    // at the same speed on high-FPS desktops and lower-FPS mobile devices.
    var GRAVITY = 0.55, FLAP = -10.5, PIPE_SPEED = 3.2, PIPE_GAP = 180, PIPE_WIDTH = 64;
    var PIPE_INTERVAL = 78; // frames @60fps between pipes (time-based via tick)
    var GROUND_Y = 490;

    // ---- DOM ----
    var canvas = document.getElementById('game-canvas');
    var ctx = canvas.getContext('2d');
    var scoreEl = document.getElementById('current-score');
    var startScreen = document.getElementById('start-screen');
    var overScreen = document.getElementById('game-over-screen');
    var finalScoreEl = document.getElementById('final-score-display');
    var startBtn = document.getElementById('start-btn');
    var playAgainBtn = document.getElementById('play-again-btn');
    var openSubmitBtn = document.getElementById('open-submit-btn');
    var submitForm = document.getElementById('submit-form');
    var submitBtn = document.getElementById('submit-btn');
    var submitMessage = document.getElementById('submit-message');
    var igUsername = document.getElementById('ig-username');
    var submitScore = document.getElementById('submit-score');
    var closeSubmitBtn = document.getElementById('close-submit-btn');
    var scoreModal = document.getElementById('score-modal');

    var csrf = '{{ csrf_token() }}';

    // ---- Logos (drawn on the pipes) ----
    var logos = [];
    ['/images/logos/momento.svg', '/images/logos/nezoo.svg'].forEach(function (src) {
        var img = new Image();
        img.src = src;
        logos.push(img);
    });

    // Each pipe takes the background colour of the logo displayed on it,
    // so the logo blends seamlessly into the pillar.
    var PIPE_STYLES = [
        { body: '#262626', cap: '#111111' },  // Momento logo background (dark charcoal)
        { body: '#F5F0E6', cap: '#D8D0BC' }   // Nezoo Press logo background (cream)
    ];

    // ---- State ----
    var sessionUuid = '', sessionToken = '', rafId = null;
    var running = false, paused = false, over = false, score = 0, tick = 0, lastPipe = 0;
    var lastGapCenter = 0;
    var pipeCount = 0;      // monotonic pipe counter for odd/even logo assignment
    var lastTime = 0;       // previous frame timestamp (delta-time)
    var bird, pipes;

    // ---- Session ----
    function createSession(callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/game-session/create', true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function () {
            try {
                var data = JSON.parse(xhr.responseText);
                sessionUuid = data.session_uuid || '';
                sessionToken = data.secure_token || '';
            } catch (e) {
                sessionUuid = ''; sessionToken = '';
            }
            callback();
        };
        xhr.onerror = function () { callback(); };
        xhr.send();
    }

    // ---- Game objects ----
    function reset() {
        bird = { x: 90, y: canvas.height / 2, w: 30, h: 22, v: 0, rot: 0 };
        pipes = [];
        score = 0; tick = 0; lastPipe = 0; over = false; paused = false;
        pipeCount = 0; lastTime = 0;
        // Start the first gap near the middle of the playable area
        lastGapCenter = GROUND_Y / 2;
        scoreEl.textContent = '0';
    }

    function start() {
        reset();
        createSession(function () {
            startScreen.style.display = 'none';
            overScreen.style.display = 'none';
            running = true;
            if (rafId) cancelAnimationFrame(rafId);
            rafId = requestAnimationFrame(loop);
        });
    }

    function flap() {
        if (!running || paused || over) return;
        bird.v = FLAP;
    }

    function loop(time) {
        if (!running) return;
        if (paused) { lastTime = time || 0; rafId = requestAnimationFrame(loop); return; }

        // Delta time in "60fps frames". Clamp to avoid big jumps after tab switches.
        var dt = lastTime ? (time - lastTime) : 16.667;
        lastTime = time;
        if (!isFinite(dt) || dt <= 0) dt = 16.667;
        if (dt > 50) dt = 50;
        var f = dt / 16.667;

        tick += f;

        // Bird physics (scaled by delta time)
        bird.v += GRAVITY * f;
        bird.y += bird.v * f;
        bird.rot = Math.max(-25, Math.min(90, bird.v * 4));

        // Spawn pipes — keep the gap centered in the playable area (above ground).
        // The gap centre stays within a comfortable band and only shifts a little
        // between consecutive pipes, so no pipe is unreachably high or low.
        if (tick - lastPipe > PIPE_INTERVAL) {
            var half = PIPE_GAP / 2;
            var minCenter = half + 40;                 // keep top pipe >= 40px
            var maxCenter = GROUND_Y - half - 40;      // keep bottom pipe >= 40px above ground
            var target = minCenter + Math.random() * (maxCenter - minCenter);
            // Limit vertical movement between pipes
            var maxStep = 90;
            var delta = target - lastGapCenter;
            if (delta > maxStep) delta = maxStep;
            if (delta < -maxStep) delta = -maxStep;
            lastGapCenter += delta;
            var top = Math.floor(lastGapCenter - half);
            // Odd pipes (1,3,5...) get the black Momento logo; even pipes get the cream Nezoo logo.
            pipeCount++;
            var logoIndex = (pipeCount % 2 === 1) ? 0 : 1;
            pipes.push({ x: canvas.width, top: top, passed: false, logo: logoIndex });
            lastPipe = tick;
        }

        // Move pipes & collisions
        for (var i = pipes.length - 1; i >= 0; i--) {
            var p = pipes[i];
            p.x -= PIPE_SPEED * f;

            if (bird.x + bird.w > p.x && bird.x < p.x + PIPE_WIDTH) {
                if (bird.y < p.top || bird.y + bird.h > p.top + PIPE_GAP) {
                    endGame();
                    return;
                }
            }
            if (!p.passed && p.x + PIPE_WIDTH < bird.x) {
                p.passed = true;
                score++;
                scoreEl.textContent = String(score);
            }
            if (p.x + PIPE_WIDTH < 0) pipes.splice(i, 1);
        }

        // Ground / ceiling collision
        if (bird.y + bird.h >= GROUND_Y || bird.y < 0) {
            endGame();
            return;
        }

        draw();
        rafId = requestAnimationFrame(loop);
    }

    // Flat palette
    var C = {
        sky: '#EAF4FB',
        cloud: '#FFFFFF',
        pipe: '#2563EB',
        pipeCap: '#1D4ED8',
        ground: '#111827',
        groundTop: '#2563EB',
        bird: '#F59E0B',
        birdDark: '#D97706',
        beak: '#F97316'
    };

    function draw() {
        // Flat sky
        ctx.fillStyle = C.sky;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Flat clouds
        ctx.fillStyle = C.cloud;
        for (var c = 0; c < 4; c++) {
            var cx = ((c * 130 + tick * 0.5) % (canvas.width + 120)) - 60;
            var cy = 60 + c * 40;
            ctx.beginPath();
            ctx.arc(cx, cy, 26, 0, Math.PI * 2);
            ctx.arc(cx + 22, cy + 6, 20, 0, Math.PI * 2);
            ctx.arc(cx - 22, cy + 6, 18, 0, Math.PI * 2);
            ctx.fill();
        }

        // Pipes — colored to match each sponsor logo's background
        for (var i = 0; i < pipes.length; i++) {
            var p = pipes[i];
            var style = PIPE_STYLES[p.logo % PIPE_STYLES.length];

            ctx.fillStyle = style.body;
            ctx.fillRect(p.x, 0, PIPE_WIDTH, p.top);
            ctx.fillRect(p.x, p.top + PIPE_GAP, PIPE_WIDTH, canvas.height - p.top - PIPE_GAP);

            ctx.fillStyle = style.cap;
            ctx.fillRect(p.x - 4, p.top - 18, PIPE_WIDTH + 8, 18);
            ctx.fillRect(p.x - 4, p.top + PIPE_GAP, PIPE_WIDTH + 8, 18);

            // Sponsor logo on the pipe (flat, no effects)
            var logo = logos[p.logo % logos.length];
            if (logo && logo.complete && logo.naturalWidth > 0) {
                var maxW = PIPE_WIDTH - 10;
                var lh = maxW; // logos are square
                if (p.top > lh + 30) {
                    ctx.drawImage(logo, p.x + 5, p.top - lh - 26, maxW, lh);
                }
                var bottomTop = p.top + PIPE_GAP;
                if (canvas.height - bottomTop > lh + 10) {
                    ctx.drawImage(logo, p.x + 5, bottomTop + 26, maxW, lh);
                }
            }
        }

        // Ground — single flat band with a thin accent line
        ctx.fillStyle = C.groundTop;
        ctx.fillRect(0, GROUND_Y, canvas.width, 4);
        ctx.fillStyle = C.ground;
        ctx.fillRect(0, GROUND_Y + 4, canvas.width, canvas.height - GROUND_Y - 4);

        // Bird — flat geometric shape
        ctx.save();
        ctx.translate(bird.x + bird.w / 2, bird.y + bird.h / 2);
        ctx.rotate(bird.rot * Math.PI / 180);
        ctx.fillStyle = C.bird;
        ctx.beginPath();
        ctx.ellipse(0, 0, bird.w / 2, bird.h / 2, 0, 0, Math.PI * 2);
        ctx.fill();
        // Wing
        ctx.fillStyle = C.birdDark;
        ctx.beginPath();
        ctx.ellipse(-2, 2, bird.w / 4, bird.h / 4, 0, 0, Math.PI * 2);
        ctx.fill();
        // Eye
        ctx.fillStyle = '#FFFFFF';
        ctx.beginPath();
        ctx.arc(bird.w / 4, -3, 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = C.ground;
        ctx.beginPath();
        ctx.arc(bird.w / 4 + 1, -3, 2, 0, Math.PI * 2);
        ctx.fill();
        // Beak
        ctx.fillStyle = C.beak;
        ctx.beginPath();
        ctx.moveTo(-bird.w / 2, 0);
        ctx.lineTo(-bird.w / 2 - 9, 4);
        ctx.lineTo(-bird.w / 2, 8);
        ctx.fill();
        ctx.restore();
    }

    function endGame() {
        running = false; over = true;
        if (rafId) cancelAnimationFrame(rafId);
        finalScoreEl.textContent = String(score);
        submitScore.value = String(score);
        overScreen.style.display = 'flex';
    }

    function openSubmit() {
        overScreen.style.display = 'none';
        submitMessage.style.display = 'none';
        scoreModal.classList.add('active');
        igUsername.focus();
    }
    function closeSubmit() { scoreModal.classList.remove('active'); }

    // ---- Input ----
    function onInput(e) {
        if (e.type === 'keydown') {
            if (e.code === 'Space' || e.key === ' ') { e.preventDefault(); flap(); }
            else if (e.key === 'p' || e.key === 'P') { if (running && !over) paused = !paused; }
            else if (e.key === 'Enter' && !running && startScreen.style.display !== 'none') { start(); }
            return;
        }
        e.preventDefault();
        if (!running && startScreen.style.display !== 'none') { start(); return; }
        flap();
    }
    window.addEventListener('keydown', onInput, { passive: false });
    canvas.addEventListener('mousedown', onInput, { passive: false });
    canvas.addEventListener('touchstart', onInput, { passive: false });

    startBtn.addEventListener('click', start);
    playAgainBtn.addEventListener('click', start);
    openSubmitBtn.addEventListener('click', openSubmit);
    closeSubmitBtn.addEventListener('click', closeSubmit);

    // ---- Submit ----
    submitForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var name = igUsername.value.replace(/^@/, '').trim();
        if (!name || !/^[a-zA-Z0-9_.]+$/.test(name)) {
            showMsg('Please enter a valid Instagram username (letters, numbers, underscores).', true);
            return;
        }
        var bus = document.getElementById('bus-number').value.trim();
        if (!bus || !/^[a-zA-Z0-9\-\s]+$/.test(bus)) {
            showMsg('Please enter a valid bus number.', true);
            return;
        }
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        showMsg('Submitting your score...', false);

        var fd = new FormData();
        fd.append('game_session_id', sessionUuid);
        fd.append('score', String(score));
        fd.append('instagram_username', name);
        fd.append('bus_number', bus);
        fd.append('_token', csrf);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/score/submit', true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Session-Token', sessionToken);
        xhr.onload = function () {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Score';
            var data;
            try { data = JSON.parse(xhr.responseText); } catch (err) { data = {}; }
            if (xhr.status >= 200 && xhr.status < 300 && data.success) {
                showMsg(data.message || 'Score submitted! Awaiting verification.', false);
                submitForm.reset();
                submitScore.value = String(score);
            } else {
                showMsg(data.error || 'Submission failed. Please try again.', true);
            }
        };
        xhr.onerror = function () {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Score';
            showMsg('Network error. Please try again.', true);
        };
        xhr.send(fd);
    });

    function showMsg(text, isError) {
        submitMessage.textContent = text;
        submitMessage.style.color = isError ? '#ef4444' : '#22c55e';
        submitMessage.style.display = 'block';
    }

    // Draw initial empty frame
    reset();
    draw();
})();
</script>
@endpush
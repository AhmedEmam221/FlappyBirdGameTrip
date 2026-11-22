<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Flappy Challenge</title>
    <meta name="description" content="@yield('description', 'Play, score, compete, and win!')">
    <meta name="keywords" content="flappy bird, competition, game">
    <meta name="author" content="Khaled Hult">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* ============ Minimal / Flat Design Tokens ============ */
        :root {
            --bg: #F6F7F9;
            --surface: #FFFFFF;
            --border: #E5E7EB;
            --text: #111827;
            --muted: #6B7280;
            --accent: #2563EB;
            --accent-hover: #1D4ED8;
            --success: #16A34A;
            --warning: #D97706;
            --danger: #DC2626;
            --radius: 10px;
        }

        * { box-sizing: border-box; }

        html { -webkit-text-size-adjust: 100%; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text);
        }

        a { color: var(--accent); }

        /* Utility colors */
        .text-primary { color: var(--accent) !important; }
        .text-muted { color: var(--muted) !important; }
        .text-accent { color: var(--accent) !important; }
        .gradient-text { color: var(--accent); } /* flat, kept for compatibility */
        .text-success { color: var(--success) !important; }
        .text-danger { color: var(--danger) !important; }

        /* Cards — flat white with a hairline border */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        /* Buttons — solid flat color, no gradients or shadows */
        .btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid transparent;
            padding: 11px 22px;
            font-size: 0.95rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: var(--accent-hover); color: #fff; }

        .btn-secondary {
            background: var(--surface);
            color: var(--text);
            border-color: var(--border);
        }
        .btn-secondary:hover { background: #F3F4F6; color: var(--text); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #B91C1C; color: #fff; }

        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Game container sizing controlled in play.blade.php */
        #game-container {
            position: relative;
            width: 400px;
            max-width: 100%;
            margin: 0 auto;
        }

        /* Form inputs — flat */
        .form-input {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 0.95rem;
            width: 100%;
            font-family: inherit;
            transition: border-color 0.15s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
        }
        .form-input::placeholder { color: #9CA3AF; }

        /* Leaderboard entries */
        .leaderboard-entry {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            margin-bottom: 8px;
            transition: border-color 0.15s ease;
        }
        .leaderboard-entry:hover { border-color: #C7D2FE; }
        .leaderboard-entry.gold { border-left: 4px solid #EAB308; }
        .leaderboard-entry.silver { border-left: 4px solid #9CA3AF; }
        .leaderboard-entry.bronze { border-left: 4px solid #B45309; }

        /* Badges */
        .badge {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 999px;
        }
        .badge-pending { background: #FEF3C7; color: #92400E; }
        .badge-approved { background: #DCFCE7; color: #166534; }
        .badge-rejected { background: #FEE2E2; color: #991B1B; }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.15s ease, visibility 0.15s ease;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }

        .modal-content {
            background: var(--surface);
            padding: 28px;
            border-radius: var(--radius);
            width: 90%;
            max-width: 420px;
            border: 1px solid var(--border);
        }

        /* Focus */
        *:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* Simple divider text */
        .divider { height: 1px; background: var(--border); }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
        }
    </style>
</head>
<body>
    <div class="min-h-screen px-4 py-8 md:px-6 md:py-10">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>

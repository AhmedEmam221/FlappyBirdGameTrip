<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flappy Challenge - Admin</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* ============ Minimal / Flat Design Tokens (Admin) ============ */
        :root {
            --bg: #F6F7F9;
            --surface: #FFFFFF;
            --border: #E5E7EB;
            --text: #111827;
            --muted: #6B7280;
            --accent: #2563EB;
            --accent-hover: #1D4ED8;
            --danger: #DC2626;
            --success: #16A34A;
            --warning: #D97706;
            --radius: 10px;
            --sidebar-w: 256px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        a { color: var(--accent); text-decoration: none; }

        .text-primary { color: var(--accent) !important; }
        .text-muted { color: var(--muted) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-xs { font-size: 0.75rem; }
        .text-sm { font-size: 0.875rem; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        /* ---------- Sidebar (sliding) ---------- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 20px;
            overflow-y: auto;
            z-index: 40;
            transform: translateX(0);
            transition: transform 0.25s ease;
        }
        body.sidebar-collapsed .sidebar { transform: translateX(-100%); }

        .sidebar-title { padding-left: 44px; }

        .sidebar-nav a {
            color: var(--muted);
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.15s, color 0.15s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: #EFF6FF;
            color: var(--accent);
        }

        /* Toggle button */
        #sidebar-toggle {
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 50;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            cursor: pointer;
            transition: background-color 0.15s;
        }
        #sidebar-toggle:hover { background: #F3F4F6; }
        #sidebar-toggle svg { width: 20px; height: 20px; }

        /* Mobile backdrop */
        #sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.4);
            z-index: 30;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        body:not(.sidebar-collapsed) #sidebar-backdrop { opacity: 1; visibility: visible; }

        /* Main content */
        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            padding: 24px;
            transition: margin-left 0.25s ease;
        }
        body.sidebar-collapsed .admin-main { margin-left: 0; }
        @media (min-width: 768px) { .admin-main { padding: 32px; } }

        /* ---------- Buttons ---------- */
        .btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid transparent;
            padding: 9px 16px;
            font-size: 0.875rem;
            transition: background-color 0.15s, border-color 0.15s;
            display: inline-block;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-secondary { background: var(--surface); color: var(--text); border-color: var(--border); }
        .btn-secondary:hover { background: #F3F4F6; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #B91C1C; }

        /* Form */
        .form-input {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.9rem;
            width: 100%;
            font-family: inherit;
        }
        .form-input:focus { outline: none; border-color: var(--accent); }

        /* Tables */
        .table th, .table td { color: var(--text); }
        table thead th { color: var(--muted); font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; }
        table tbody tr { border-top: 1px solid var(--border); }
        table tbody tr:hover { background: #F9FAFB; }

        /* Badges */
        .badge { display: inline-block; font-size: 0.7rem; font-weight: 600; padding: 3px 9px; border-radius: 999px; }
        .badge-approved { background: #DCFCE7; color: #166534; }
        .badge-pending { background: #FEF3C7; color: #92400E; }
        .badge-rejected { background: #FEE2E2; color: #991B1B; }
        .badge-warning { background: #FEF3C7; color: #92400E; }

        /* Pagination */
        .pagination { display: flex; gap: 4px; list-style: none; padding: 0; margin: 0; }
        .pagination li > a, .pagination li > span {
            display: inline-block; padding: 6px 11px; border: 1px solid var(--border);
            border-radius: 6px; color: var(--text); font-size: 0.85rem;
        }
        .pagination li.active > span { background: var(--accent); color: #fff; border-color: var(--accent); }

        *:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
        @media (prefers-reduced-motion: reduce) {
            .sidebar, .admin-main, #sidebar-backdrop { transition: none !important; }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Toggle button -->
    <button id="sidebar-toggle" type="button" aria-label="Toggle sidebar" aria-expanded="true" title="Toggle sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Mobile backdrop -->
    <div id="sidebar-backdrop" aria-hidden="true"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <h2 class="text-xl font-extrabold mb-8 sidebar-title" style="color:var(--accent);">Flappy Admin</h2>

        <nav class="sidebar-nav space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm @if(request()->routeIs('admin.dashboard')) active @endif">Overview</a>
            <a href="{{ route('admin.entries') }}" class="block px-4 py-2.5 text-sm @if(request()->routeIs('admin.entries')) active @endif">Submissions</a>
            <a href="{{ route('admin.players') }}" class="block px-4 py-2.5 text-sm @if(request()->routeIs('admin.players')) active @endif">Players</a>
            <a href="{{ route('admin.analytics') }}" class="block px-4 py-2.5 text-sm @if(request()->routeIs('admin.analytics')) active @endif">Analytics</a>
            <a href="{{ route('admin.settings') }}" class="block px-4 py-2.5 text-sm @if(request()->routeIs('admin.settings')) active @endif">Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4" style="border-top:1px solid var(--border);">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-danger hover:bg-red-50 rounded-lg">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main -->
    <main class="admin-main">
        @yield('content')
    </main>

    <script>
        (function () {
            var body = document.body;
            var toggle = document.getElementById('sidebar-toggle');
            var backdrop = document.getElementById('sidebar-backdrop');
            var mq = window.matchMedia('(max-width: 767px)');
            var STORAGE_KEY = 'flappy-sidebar-collapsed';

            function setCollapsed(collapsed) {
                body.classList.toggle('sidebar-collapsed', collapsed);
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                try { localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0'); } catch (e) {}
            }

            // Initial state: respect saved preference, otherwise mobile = collapsed, desktop = open
            var saved = null;
            try { saved = localStorage.getItem(STORAGE_KEY); } catch (e) {}
            if (saved === null) {
                setCollapsed(mq.matches);
            } else {
                setCollapsed(saved === '1');
            }

            toggle.addEventListener('click', function () {
                setCollapsed(!body.classList.contains('sidebar-collapsed'));
            });

            backdrop.addEventListener('click', function () { setCollapsed(true); });

            // Close sidebar when a nav link is tapped on mobile
            document.querySelectorAll('.sidebar-nav a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (mq.matches) setCollapsed(true);
                });
            });

            // When switching to mobile, collapse the sidebar
            mq.addEventListener('change', function (e) {
                if (e.matches) setCollapsed(true);
            });

            // Escape key closes the sidebar
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !body.classList.contains('sidebar-collapsed')) setCollapsed(true);
            });
        })();
    </script>
</body>
</html>

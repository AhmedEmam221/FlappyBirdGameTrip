<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Flappy Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg: #F6F7F9; --surface: #FFFFFF; --border: #E5E7EB;
            --text: #111827; --muted: #6B7280; --accent: #2563EB;
            --accent-hover: #1D4ED8; --radius: 10px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg); color: var(--text); margin: 0;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 20px; -webkit-font-smoothing: antialiased;
        }
        .card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); width: 100%; max-width: 380px; padding: 32px;
        }
        .form-input {
            background: var(--surface); color: var(--text); border: 1px solid var(--border);
            border-radius: 8px; padding: 11px 14px; font-size: 0.95rem; width: 100%; font-family: inherit;
        }
        .form-input:focus { outline: none; border-color: var(--accent); }
        .btn-primary {
            background: var(--accent); color: #fff; border: none; border-radius: 8px;
            padding: 11px 20px; font-weight: 600; font-size: 0.95rem; cursor: pointer; width: 100%;
            font-family: inherit;
        }
        .btn-primary:hover { background: var(--accent-hover); }
        .error-box {
            background: #FEE2E2; color: #991B1B; border-radius: 8px;
            padding: 10px 14px; font-size: 0.85rem; margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold mb-1">Flappy Challenge</h1>
            <p class="text-muted text-sm">Admin sign in</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="error-box">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <label class="flex items-center text-sm text-muted mb-6">
                <input type="checkbox" name="remember" class="mr-2"> Remember me
            </label>
            <button type="submit" class="btn-primary">Sign in</button>
        </form>

        <div class="text-center mt-6">
            <a href="/" class="text-primary text-sm hover:underline">&larr; Back to website</a>
        </div>
    </div>
</body>
</html>

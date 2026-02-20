<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Cause we Can') }}</title>
    <style>
        :root {
            color-scheme: dark;
            --bg-0: #0b0f19;
            --bg-1: #111827;
            --bg-2: #1a2333;
            --line: #2f3c53;
            --text: #e7edf8;
            --muted: #9aa9c0;
            --accent: #7aa2f7;
            --accent-2: #9b8cff;
            --success: #72f1b8;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 10% -10%, rgba(122, 162, 247, .24), transparent 35%),
                radial-gradient(circle at 90% 0%, rgba(155, 140, 255, .20), transparent 30%),
                linear-gradient(180deg, var(--bg-0), #0b111b 35%, #0b0f19 100%);
            min-height: 100vh;
        }

        a {
            color: #b7ccff;
            text-decoration: none;
            transition: color .2s ease;
        }

        a:hover { color: #d8e4ff; }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.2rem;
            padding: .8rem 1rem;
            background: linear-gradient(145deg, rgba(17, 24, 39, .85), rgba(16, 23, 34, .65));
            border: 1px solid rgba(122, 162, 247, .25);
            border-radius: 14px;
            backdrop-filter: blur(5px);
        }

        .brand {
            margin: 0;
            font-size: 1.25rem;
            letter-spacing: .4px;
        }

        .nav-links {
            display: flex;
            gap: .45rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-link {
            display: inline-block;
            padding: .42rem .72rem;
            border: 1px solid transparent;
            border-radius: 9px;
            color: #d5e1f6;
        }

        .nav-link:hover {
            border-color: rgba(122, 162, 247, .4);
            background: rgba(122, 162, 247, .1);
        }

        .card {
            background: linear-gradient(155deg, rgba(26, 35, 51, .78), rgba(18, 25, 37, .86));
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .22);
        }

        .hero {
            border-color: rgba(122, 162, 247, .4);
            background:
                radial-gradient(circle at 85% 0%, rgba(122, 162, 247, .2), transparent 25%),
                linear-gradient(155deg, rgba(26, 35, 51, .88), rgba(15, 23, 35, .95));
        }

        .btn {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #0b1020;
            border: none;
            border-radius: 10px;
            padding: .5rem .85rem;
            font-weight: 800;
            cursor: pointer;
        }

        .btn:hover { filter: brightness(1.06); }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        input, textarea, select {
            width: 100%;
            background: rgba(7, 13, 23, .9);
            border: 1px solid #334258;
            color: var(--text);
            border-radius: 10px;
            padding: .55rem .6rem;
            margin-top: .3rem;
            margin-bottom: .65rem;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(122, 162, 247, .15);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }

        th, td {
            text-align: left;
            padding: .7rem;
            border-bottom: 1px solid rgba(56, 70, 94, .7);
        }

        th {
            color: #c7d8f6;
            font-weight: 700;
            background: rgba(122, 162, 247, .08);
        }

        tr:hover td { background: rgba(122, 162, 247, .04); }

        .muted { color: var(--muted); }

        .badge {
            display: inline-block;
            padding: .16rem .45rem;
            border-radius: 999px;
            font-size: .78rem;
            border: 1px solid rgba(114, 241, 184, .55);
            color: var(--success);
            background: rgba(114, 241, 184, .08);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <h1 class="brand">Cause we Can</h1>
        <div class="nav-links">
            <a class="nav-link" href="{{ route('home') }}">Blog</a>
            <a class="nav-link" href="{{ route('calendar.index') }}">Calendar</a>
            <a class="nav-link" href="{{ route('roster.index') }}">Roster</a>
            <a class="nav-link" href="{{ route('downloads.index') }}">Downloads</a>
            @auth
                <form method="post" action="{{ route('logout') }}">@csrf <button class="btn" type="submit">Logout</button></form>
            @else
                <a class="btn" href="{{ route('auth.discord.redirect') }}">Login with Discord</a>
            @endauth
        </div>
    </div>

    @if (session('status'))
        <div class="card">{{ session('status') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>

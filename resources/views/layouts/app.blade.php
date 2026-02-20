<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Cause we Can') }}</title>
    <style>
        :root { color-scheme: dark; }
        body { margin: 0; font-family: Inter, Arial, sans-serif; background: #0f1117; color: #e6edf3; }
        a { color: #7aa2f7; text-decoration: none; }
        .container { max-width: 1100px; margin: 0 auto; padding: 1.5rem; }
        .nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .nav-links { display: flex; gap: 1rem; align-items: center; }
        .card { background: #161b22; border: 1px solid #30363d; border-radius: 10px; padding: 1rem; margin-bottom: 1rem; }
        .btn { background: #7aa2f7; color: #111; border: none; border-radius: 8px; padding: .5rem .8rem; font-weight: 700; cursor: pointer; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap: 1rem; }
        input, textarea, select { width: 100%; background: #0d1117; border: 1px solid #30363d; color: #e6edf3; border-radius: 8px; padding: .45rem; margin-top: .2rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .6rem; border-bottom: 1px solid #30363d; }
        .muted { color: #9da5b4; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <h1 style="margin:0;">Cause we Can</h1>
        <div class="nav-links">
            <a href="{{ route('home') }}">Blog</a>
            <a href="{{ route('calendar.index') }}">Calendar</a>
            <a href="{{ route('roster.index') }}">Roster</a>
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

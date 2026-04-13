<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
</head>
<body class="admin-shell">
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand brand-admin">
            <span class="brand-mark admin-brand-mark">SG</span>
            <span>
                <strong>Serve God</strong>
                <small>{{ auth()->user()->name }} · {{ str_replace('_', ' ', auth()->user()->role) }}</small>
            </span>
        </a>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
            <a href="{{ route('admin.posts.index') }}" @class(['active' => request()->routeIs('admin.posts.*')])>Manage Posts</a>
            <a href="{{ route('admin.media.index') }}" @class(['active' => request()->routeIs('admin.media.*')])>Media Library</a>
            <a href="{{ route('admin.admins.index') }}" @class(['active' => request()->routeIs('admin.admins.*')])>Manage Admins</a>
            <a href="{{ route('home') }}">View Site</a>
        </nav>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="ghost-button admin-ghost-button full-width" type="submit">Logout</button>
        </form>
    </aside>

    <main class="admin-main">
        @if(session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>

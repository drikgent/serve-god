<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Serve God Studio' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'A modern personal photo and video blog with a Pinterest-inspired feed and Instagram-style storytelling.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
</head>
<body class="site-shell">
    <div class="ambient ambient-a"></div>
    <div class="ambient ambient-b"></div>

    <header class="topbar">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark">SG</span>
            <span>
                <strong>Serve God Studio</strong>
            </span>
        </a>

        <nav class="main-nav">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('explore') }}" @class(['active' => request()->routeIs('explore')])>Explore</a>
            <a href="{{ route('categories.index') }}" @class(['active' => request()->routeIs('categories.*')])>Categories</a>
            <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>About</a>
            <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>Contact</a>
        </nav>

        @if(auth()->check() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="ghost-button">Back to Admin</a>
        @else
            <a href="{{ route('admin.login') }}" class="ghost-button">Admin</a>
        @endif
    </header>

    <main class="page-wrap">
        @yield('content')
    </main>

    <footer class="site-footer">
        <p>&copy; {{ now()->year }} ServeG Studio. All rights reserved.</p>
    </footer>
</body>
</html>

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
    <button class="admin-mobile-menu" type="button" aria-label="Open admin menu" aria-controls="adminSidebar" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <button class="admin-mobile-overlay" type="button" aria-label="Close admin menu"></button>

    <aside id="adminSidebar" class="admin-sidebar" aria-hidden="false">
        <button class="admin-mobile-close" type="button" aria-label="Close admin menu">&times;</button>
        <a href="{{ route('admin.dashboard') }}" class="brand brand-admin">
            <span class="brand-mark admin-brand-mark">SG</span>
            <span>
                <strong>Serve God</strong>
                <small>{{ auth()->user()->name }} &middot; {{ str_replace('_', ' ', auth()->user()->role) }}</small>
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

    <script>
        (() => {
            const shell = document.body;
            const toggle = document.querySelector('.admin-mobile-menu');
            const overlay = document.querySelector('.admin-mobile-overlay');
            const close = document.querySelector('.admin-mobile-close');
            const sidebar = document.getElementById('adminSidebar');
            const navLinks = sidebar ? sidebar.querySelectorAll('a') : [];
            if (!toggle || !overlay || !close || !sidebar) {
                return;
            }

            const closeMenu = () => {
                shell.classList.remove('admin-sidebar-open');
                toggle.setAttribute('aria-expanded', 'false');
                sidebar.setAttribute('aria-hidden', 'true');
            };

            const openMenu = () => {
                shell.classList.add('admin-sidebar-open');
                toggle.setAttribute('aria-expanded', 'true');
                sidebar.setAttribute('aria-hidden', 'false');
            };

            toggle.addEventListener('click', () => {
                if (shell.classList.contains('admin-sidebar-open')) {
                    closeMenu();
                    return;
                }
                openMenu();
            });

            overlay.addEventListener('click', closeMenu);
            close.addEventListener('click', closeMenu);
            navLinks.forEach((link) => link.addEventListener('click', closeMenu));

            window.addEventListener('resize', () => {
                if (window.innerWidth > 760) {
                    shell.classList.remove('admin-sidebar-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    sidebar.setAttribute('aria-hidden', 'false');
                } else if (!shell.classList.contains('admin-sidebar-open')) {
                    sidebar.setAttribute('aria-hidden', 'true');
                }
            });

            if (window.innerWidth <= 760) {
                sidebar.setAttribute('aria-hidden', 'true');
            }
        })();
    </script>
</body>
</html>

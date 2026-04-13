<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
</head>
<body class="admin-login-page">
    <main class="admin-login-shell">
        <section class="admin-login-showcase">
            <span class="eyebrow admin-eyebrow">Admin Access</span>
            <h1>Run the studio like a sharper editorial workspace.</h1>
            <p>Sign in to publish visual stories, manage the media pipeline, and handle remote collaboration with a more polished admin experience.</p>

            <div class="admin-login-notes">
                <div class="admin-login-note">
                    <strong>Publishing flow</strong>
                    <span>Create, edit, draft, and publish posts from anywhere.</span>
                </div>
                <div class="admin-login-note">
                    <strong>Demo accounts</strong>
                    <span>`admin@servegod.test` / `password`</span>
                    <span>`editor@servegod.test` / `password`</span>
                </div>
            </div>
        </section>

        <section class="admin-login-panel">
            <div class="admin-login-panel-head">
                <span class="brand-mark admin-brand-mark">SG</span>
                <div>
                    <strong>Sign in to Admin Studio</strong>
                    <p>Secure access for editors and super admins.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.login.store') }}" class="admin-login-card">
                @csrf
                <label>Email</label>
                <input type="email" name="email" placeholder="admin@servegod.test" value="{{ old('email') }}" required>
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
                <label class="checkbox-row">
                    <input type="checkbox" name="remember" value="1">
                    <span>Keep me signed in</span>
                </label>
                @error('email')
                    <p class="error-text">{{ $message }}</p>
                @enderror
                <button type="submit">Enter dashboard</button>
                <a href="{{ route('home') }}" class="admin-back-link">Back to public site</a>
            </form>
        </section>
    </main>
</body>
</html>

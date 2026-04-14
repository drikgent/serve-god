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
    <main class="admin-login-shell admin-login-shell-single">
        <section class="admin-login-panel">
            <div class="admin-login-panel-head">
                <span class="brand-mark admin-brand-mark">SG</span>
                <div>
                    <strong>Sign in to Serve God</strong>
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

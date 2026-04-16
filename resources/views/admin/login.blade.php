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
    <div class="admin-login-slider" aria-hidden="true">
        <div class="admin-login-slider-track">
            <div class="admin-login-slider-panel">
                <div class="admin-login-slider-inner">
                    <img src="{{ asset('admin-login/rizzal.png') }}" alt="">
                </div>
            </div>
            <div class="admin-login-slider-panel">
                <div class="admin-login-slider-inner">
                    <img src="{{ asset('admin-login/john-weak.jpg') }}" alt="">
                </div>
            </div>
            <div class="admin-login-slider-panel">
                <div class="admin-login-slider-inner">
                    <img src="{{ asset('admin-login/paul.jpg') }}" alt="">
                </div>
            </div>
            <div class="admin-login-slider-panel">
                <div class="admin-login-slider-inner">
                    <img src="{{ asset('admin-login/no-context.jpg') }}" alt="">
                </div>
            </div>
            <div class="admin-login-slider-panel">
                <div class="admin-login-slider-inner">
                    <img src="{{ asset('admin-login/bull.jpg') }}" alt="">
                </div>
            </div>
            <div class="admin-login-slider-panel">
                <div class="admin-login-slider-inner">
                    <img src="{{ asset('admin-login/slide-6.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
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
    <script>
        (function () {
            const panels = document.querySelectorAll(".admin-login-slider-inner");
            if (!panels.length) return;

            const body = document.body;
            body.classList.add("admin-login-intro");
            const getTransform = (value) => `translateY(${value})`;
            const introDelayMs = 450;
            const introDurationMs = 2000;
            const formEntranceDelayMs = 180;

            panels.forEach((panel, index) => {
                const isEven = index % 2 === 0;
                panel.style.transform = isEven ? getTransform("-100%") : getTransform("100%");
            });

            const playIntroOnce = () => {
                panels.forEach((panel, index) => {
                    panel.style.transform = getTransform("0");
                });
            };

            setTimeout(playIntroOnce, introDelayMs);
            setTimeout(() => {
                body.classList.add("admin-login-intro-done");
            }, introDelayMs + introDurationMs + formEntranceDelayMs);
        })();
    </script>
</body>
</html>

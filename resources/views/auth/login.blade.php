<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Rice Mill App</title>
    <meta name="description" content="Masuk ke Rice Mill App — Sistem Monitoring Pertanian Terpadu">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:       #1a5c38;
            --primary-light: #2d7a50;
            --primary-dark:  #0e3d24;
            --accent:        #e8b84b;
            --bg-card:       #ffffff;
            --text-main:     #1c2b1e;
            --text-muted:    #6b7c6e;
            --border:        #dde5de;
            --error:         #dc3545;
            --shadow-soft:   0 8px 32px rgba(26, 92, 56, .08);
            --shadow-hover:  0 12px 40px rgba(26, 92, 56, .14);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 50%, #d1fae5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative background circles */
        body::before {
            content: '';
            position: fixed;
            top: -180px; right: -120px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(26,92,56,.06), rgba(232,184,75,.04));
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -200px; left: -100px;
            width: 450px; height: 450px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(232,184,75,.06), rgba(26,92,56,.03));
            z-index: 0;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
        }

        /* Logo / Brand */
        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 56px; height: 56px;
            background: var(--primary);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(26, 92, 56, .2);
        }

        .brand-icon i, .brand-icon svg { color: #fff; width: 28px; height: 28px; }

        .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1.65rem;
            color: var(--text-main);
            letter-spacing: .01em;
        }

        .brand-sub {
            font-size: .82rem;
            color: var(--text-muted);
            margin-top: 4px;
            letter-spacing: .02em;
        }

        /* Card */
        .auth-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 40px 36px 36px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--border);
            transition: box-shadow .3s ease;
        }

        .auth-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .auth-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-main);
            text-align: center;
            margin-bottom: 4px;
        }

        .auth-card-subtitle {
            font-size: .84rem;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 28px;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: .83rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 7px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px; height: 18px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: .9rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-main);
            background: #fff;
            transition: border .2s, box-shadow .2s;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #a8b5ab;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 92, 56, .1);
        }

        .input-wrapper input.is-invalid {
            border-color: var(--error);
        }

        .input-wrapper input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, .1);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover { color: var(--primary); }
        .toggle-password i { width: 18px; height: 18px; }

        .error-text {
            display: block;
            font-size: .76rem;
            color: var(--error);
            margin-top: 5px;
            padding-left: 2px;
        }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-row label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .84rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            font-size: .82rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { text-decoration: underline; }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all .22s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 92, 56, .25);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit i { width: 18px; height: 18px; }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .auth-divider span {
            font-size: .78rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            margin-top: 28px;
            font-size: .86rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        /* Alert for session errors */
        .alert-danger-custom {
            background: #fde8e8;
            border: 1px solid #f5b8b8;
            color: #8b1a1a;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: .84rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-danger-custom i { width: 16px; height: 16px; flex-shrink: 0; }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card { padding: 28px 22px 24px; }
            body { padding: 16px; }
        }

        /* Smooth entrance animation */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-wrapper { animation: slideUp .5s ease-out; }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- Brand -->
    <div class="brand-header">
        <div class="brand-icon">
            <i data-lucide="sprout"></i>
        </div>
        <div class="brand-name">SiMonTani</div>
        <div class="brand-sub">Sistem Monitoring Pertanian Terpadu</div>
    </div>

    <!-- Login Card -->
    <div class="auth-card" id="login-card">
        <h2 class="auth-card-title">Masuk ke akun Anda</h2>
        <p class="auth-card-subtitle">Akses dashboard sesuai peran Anda</p>

        @if(session('status'))
            <div class="alert-danger-custom" style="background:#e8f5ee;border-color:#b2dcc4;color:#1a5c38;">
                <i data-lucide="check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email-input">Email</label>
                <div class="input-wrapper">
                    <i data-lucide="mail" class="input-icon"></i>
                    <input id="email-input"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required autofocus
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="nama@email.com">
                </div>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password-input">Password</label>
                <div class="input-wrapper">
                    <i data-lucide="lock" class="input-icon"></i>
                    <input id="password-input"
                           type="password"
                           name="password"
                           required
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Masukkan password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-input', this)">
                        <i data-lucide="eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-submit" id="login-btn">
                <i data-lucide="log-in"></i>
                Masuk
            </button>
        </form>

        <!-- Divider -->
        <div class="auth-divider">
            <span>atau</span>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
</script>

</body>
</html>
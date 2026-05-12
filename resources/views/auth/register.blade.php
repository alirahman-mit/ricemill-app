<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Rice Mill App</title>
    <meta name="description" content="Daftar akun baru di Rice Mill App — Sistem Monitoring Pertanian Terpadu">

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
            max-width: 520px;
        }

        /* Logo / Brand */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
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
            padding: 36px 36px 32px;
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
            margin-bottom: 24px;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label.field-label {
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

        /* ===== Role Selection ===== */
        .role-section-label {
            font-size: .83rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 10px;
        }

        .role-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 22px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0; height: 0;
        }

        .role-option .role-label {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            cursor: pointer;
            transition: all .22s ease;
            background: #fff;
        }

        .role-option .role-label:hover {
            border-color: var(--primary-light);
            background: #f8fbf9;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: var(--primary);
            background: #edf7f1;
            box-shadow: 0 0 0 3px rgba(26, 92, 56, .08);
        }

        .role-icon-box {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f0f4f1;
            transition: all .22s ease;
        }

        .role-icon-box i {
            width: 20px; height: 20px;
            color: var(--text-muted);
            transition: color .22s ease;
        }

        .role-option input[type="radio"]:checked + .role-label .role-icon-box {
            background: var(--primary);
        }

        .role-option input[type="radio"]:checked + .role-label .role-icon-box i {
            color: #fff;
        }

        .role-info { flex: 1; }

        .role-info .role-name {
            font-size: .88rem;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.2;
        }

        .role-info .role-desc {
            font-size: .76rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .role-check {
            width: 22px; height: 22px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all .22s ease;
        }

        .role-check i { width: 14px; height: 14px; color: #fff; display: none; }

        .role-option input[type="radio"]:checked + .role-label .role-check {
            background: var(--primary);
            border-color: var(--primary);
        }

        .role-option input[type="radio"]:checked + .role-label .role-check i {
            display: block;
        }

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

        .btn-submit:active { transform: translateY(0); }
        .btn-submit i { width: 18px; height: 18px; }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
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
            margin-top: 24px;
            font-size: .86rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        /* Alert */
        .alert-danger-custom {
            background: #fde8e8;
            border: 1px solid #f5b8b8;
            color: #8b1a1a;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: .84rem;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-danger-custom i { width: 16px; height: 16px; flex-shrink: 0; }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background: #eee;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength .bar {
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: width .3s, background .3s;
        }

        .password-hint {
            font-size: .74rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Responsive */
        @media (max-width: 560px) {
            .auth-card { padding: 28px 22px 24px; }
            body { padding: 16px; }
        }

        /* Entrance animation */
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
        <div class="brand-sub">Bergabunglah dengan ekosistem pertanian digital</div>
    </div>

    <!-- Register Card -->
    <div class="auth-card" id="register-card">
        <h2 class="auth-card-title">Buat akun baru</h2>
        <p class="auth-card-subtitle">Pilih peran Anda — tidak bisa diubah setelah daftar</p>

        {{-- Validation Error Summary --}}
        @if($errors->any())
            <div class="alert-danger-custom">
                <i data-lucide="alert-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf

            <!-- Role Selection -->
            <div class="form-group">
                <div class="role-section-label">Saya mendaftar sebagai</div>
                <div class="role-options">
                    <!-- Petani -->
                    <div class="role-option">
                        <input type="radio" name="role" id="role-petani" value="petani" required
                               {{ old('role', 'petani') === 'petani' ? 'checked' : '' }}>
                        <label for="role-petani" class="role-label">
                            <div class="role-icon-box">
                                <i data-lucide="sprout"></i>
                            </div>
                            <div class="role-info">
                                <div class="role-name">Petani</div>
                                <div class="role-desc">Kelola lahan, panen, & setoran gabah</div>
                            </div>
                            <div class="role-check">
                                <i data-lucide="check"></i>
                            </div>
                        </label>
                    </div>

                    <!-- Rice Mill -->
                    <div class="role-option">
                        <input type="radio" name="role" id="role-ricemill" value="rice_mill" required
                               {{ old('role') === 'rice_mill' ? 'checked' : '' }}>
                        <label for="role-ricemill" class="role-label">
                            <div class="role-icon-box">
                                <i data-lucide="factory"></i>
                            </div>
                            <div class="role-info">
                                <div class="role-name">Rice Mill</div>
                                <div class="role-desc">Penerimaan gabah, produksi & distribusi</div>
                            </div>
                            <div class="role-check">
                                <i data-lucide="check"></i>
                            </div>
                        </label>
                    </div>

                    <!-- Packager -->
                    <div class="role-option">
                        <input type="radio" name="role" id="role-packager" value="packager" required
                               {{ old('role') === 'packager' ? 'checked' : '' }}>
                        <label for="role-packager" class="role-label">
                            <div class="role-icon-box">
                                <i data-lucide="package"></i>
                            </div>
                            <div class="role-info">
                                <div class="role-name">Packager</div>
                                <div class="role-desc">Penerimaan beras, pengemasan & pesanan</div>
                            </div>
                            <div class="role-check">
                                <i data-lucide="check"></i>
                            </div>
                        </label>
                    </div>
                </div>
                @error('role')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama -->
            <div class="form-group">
                <label class="field-label" for="name-input">Nama lengkap</label>
                <div class="input-wrapper">
                    <i data-lucide="user" class="input-icon"></i>
                    <input id="name-input"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="Nama lengkap Anda">
                </div>
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="field-label" for="email-input">Email</label>
                <div class="input-wrapper">
                    <i data-lucide="mail" class="input-icon"></i>
                    <input id="email-input"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="nama@email.com">
                </div>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="field-label" for="password-input">Password</label>
                <div class="input-wrapper">
                    <i data-lucide="lock" class="input-icon"></i>
                    <input id="password-input"
                           type="password"
                           name="password"
                           required
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Minimal 8 karakter">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-input', this)">
                        <i data-lucide="eye"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="bar" id="pw-bar"></div>
                </div>
                <div class="password-hint" id="pw-hint">Gunakan minimal 8 karakter</div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="form-group">
                <label class="field-label" for="password-confirm-input">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <i data-lucide="lock" class="input-icon"></i>
                    <input id="password-confirm-input"
                           type="password"
                           name="password_confirmation"
                           required
                           placeholder="Ulangi password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-confirm-input', this)">
                        <i data-lucide="eye"></i>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-submit" id="register-btn">
                <i data-lucide="user-plus"></i>
                Daftar sekarang
            </button>
        </form>

        <!-- Divider -->
        <div class="auth-divider">
            <span>atau</span>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
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

    // Password strength indicator
    const pwInput = document.getElementById('password-input');
    const pwBar   = document.getElementById('pw-bar');
    const pwHint  = document.getElementById('pw-hint');

    pwInput.addEventListener('input', function() {
        const val = this.value;
        let strength = 0;
        if (val.length >= 8)            strength++;
        if (/[A-Z]/.test(val))          strength++;
        if (/[0-9]/.test(val))          strength++;
        if (/[^A-Za-z0-9]/.test(val))   strength++;

        const colors  = ['#dc3545', '#e8b84b', '#e8b84b', '#2d7a50', '#1a5c38'];
        const widths  = ['0%', '25%', '50%', '75%', '100%'];
        const hints   = [
            'Gunakan minimal 8 karakter',
            'Lemah — tambahkan huruf kapital',
            'Sedang — tambahkan angka',
            'Kuat — tambahkan simbol',
            'Sangat kuat! 🎉'
        ];

        pwBar.style.width      = widths[strength];
        pwBar.style.background = colors[strength];
        pwHint.textContent     = hints[strength];
    });
</script>

</body>
</html>
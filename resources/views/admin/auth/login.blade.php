<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" type="image/png" href="{{ asset('website.png') }}">
    <style>
        :root {
            --primary: #3ba100;
            --primary-hover: #4db60f;
            --secondary: #54575a;
            --bg-soft: #edf2f9;
            --text: #0f172a;
            --muted: #64748b;
            --border: #d8e1ee;
            --danger-bg: #fff1f2;
            --danger-text: #991b1b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 18px;
            background:
                radial-gradient(circle at 12% 16%, rgba(59, 161, 0, 0.12), transparent 28%),
                var(--bg-soft);
            font-family: Arial, sans-serif;
            color: var(--text);
        }

        .login-shell {
            width: 100%;
            max-width: 420px;
        }

        .card {
            border-radius: 24px;
            border: 1px solid rgba(216, 225, 238, 0.88);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.1);
        }

        .hero-logo {
            width: min(180px, 100%);
            height: auto;
            display: block;
            margin: 0 auto 18px;
            object-fit: contain;
        }

        .card {
            width: 100%;
            padding: 26px;
            align-self: center;
        }

        h2 {
            margin: 0 0 6px;
            color: var(--text);
            font-size: 28px;
            letter-spacing: -0.02em;
        }

        .subtext {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1f2937;
            font-size: 13px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 16px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            color: #0f172a;
            background: #fff;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 161, 0, 0.18);
        }

        .password-field {
            position: relative;
            margin-bottom: 16px;
        }

        .password-field input {
            margin-bottom: 0;
            padding-right: 46px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
            color: #64748b;
            box-shadow: none;
        }

        .password-toggle:hover {
            transform: translateY(-50%);
            background: transparent;
            box-shadow: none;
            color: #334155;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
            display: block;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .error {
            background: var(--danger-bg);
            color: var(--danger-text);
            font-size: 14px;
            margin-bottom: 16px;
            border-radius: 12px;
            border: 1px solid #fecaca;
            padding: 12px 14px;
            font-weight: 600;
        }

        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #4db60f 0%, var(--primary) 100%);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 16px 26px rgba(59, 161, 0, 0.24);
        }

        button:hover {
            background: linear-gradient(135deg, #67c92b 0%, var(--primary-hover) 100%);
            transform: translateY(-1px);
            box-shadow: 0 18px 28px rgba(59, 161, 0, 0.28);
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            .card {
                border-radius: 22px;
            }

            .card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="login-shell">
        <form class="card" method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <img src="{{ asset('website.png') }}" alt="ERP17" class="hero-logo">
            <h2>Welcome back</h2>
            <p class="subtext">Sign in to continue.</p>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="password">Password</label>
            <div class="password-field">
                <input id="password" name="password" type="password" required>
                <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password" aria-pressed="false">
                    <svg id="passwordToggleIcon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>

            <button type="submit">Sign in</button>
        </form>
    </div>
    <script>
        (() => {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');

            if (!passwordInput || !passwordToggle || !passwordToggleIcon) return;

            const eyeIcon = `
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
            const eyeOffIcon = `
                <path d="m3 3 18 18"></path>
                <path d="M10.58 10.58a2 2 0 0 0 2.84 2.84"></path>
                <path d="M9.88 5.09A9.77 9.77 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-2.2 3.19"></path>
                <path d="M6.61 6.61A17.34 17.34 0 0 0 2 12s3.5 7 10 7a9.9 9.9 0 0 0 4.25-.94"></path>
            `;

            passwordToggle.addEventListener('click', () => {
                const showPassword = passwordInput.type === 'password';

                passwordInput.type = showPassword ? 'text' : 'password';
                passwordToggle.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
                passwordToggle.setAttribute('aria-pressed', showPassword ? 'true' : 'false');
                passwordToggleIcon.innerHTML = showPassword ? eyeOffIcon : eyeIcon;
            });
        })();
    </script>
</body>

</html>
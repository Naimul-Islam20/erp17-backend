<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        :root {
            --primary: #e9a70e;
            --primary-hover: #f4bd35;
            --secondary: #1e1e6d;
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
                radial-gradient(circle at 12% 16%, rgba(233, 167, 14, 0.12), transparent 28%),
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
            width: min(210px, 100%);
            height: auto;
            display: block;
            margin: 0 auto 18px;
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
        input[type="password"] {
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
        input[type="password"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(233, 167, 14, 0.18);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: #334155;
            font-size: 14px;
        }

        .remember input {
            margin: 0;
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
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
            background: linear-gradient(135deg, #f5c84f 0%, var(--primary) 100%);
            color: #1f2937;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 16px 26px rgba(233, 167, 14, 0.24);
        }

        button:hover {
            background: linear-gradient(135deg, #ffd56a 0%, var(--primary-hover) 100%);
            transform: translateY(-1px);
            box-shadow: 0 18px 28px rgba(233, 167, 14, 0.28);
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
            <img src="{{ asset('Website.png') }}" alt="ERP17" class="hero-logo">
            <h2>Welcome back</h2>
            <p class="subtext">Sign in to continue.</p>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <label class="remember" for="remember">
                <input id="remember" type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit">Sign in</button>
        </form>
    </div>
</body>

</html>
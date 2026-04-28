<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #0f172a;
            font-family: Arial, sans-serif;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            padding: 24px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-top: 6px;
            margin-bottom: 12px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: 0;
            border-radius: 6px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
        }

        .error {
            color: #b91c1c;
            font-size: 14px;
            margin-bottom: 8px;
        }

        label {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <form class="card" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <h2>Admin Login</h2>

        @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
        @endif

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <label style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <input style="width:auto;margin:0;" type="checkbox" name="remember" value="1">
            Remember me
        </label>

        <button type="submit">Sign in</button>
    </form>
</body>

</html>
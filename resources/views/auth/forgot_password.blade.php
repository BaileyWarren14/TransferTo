<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Truck Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- IMPORTANTE PARA RESPONSIVE -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/log.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .login-card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
        }

        .btn-login {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
            color: #fff;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 2rem 1.5rem;
                max-width: 95%;
            }

            .login-card h3 {
                font-size: 1.8rem;
            }

            .login-card p.text-muted {
                font-size: 1rem;
            }

            .login-card input.form-control,
            .login-card .btn-login {
                font-size: 1rem;
                padding: 0.75rem;
            }

            .login-card label.form-label {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="mb-3 text-center">Forgot your password?</h3>
    <p class="text-muted text-center">Enter your email and we’ll send you a reset link.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="example@email.com">
        </div>

        <button type="submit" class="btn btn-login w-100">Send Reset Link</button>
    </form>

    <div class="mt-3 text-center">
        <a href="{{ route('login') }}" class="text-decoration-none">← Back to Login</a>
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Truck Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/log.css') }}" rel="stylesheet">
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

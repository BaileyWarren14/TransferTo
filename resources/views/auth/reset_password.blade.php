<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298); /* fondo azul degradado */
            min-height: 100vh;
        }

        .card {
            background-color: #ffffffdd; /* ligeramente translúcida para contraste */
        }

        .card h4 {
            color: #0d6efd;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        input.form-control {
            border-radius: 0.5rem;
        }

        .card-body {
            padding: 2.5rem;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        <h4 class="text-center mb-4"><i class="fa-solid fa-key"></i> Reset Password</h4>

                        {{-- Show success messages --}}
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Show validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ $userType === 'admin' ? route('admin.password.update') : route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="userType" value="{{ $userType }}">

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input id="password" type="password" class="form-control" name="password" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-3">
                                <i class="fa-solid fa-lock"></i> Reset Password
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ url('/log') }}" class="text-decoration-none fw-semibold link-back">
                                <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

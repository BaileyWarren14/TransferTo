@extends('layouts.app')

@section('content')
<div class="login-container d-flex align-items-center justify-content-center">
    <div class="card shadow-lg p-4 login-card">
        <div class="text-center mb-3">
            <!-- Logo de la empresa -->
            <img src="{{ asset('images/truck-logo.png') }}" alt="Company Logo" class="img-fluid" style="max-width:100px;">
            <h4 class="mt-2 fw-bold text-primary">Truck Company Login</h4>
            <p class="text-muted">Access your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3 input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-envelope-fill text-primary"></i>
                </span>
                <input type="email" name="email" id="email" class="form-control" placeholder="your@email.com" required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-3 input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-lock-fill text-primary"></i>
                </span>
                <input type="password" name="password" id="password" class="form-control" placeholder="********" required>
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <!-- Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </button>
            </div>
        </form>

        <!-- Links -->
        <div class="mt-3 text-center">
            <a href="{{ route('password.request') }}" class="text-decoration-none">
                <i class="bi bi-question-circle"></i> Forgot Password?
            </a>
        </div>
    </div>
</div>

<style>
/* Fondo con imagen */
.login-container {
    min-height: 100vh;
    background: url("{{ asset('images/truck-bg.jpg') }}") no-repeat center center fixed;
    background-size: cover;
}

/* Card */
.login-card {
    width: 100%;
    max-width: 400px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.95);
}

/* Inputs */
.form-control, .input-group-text {
    border-radius: 10px;
}

/* Botón */
.btn-primary {
    border-radius: 10px;
    font-weight: bold;
}
</style>
@endsection

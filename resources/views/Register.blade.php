<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Truck Company</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .register-card {
        background: #fff;
        border-radius: 15px;
        padding: 2rem;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .register-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }
    .register-card h2 {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #2a5298;
    }
    .form-control:focus {
        box-shadow: 0 0 5px rgba(42,82,152,0.5);
        border-color: #2a5298;
    }
    .btn-register {
        background-color: #2a5298;
        color: #fff;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .btn-register:hover {
        background-color: #1e3c72;
        transform: scale(1.05);
    }
    .feedback {
        font-size: 0.9rem;
        color: red;
        margin-top: 0.5rem;
    }
    .logo {
        display: block;
        margin: 0 auto 1rem auto;
        width: 80px;
    }
    .link-login {
        text-align: center;
        margin-top: 1rem;
    }
    .link-login a {
        color: #2a5298;
        text-decoration: none;
    }
    .link-login a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="register-card">
    <!-- Logo -->
    <img src="https://cdn-icons-png.flaticon.com/512/61/61215.png" alt="Truck Logo" class="logo">

    <h2>Create Account</h2>

    @if(session('error'))
        <div class="feedback">{{ session('error') }}</div>
    @endif

    <form id="registerForm" action="{{ route('register.post') }}" method="POST" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
            <div class="invalid-feedback">Please enter your full name.</div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="email@company.com" required>
            <div class="invalid-feedback">Please enter a valid email.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <div class="invalid-feedback">Password is required.</div>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
            <div class="invalid-feedback">Please confirm your password.</div>
        </div>
        <button type="submit" class="btn btn-register w-100">Register</button>
    </form>

    <div class="link-login">
        Already have an account? <a href="{{ route('log') }}">Login</a>
    </div>
</div>

<script>
    // Validation and feedback
    (function () {
        'use strict'
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();
</script>

</body>
</html>

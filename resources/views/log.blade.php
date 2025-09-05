<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Truck Company</title>
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
        .login-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        .login-card h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #2a5298;
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(42,82,152,0.5);
            border-color: #2a5298;
        }
        .btn-login {
            background-color: #2a5298;
            color: #fff;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-login:hover {
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
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo -->
        <!--<img src="https://cdn-icons-png.flaticon.com/512/61/61215.png" alt="Truck Logo" class="logo">

        <h2>Truck Company Login</h2>-->

        @if(session('error'))
            <div class="feedback">{{ session('error') }}</div>
        @endif

        <form id="loginForm" action="{{ route('login.post') }}" method="POST" novalidate>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@example.com" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <div class="invalid-feedback">Password is required.</div>
            </div>
            <button type="submit" class="btn btn-login w-100">Log In</button>
        </form>
    </div>

<script>
    // Validación y feedback de formulario
    (function () {
        'use strict'
        const form = document.getElementById('loginForm');
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

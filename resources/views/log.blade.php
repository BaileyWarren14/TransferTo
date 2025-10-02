<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Truck Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/log.css') }}" rel="stylesheet">
    <style>
        
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
                <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
             <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <span id="toggleIcon">👁️</span>
                    </button>
                    <div class="invalid-feedback">Password is required.</div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-login w-100">Log In</button>
            
        </form>
            <a href="{{ route('register') }}" class="btn btn-register w-100">Register</a>
            <div style="text-align: center;">
                <a href="" class="">Forgot password?</a>
            </div>

    </div>

    <script>
        // Toggle password visibility - Siempre disponible
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('toggleIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.textContent = '🙈';
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.textContent = '👁️';
                }
            });
        }
    </script>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = "{{ route('dashboard') }}"; // 🔹 cambia según tu ruta
    });
</script>
@endif

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

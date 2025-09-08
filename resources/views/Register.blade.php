<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Truck Company</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/register.css') }}" rel="stylesheet">
<link href="{{ asset('css/viewpassword.css') }}" rel="stylesheet">
</head>
<body>

<div class="register-card">
    <!-- Logo -->
    <!--<img src="https://cdn-icons-png.flaticon.com/512/61/61215.png" alt="Truck Logo" class="logo">-->

    <h2>Create Account</h2>

    @if(session('error'))
        <div class="feedback">{{ session('error') }}</div>
    @endif

    <form id="registerForm" action="{{ route('register.post') }}" method="POST" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="John" required>
            <div class="invalid-feedback">Please enter your first name.</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Doe" required>
            <div class="invalid-feedback">Please enter your last name.</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Social Security Number</label>
            <input type="text" class="form-control" id="social_security_number" name="social_security_number" placeholder="XXX-XX-XXXX" required>
            <div class="invalid-feedback">Please enter your Social Security Number.</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Driver's License Number</label>
            <input type="text" class="form-control" id="license_number" name="license_number" placeholder="S123-456-789-012" required>
            <div class="invalid-feedback">Please enter your Driver's License Number.</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="6786832213" required>
            <div class="invalid-feedback">Please enter your Phone Number.</div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="email@company.com" required>
            <div class="invalid-feedback">Please enter a valid email.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="password-wrapper">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    👁️
                </button>
            </div>
            <div class="invalid-feedback">Password is required.</div>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="password-wrapper">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                    👁️
                </button>
            </div>
            <div class="invalid-feedback">Please confirm your password.</div>
            
        </div>
        
        <button type="submit" class="btn btn-register w-100">Register</button>
    </form>

    <div class="link-login">
        Already have an account? <a href="{{ route('log') }}">Login</a>
    </div>

</div>

<script src="{{ asset('js/register.js') }}" defer></script>
    @if ($errors->any())
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/viewpassword.js') }}" defer></script>
<script>
    let errorMessages = '';
    @foreach ($errors->all() as $error)
        errorMessages += '{{ $error }}<br>';
    @endforeach

    Swal.fire({
        title: 'Error',
        html: errorMessages,
        icon: 'error',
        confirmButtonText: 'OK'
    });
    
    
</script>
@endif
</body>
</html>

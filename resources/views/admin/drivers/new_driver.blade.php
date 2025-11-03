@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container my-5">
    <h2 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i><span data-key="add_new_driver">Add New Driver</span></h2>

    <!-- Botón regresar -->
    <a href="{{ route('drivers.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> <span data-key="back_to_drivers">Back to Drivers</span>
    </a>

    <!-- Formulario -->
    <form id="driverForm" action="{{ route('drivers.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="row g-3">

            <div class="col-md-6">
                <label for="name" class="form-label" data-key="first_name">First Name</label>
                <input type="text" name="name" id="name" class="form-control" data-key="enter_first_name" placeholder="Enter first name" required>
            </div>

            <div class="col-md-6">
                <label for="lastname" class="form-label" data-key="last_name">Last Name</label>
                <input type="text" name="lastname" id="lastname" class="form-control" data-key="enter_last_name" placeholder="Enter last name" required>
            </div>

            <div class="col-md-6">
                <label for="phone_number" class="form-label" data-key="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" data-key="enter_phone_number" placeholder="Enter phone number">
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label" data-key="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" data-key="enter_email_address" placeholder="Enter email address" autocomplete="new-email">
            </div>

            <div class="col-md-6">
                <label for="social_security_number" class="form-label" data-key="social_security_number">Social Security Number</label>
                <input type="text" name="social_security_number" id="social_security_number" class="form-control" 
                data-key="enter_social_security_number" placeholder="Enter Social Security Number">
            </div>

            <div class="col-md-6">
                <label for="license_number" class="form-label" data-key="driver_license_number">Driver License Number</label>
                <input type="text" name="license_number" id="license_number" class="form-control" data-key="enter_driver_license_number" placeholder="Enter Driver License Number">
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label" data-key="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" data-key="enter_password" placeholder="Enter password" autocomplete="new-password">
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                <i class="fas fa-save me-1"></i> <span data-key="save_driver">Save Driver</span>
            </button>
            <a href="{{ route('drivers.index') }}" class="btn btn-secondary btn-lg flex-grow-1">
                <i class="fas fa-times me-1"></i> <span data-key="cancel"> Cancel</span>
            </a>
        </div>
    </form>
</div>

<!-- SweetAlert2 y Ajax -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('driverForm').addEventListener('submit', function(e) {
    e.preventDefault(); // detenemos el envío

    let name = document.getElementById('name').value.trim();
    let lastname = document.getElementById('lastname').value.trim();
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();

    // Validación rápida frontend
    if (!name || !lastname || !password) {
        Swal.fire({
            icon: 'error',
            title: 'Incomplete Fields',
            text: 'Please fill in all required fields',
        });
        return;
    }

    // Confirmación antes de guardar
    Swal.fire({
        title: 'Are you sure?',
        text: "This driver will be added to the system.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, save',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit(); // enviamos formulario
        }
    });
});
</script>



<style>
/* Diseño profesional */
/* Inputs flotantes estilo moderno */
.form-floating > label {
    color: #6c757d;
    transition: all 0.2s;
}
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: #0d6efd;
    transform: scale(0.85) translateY(-0.5rem);
}

/* Botones responsive */
@media (max-width: 576px) {
    .w-md-auto { width: 100% !important; }
}

.btn-success {
    background-color: #28a745;
    border: none;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-secondary {
    background-color: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
</style>

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Errores en el formulario',
        html: `
            <ul style="text-align:left;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        `
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        timer: 2500,
        showConfirmButton: false
    });
</script>
@endif

@endsection

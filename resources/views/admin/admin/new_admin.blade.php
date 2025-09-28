@extends('layouts.app_admin')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4"><i class="fas fa-user-shield me-2"></i>Add New Admin</h2>

    <!-- Botón regresar -->
    <a href="{{ route('admin.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back to Admins
    </a>

    <!-- Formulario -->
    <form id="addAdminForm" action="{{ route('admin.store') }}" method="POST">
        @csrf
        <div class="row g-3">

            <div class="col-md-6">
                <label for="name" class="form-label">First Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter first name" required>
            </div>

            <div class="col-md-6">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter last name" required>
            </div>

            <div class="col-md-6">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number">
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required autocomplete="new-email">
            </div>

            <div class="col-md-6">
                <label for="department" class="form-label">Department</label>
                <input type="text" name="department" id="department" class="form-control" placeholder="Enter department">
            </div>

            <div class="col-md-6">
                <label for="position" class="form-label">Position</label>
                <input type="text" name="position" id="position" class="form-control" placeholder="Enter position" required>
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required autocomplete="new-password">
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                <i class="fas fa-save me-1"></i> Save Admin
            </button>
            <a href="{{ route('admin.index') }}" class="btn btn-secondary btn-lg flex-grow-1">
                <i class="fas fa-times me-1"></i> Cancel
            </a>
        </div>
    </form>
</div>

<!-- SweetAlert2 y Ajax -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('addAdminForm').addEventListener('submit', function(e){
    e.preventDefault();

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
        text: "This admin will be added to the system.",
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
/* Diseño profesional similar al de drivers */
.btn-success {
    background-color: #28a745;
    border: none;
}
.btn-success:hover { background-color: #218838; }

.btn-secondary {
    background-color: #6c757d;
    border: none;
}
.btn-secondary:hover { background-color: #5a6268; }
</style>
@endsection

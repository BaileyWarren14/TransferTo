@extends('layouts.app')

@section('content')
<title>Edit Driver</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-4">
    <a href="{{ route('drivers.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> <span data-key="back">Back</span>
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <i class="fas fa-user-plus fa-2x me-2"></i> <span data-key="edit_driver">Edit Driver</span>
        </div>
        <div class="card-body">
            <form id="driverForm" method="POST" action="{{ route('drivers.update', $driver->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6 form-floating">
                        <input type="text" name="name" id="name" class="form-control" 
                            placeholder="First Name" value="{{ old('name', $driver->name) }}" required>
                        <label for="name" data-key="first_name">First Name</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="lastname" id="lastname" class="form-control" 
                            placeholder="Last Name" value="{{ old('lastname', $driver->lastname) }}" required>
                        <label for="lastname" data-key="last_name">Last Name</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="phone_number" id="phone_number" class="form-control" 
                            placeholder="Phone Number" value="{{ old('phone_number', $driver->phone_number) }}">
                        <label for="phone_number" data-key="phone_number">Phone Number</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="email" name="email" id="email" class="form-control" 
                            placeholder="Email Address" value="{{ old('email', $driver->email) }}">
                        <label for="email" data-key="email_address">Email Address</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="social_security_number" id="social_security_number" class="form-control" 
                            placeholder="Social Security Number" value="{{ old('social_security_number', $driver->social_security_number) }}">
                        <label for="social_security_number" data-key="social_security_number">Social Security Number</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="license_number" id="license_number" class="form-control" 
                            placeholder="Driver License Number" value="{{ old('license_number', $driver->license_number) }}">
                        <label for="license_number" data-key="driver_license_number">Driver License Number</label>
                    </div>

                     <div class="col-md-6 form-floating">
                        <input type="password" name="password" id="password" class="form-control" 
                            placeholder="Password">
                        <label for="password" data-key="password_leave_empty_to_keep_current">Password (Leave empty to keep current)</label>
                        
                    </div>
                </div>
                
               

                <div class="mt-4 d-flex flex-column flex-md-row gap-2 justify-content-between">
                    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fas fa-arrow-left me-1"></i> <span data-key="back">Back</span>
                    </a>
                    <button type="submit" class="btn btn-success w-100 w-md-auto">
                        <i class="fas fa-save me-1"></i> <span data-key="save_driver">Save Driver</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Submit con SweetAlert2
document.getElementById('driverForm').addEventListener('submit', function(e){
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({
                title: 'Driver Updated!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('drivers.index') }}";
            });
        } else {
            Swal.fire('Error', 'Failed to update driver', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', err.message, 'error');
    });
});
</script>

<style>
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
</style>

@endsection

@extends('layouts.app_admin')

@section('content')
<div class="container my-5">
    <a href="{{ route('admin.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <i class="fas fa-user-shield fa-2x me-2"></i> Edit Admin
        </div>
        <div class="card-body">
            <form id="editAdminForm" method="POST" action="{{ route('admin.update', $admin->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6 form-floating">
                        <input type="text" name="name" id="name" class="form-control" 
                            placeholder="First Name" value="{{ old('name', $admin->name) }}" required>
                        <label for="name">First Name</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="lastname" id="lastname" class="form-control" 
                            placeholder="Last Name" value="{{ old('lastname', $admin->lastname) }}" required>
                        <label for="lastname">Last Name</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="phone_number" id="phone_number" class="form-control" 
                            placeholder="Phone Number" value="{{ old('phone_number', $admin->phone_number) }}">
                        <label for="phone_number">Phone Number</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="email" name="email" id="email" class="form-control" 
                            placeholder="Email" value="{{ old('email', $admin->email) }}" required>
                        <label for="email">Email</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="department" id="department" class="form-control" 
                            placeholder="Department" value="{{ old('department', $admin->department) }}">
                        <label for="department">Department</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="position" id="position" class="form-control" 
                            placeholder="Position" value="{{ old('position', $admin->position) }}" required>
                        <label for="position">Position</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="password" name="password" id="password" class="form-control" 
                            placeholder="Password">
                        <label for="password">Password (Leave empty to keep current)</label>
                    </div>

                </div>

                <div class="mt-4 d-flex flex-column flex-md-row gap-2 justify-content-between">
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success w-100 w-md-auto">
                        <i class="fas fa-save me-1"></i> Save Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('editAdminForm').addEventListener('submit', function(e){
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
                title: 'Admin Updated!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('admin.index') }}";
            });
        } else {
            Swal.fire('Error', 'Failed to update Admin', 'error');
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

@media (max-width: 576px) {
    .w-md-auto { width: 100% !important; }
}
</style>
@endsection

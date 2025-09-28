@extends('layouts.app_admin')

@section('content')
<title>Edit Trailer</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-4">
    <a href="{{ route('trailers.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <i class="fas fa-truck-pickup fa-2x me-2"></i> Edit Trailer
        </div>
        <div class="card-body">
            <form id="trailerForm" method="POST" action="{{ route('trailers.update', $trailer->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6 form-floating">
                        <input type="number" name="axles" id="axles" class="form-control" 
                            placeholder="Number of Axles" value="{{ old('axles', $trailer->axles) }}" required>
                        <label for="axles">Number of Axles</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="trailer_type" id="trailer_type" class="form-control" 
                            placeholder="Trailer Type" value="{{ old('trailer_type', $trailer->trailer_type) }}" required>
                        <label for="trailer_type">Trailer Type</label>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="text" name="license_plate" id="license_plate" class="form-control" 
                            placeholder="License Plate" value="{{ old('license_plate', $trailer->license_plate) }}" required>
                        <label for="license_plate">License Plate</label>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-column flex-md-row gap-2 justify-content-between">
                    <a href="{{ route('trailers.index') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success w-100 w-md-auto">
                        <i class="fas fa-save me-1"></i> Save Trailer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Submit con SweetAlert2
document.getElementById('trailerForm').addEventListener('submit', function(e){
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
                title: 'Trailer Updated!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('trailers.index') }}";
            });
        } else {
            Swal.fire('Error', 'Failed to update trailer', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', err.message, 'error');
    });
});
</script>

<style>
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

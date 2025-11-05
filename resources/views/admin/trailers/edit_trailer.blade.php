@extends('layouts.app')

@section('content')
<style>
/* 🔹 Mantiene coherencia visual entre input y select flotantes */
    .form-floating > label {
        color: #6c757d;
        transition: all 0.2s;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label,
    .form-floating > .form-select:focus ~ label,
    .form-floating > .form-select:not(:placeholder-shown) ~ label {
        color: #0d6efd; /* Azul Bootstrap */
        transform: scale(0.85) translateY(-0.5rem);
}
</style>
<title>Edit Trailer</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-4">
    <a href="{{ route('trailers.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> <span data-key="back">Back</span>
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <i class="fas fa-truck-pickup fa-2x me-2"></i> <span data-key="edit_trailer">Edit Trailer</span>
        </div>
        <div class="card-body">
            <form id="trailerForm" method="POST" action="{{ route('trailers.update', $trailer->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6 form-floating">
                        <input type="number" name="axles" id="axles" class="form-control" 
                            placeholder="Number of Axles"  value="{{ old('axles', $trailer->axles) }}" required>
                        <label for="axles" data-key="number_of_axels">Number of Axles</label>
                    </div>

                     <div class="col-md-6 form-floating">
                        <select name="trailer_type" 
                                id="trailer_type" 
                                class="form-select" 
                                required>
                            <option value="" disabled {{ $trailer->trailer_type ? '' : 'selected' }}>Select Trailer type</option>
                            <option value="1" {{ $trailer->trailer_type == '1' ? 'selected' : '' }}>Cistern</option>
                            <option value="2" {{ $trailer->trailer_type == '2' ? 'selected' : '' }}>Dry Box</option>
                            <option value="3" {{ $trailer->trailer_type == '3' ? 'selected' : '' }}>Platform</option>
                            <option value="4" {{ $trailer->trailer_type == '4' ? 'selected' : '' }}>Pneumatic</option>
                            <option value="5" {{ $trailer->trailer_type == '5' ? 'selected' : '' }}>Other</option>
                        </select>
                        <label for="trailer_type" data-key="trailer_type">Trailer Type</label>
                    </div>
                    
                   

                    <div class="col-md-6 form-floating">
                        <input type="text" name="license_plate" id="license_plate" class="form-control" 
                            placeholder="License Plate" value="{{ old('license_plate', $trailer->license_plate) }}" required>
                        <label for="license_plate" data-key="license_plate">License Plate</label>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-column flex-md-row gap-2 justify-content-between">
                    <a href="{{ route('trailers.index') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fas fa-arrow-left me-1"></i> <span data-key="back">Back</span>
                    </a>
                    <button type="submit" class="btn btn-success w-100 w-md-auto">
                        <i class="fas fa-save me-1"></i> <span data-key="save_trailer">Save Trailer</span>
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

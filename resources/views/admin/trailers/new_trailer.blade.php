@extends('layouts.app_admin')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4"><i class="fas fa-plus-circle me-2"></i>Add New Trailer</h2>

    <a href="{{ route('trailers.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back to Trailers
    </a>

    <form id="addTrailerForm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="axles" class="form-label">Axles</label>
                <input type="number" name="axles" id="axles" class="form-control" placeholder="Enter number of axles" required>
            </div>

            <div class="col-md-6">
                <label for="trailer_type" class="form-label">Trailer Type</label>
                <input type="text" name="trailer_type" id="trailer_type" class="form-control" placeholder="Enter trailer type" required>
            </div>

            <div class="col-md-6">
                <label for="license_plate" class="form-label">License Plate</label>
                <input type="text" name="license_plate" id="license_plate" class="form-control" placeholder="Enter license plate" required>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                <i class="fas fa-save me-1"></i> Save Trailer
            </button>
            <a href="{{ route('trailers.index') }}" class="btn btn-secondary btn-lg flex-grow-1">
                <i class="fas fa-times me-1"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('addTrailerForm').addEventListener('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch("{{ route('trailers.store') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({
                icon: 'success',
                title: 'Trailer Saved!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "{{ route('trailers.index') }}";
            });
        } else {
            Swal.fire('Error', 'Failed to save trailer.', 'error');
        }
    })
    .catch(err => Swal.fire('Error', err.message, 'error'));
});
</script>
@endsection

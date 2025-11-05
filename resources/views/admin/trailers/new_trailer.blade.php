@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4" data-key=""><i class="fas fa-plus-circle me-2" ></i><span data-key="add_new_trailer">Add New Trailer</span></h2>

    <a href="{{ route('trailers.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> <span data-key="back_to_trailers">Back to Trailers</span>
    </a>

    <form id="addTrailerForm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="axles" class="form-label" data-key="axles">Axles</label>
                <input type="number" name="axles" id="axles" class="form-control" data-key="enter_number_of_axles" placeholder="Enter number of axles" required>
            </div>

             <div class="col-md-6">
                <label for="trailer_type" class="form-label" data-key="trailer_type">Trailer Type</label>
                <select name="trailer_type" id="trailer_type" class="form-select" required>
                    <option value="" disabled selected data-key="select_trailer_type">Select Trailer type</option>
                    <option value="1" data-key="cistern">Cistern</option>
                    <option value="2" data-key="dry_box">Dry Box</option>
                    <option value="3" data-key="platform">platform</option>
                    <option value="4" data-key="pneumatic">Pneunamtic</option>
                    <option value="5" data-key="other">other</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="license_plate" class="form-label" data-key="license_plate">License Plate</label>
                <input type="text" name="license_plate" id="license_plate" class="form-control" data-key="enter_trailer_plates" placeholder="Enter license plate" required>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                <i class="fas fa-save me-1"></i> <span data-key="save_trailer">Save Trailer</span>
            </button>
            <a href="{{ route('trailers.index') }}" class="btn btn-secondary btn-lg flex-grow-1">
                <i class="fas fa-times me-1"></i> <span data-key="cancel">Cancel</span>
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

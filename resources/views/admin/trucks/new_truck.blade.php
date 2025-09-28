@extends('layouts.app_admin')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4"><i class="fas fa-truck me-2"></i>Add New Truck</h2>
    <a href="{{ route('trucks.list_trucks') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back to Trucks
    </a>

    <form id="addTruckForm">
        @csrf
        <div class="row g-3">

            <div class="col-md-6">
                <label for="license_plate" class="form-label">License Plate</label>
                <input type="text" name="license_plate" id="license_plate" class="form-control" placeholder="Enter license plate" required>
            </div>

            <div class="col-md-6">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" id="brand" class="form-control" placeholder="Enter brand" required>
            </div>

            <div class="col-md-6">
                <label for="model" class="form-label">Model</label>
                <input type="text" name="model" id="model" class="form-control" placeholder="Enter model" required>
            </div>

            <div class="col-md-6">
                <label for="year" class="form-label">Year</label>
                <input type="number" name="year" id="year" class="form-control" placeholder="Enter year">
            </div>

            <div class="col-md-6">
                <label for="current_mileage" class="form-label">Current Mileage</label>
                <input type="number" name="current_mileage" id="current_mileage" class="form-control" placeholder="Enter current mileage">
            </div>

            <div class="col-md-6">
                <label for="fuel_capacity" class="form-label">Fuel Capacity</label>
                <input type="number" name="fuel_capacity" id="fuel_capacity" class="form-control" placeholder="Enter fuel capacity">
            </div>

            <div class="col-md-6">
                <label for="color" class="form-label">Color</label>
                <input type="text" name="color" id="color" class="form-control" placeholder="Enter color">
            </div>

            <div class="col-md-6">
                <label for="cab_type" class="form-label">Cab Type</label>
                <input type="text" name="cab_type" id="cab_type" class="form-control" placeholder="Enter cab type">
            </div>

            <div class="col-md-6">
                <label for="transmission_type" class="form-label">Transmission Type</label>
                <input type="text" name="transmission_type" id="transmission_type" class="form-control" placeholder="Enter transmission type">
            </div>

            <div class="col-md-6">
                <label for="driver_id" class="form-label">Assigned Driver (optional)</label>
                <select name="driver_id" id="driver_id" class="form-select">
                    <option value="">-- Select Driver --</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }} {{ $driver->lastname }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg flex-grow-1"><i class="fas fa-save me-1"></i> Save Truck</button>
            <a href="{{ route('trucks.list_trucks') }}" class="btn btn-secondary btn-lg flex-grow-1"><i class="fas fa-times me-1"></i> Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('addTruckForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);

    fetch("{{ route('trucks.store') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({
                icon: 'success',
                title: 'Truck Saved!',
                text: 'The new truck has been successfully added.',
                confirmButtonText: 'OK'
            }).then(() => window.location.href = "{{ route('trucks.list_trucks') }}");
        } else {
            Swal.fire('Error', 'Failed to save truck. Check your input.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'There was a problem saving the truck.', 'error');
    });
});
</script>
@endsection

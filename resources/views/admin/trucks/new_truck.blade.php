@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4" data-key=""><i class="fas fa-truck me-2"></i><span data-key="add_new_truck">Add New Truck </span></h2>
    <a href="{{ route('trucks.list_trucks') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> <span data-key="back_trucks">Back to Trucks</span>
    </a>

    <form id="addTruckForm">
        @csrf
        <div class="row g-3">

            <div class="col-md-6">
                <label for="license_plate" class="form-label" data-key="license_plate">License Plate</label>
                <input type="text" name="license_plate" id="license_plate" class="form-control" data-key="enter_license_plate" placeholder="Enter license plate" required>
            </div>

            <div class="col-md-6">
                <label for="brand" class="form-label" data-key="brand">Brand</label>
                <input type="text" name="brand" id="brand" class="form-control" data-key="enter_brand" placeholder="Enter brand" required>
            </div>

            <div class="col-md-6">
                <label for="model" class="form-label" data-key="model">Model</label>
                <input type="text" name="model" id="model" class="form-control" data-key="enter_model" placeholder="Enter model" required>
            </div>

            <div class="col-md-6">
                <label for="year" class="form-label" data-key="year">Year</label>
                <input type="number" name="year" id="year" class="form-control" data-key="enter_year" placeholder="Enter year">
            </div>

            <div class="col-md-6">
                <label for="current_mileage" class="form-label" data-key="current_mileage">Current Mileage</label>
                <input type="number" name="current_mileage" id="current_mileage" class="form-control" data-key="enter_current_mileage" placeholder="Enter current mileage">
            </div>

            <div class="col-md-6">
                <label for="fuel_capacity" class="form-label" data-key="fuel_capacity">Fuel Capacity</label>
                <input type="number" name="fuel_capacity" id="fuel_capacity" class="form-control" data-key="enter_fuel_capacity" placeholder="Enter fuel capacity">
            </div>

            <div class="col-md-6">
                <label for="color" class="form-label" data-key="color">Color</label>
                <input type="text" name="color" id="color" class="form-control" data-key="enter_color" placeholder="Enter color">
            </div>

            <div class="col-md-6">
                <label for="cab_type" class="form-label" data-key="cab_type">Cab Type</label>
                <input type="text" name="cab_type" id="cab_type" class="form-control" data-key="enter_cab_type" placeholder="Enter cab type">
            </div>

            <div class="col-md-6">
                <label for="transmission_type" class="form-label" data-key="transmission_type">Transmission Type</label>
                <input type="text" name="transmission_type" id="transmission_type" class="form-control" placeholder="Enter transmission type" data-key="enter_transmission_type">
            </div>

            <div class="col-md-6">
                <label for="driver_id" class="form-label" data-key="assigned_driver">Assigned Driver (optional)</label>
                <select name="driver_id" id="driver_id" class="form-select">
                    <option value="" data-key="select_driver">-- Select Driver --</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }} {{ $driver->lastname }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success btn-lg flex-grow-1" data-key="save_truck"><i class="fas fa-save me-1"></i> Save Truck</button>
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

@extends('layouts.app_admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-4">
    <a href="{{ route('trucks.list_trucks') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-1"></i> Back</a>

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <i class="fas fa-truck fa-2x me-2"></i> Edit Truck
        </div>
        <div class="card-body">
            <form id="editTruckForm" method="POST" action="{{ route('trucks.update', $truck->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="license_plate" class="form-label">License Plate</label>
                        <input type="text" name="license_plate" class="form-control" value="{{ old('license_plate', $truck->license_plate) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $truck->brand) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model', $truck->model) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" name="year" class="form-control" value="{{ old('year', $truck->year) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="current_mileage" class="form-label">Current Mileage</label>
                        <input type="number" name="current_mileage" class="form-control" value="{{ old('current_mileage', $truck->current_mileage) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="fuel_capacity" class="form-label">Fuel Capacity</label>
                        <input type="number" name="fuel_capacity" class="form-control" value="{{ old('fuel_capacity', $truck->fuel_capacity) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color', $truck->color) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="cab_type" class="form-label">Cab Type</label>
                        <input type="text" name="cab_type" class="form-control" value="{{ old('cab_type', $truck->cab_type) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="transmission_type" class="form-label">Transmission Type</label>
                        <input type="text" name="transmission_type" class="form-control" value="{{ old('transmission_type', $truck->transmission_type) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="driver_id" class="form-label">Assigned Driver (optional)</label>
                        <select name="driver_id" class="form-select">
                            <option value="">-- Select Driver --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $truck->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }} {{ $driver->lastname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('trucks.list_trucks') }}" class="btn btn-outline-secondary flex-grow-1"><i class="fas fa-arrow-left me-1"></i> Back</a>
                    <button type="submit" class="btn btn-success flex-grow-1"><i class="fas fa-save me-1"></i> Save Truck</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('editTruckForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({
                icon: 'success',
                title: 'Truck Updated!',
                confirmButtonText: 'OK'
            }).then(() => window.location.href = "{{ route('trucks.list_trucks') }}");
        } else {
            Swal.fire('Error', 'Failed to update truck.', 'error');
        }
    })
    .catch(err => Swal.fire('Error', 'Something went wrong.', 'error'));
});
</script>
@endsection

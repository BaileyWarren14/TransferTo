@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-4">
    <a href="{{ route('trucks.list_trucks') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-1"></i> <span data-key="back">Back</span></a>

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <i class="fas fa-truck fa-2x me-2"></i> <span data-key="edit_truck"> Edit Truck </span>
        </div>
        <div class="card-body">
            <form id="editTruckForm" method="POST" action="{{ route('trucks.update', $truck->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="license_plate" class="form-label" data-key="license_plate">License Plate</label>
                        <input type="text" name="license_plate" class="form-control" value="{{ old('license_plate', $truck->license_plate) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="brand" class="form-label" data-key="brand">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $truck->brand) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="model" class="form-label" data-key="model">Model</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model', $truck->model) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="year" class="form-label" data-key="year">Year</label>
                        <input type="number" name="year" id="year" class="form-control" data-key="enter_year" 
                            placeholder="Enter year" min="1900" max="2099" required>
                    </div>

                    <div class="col-md-6">
                        <label for="current_mileage" class="form-label" data-key="current_mileage">Current Mileage</label>
                        <input type="number" name="current_mileage" class="form-control" value="{{ old('current_mileage', $truck->current_mileage) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="fuel_capacity" class="form-label" data-key="fuel_capacity">Fuel Capacity</label>
                        <input type="number" name="fuel_capacity" class="form-control" value="{{ old('fuel_capacity', $truck->fuel_capacity) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color', $truck->color) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="cab_type" class="form-label" data-key="cab_type">Cab Type</label>
                        <select name="cab_type" id="cab_type" class="form-select" required>
                            <option value="" disabled {{ $truck->cab_type ? '' : 'selected' }}>Select cab type</option>
                            <option value="day_cab" {{ $truck->cab_type == 'day_cab' ? 'selected' : '' }}>Day Cab</option>
                            <option value="sleeper_cab" {{ $truck->cab_type == 'sleeper_cab' ? 'selected' : '' }}>Sleeper Cab</option>
                            <option value="extended_cab" {{ $truck->cab_type == 'extended_cab' ? 'selected' : '' }}>Extended Cab</option>
                            <option value="crew_cab" {{ $truck->cab_type == 'crew_cab' ? 'selected' : '' }}>Crew Cab</option>
                            <option value="cab_over" {{ $truck->cab_type == 'cab_over' ? 'selected' : '' }}>Cab-over / Forward Control</option>
                            <option value="conventional" {{ $truck->cab_type == 'conventional' ? 'selected' : '' }}>Conventional</option>
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label for="transmission_type" class="form-label" data-key="transmission_type">Transmission Type</label>
                        <select name="transmission_type" id="transmission_type" class="form-select" required>
                            <option value="" disabled {{ $truck->transmission_type ? '' : 'selected' }}>Select transmission type</option>
                            <option value="automatic" {{ $truck->transmission_type == 'automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="manual" {{ $truck->transmission_type == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="amt" {{ $truck->transmission_type == 'amt' ? 'selected' : '' }}>Automated Manual (AMT)</option>
                            <option value="cvt" {{ $truck->transmission_type == 'cvt' ? 'selected' : '' }}>Continuously Variable (CVT)</option>
                            <option value="dual_clutch" {{ $truck->transmission_type == 'dual_clutch' ? 'selected' : '' }}>Dual Clutch</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="driver_id" class="form-label" data-key="assigned_driver">Assigned Driver (optional)</label>
                        <select name="driver_id" class="form-select">
                            <option value="" data-key="select_diver">-- Select Driver --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $truck->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }} {{ $driver->lastname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('trucks.list_trucks') }}" class="btn btn-outline-secondary flex-grow-1"><i class="fas fa-arrow-left me-1"></i> <span data-key="back">Back</span></a>
                    <button type="submit" class="btn btn-success flex-grow-1"><i class="fas fa-save me-1"></i> <span data-key="save_truck">Save Truck</span></button>
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

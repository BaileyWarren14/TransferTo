@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 data-key="edit_duty_status_log">Edit Duty Status Log</h4>

    <form action="{{ route('driver.duty_status.update', $log->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="status" class="form-label" data-key="status">Status</label>
            <select name="status" id="status" class="form-select" required>
                @foreach(['OFF','SB','D','ON','WT','PC','YM'] as $state)
                    <option value="{{ $state }}" {{ $log->status == $state ? 'selected' : '' }}>
                        {{ $state }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="changed_at" class="form-label" data-key="changed_at">Changed At</label>
            <input type="datetime-local" name="changed_at" id="changed_at" class="form-control" 
                   value="{{ \Carbon\Carbon::parse($log->changed_at)->format('Y-m-d\TH:i') }}" required>
        </div>

        <!-- Location con botón y spinner -->
        <div class="mb-3">
            <label for="location" class="form-label" data-key="location">LOCATION*</label>
            <div class="input-group">
                <input type="text" name="location" id="location" class="form-control rounded-start-pill"
                    placeholder="" value="{{ $log->location }}" data-key="">
                <button type="button" id="getLocation" class="btn btn-primary rounded-end-pill">
                    <i class="fas fa-location-arrow"></i>
                </button>
            </div>
            <div id="locationLoader" style="display:none; text-align:center; margin-top:5px;">
                <div class="spinner"></div>
                <p data-key="getting_location">Getting Location...</p>
            </div>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label" data-key="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ $log->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary" data-key="save_changes">Save Changes</button>
        <a href="{{ route('driver.logs.activities', ['date' => \Carbon\Carbon::parse($log->changed_at)->toDateString()]) }}" class="btn btn-secondary" data-key="cancel">Cancel</a>
    </form>
</div>

<!-- FontAwesome para ícono -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('getLocation').addEventListener('click', () => {
    const locationInput = document.getElementById('location');

    if (navigator.geolocation) {
        Swal.fire({
            title: 'Getting location...',
            html: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const city = data.address.city || data.address.town || data.address.village || '';
                        const state = data.address.state || '';
                        const country = data.address.country || '';
                        locationInput.value = [city, state, country].filter(Boolean).join(', ');
                    } else {
                        locationInput.value = `${lat}, ${lng}`;
                    }
                    Swal.close();
                })
                .catch(err => {
                    console.error("Error getting address:", err);
                    locationInput.value = `${lat}, ${lng}`;
                    Swal.close();
                    Swal.fire('Error', 'Could not retrieve address.', 'error');
                });
        }, err => {
            Swal.close();
            Swal.fire('Error', 'Error getting location: ' + err.message, 'error');
        });
    } else {
        Swal.fire('Error', 'Your browser does not support geolocation.', 'error');
    }
});
</script>

<style>
.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    display: inline-block;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-truck"></i><h2 data-key="truck_information">Truck Information</h2> 
    </h2>

    @if(session('alert_message'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "warning",
                    title: "Notice",
                    text: "{{ session('alert_message') }}"
                });
            });
        </script>
    @elseif($truck)
        <div class="card shadow p-4 dark-card">
            <h4 class="mb-3" data-key="unit">Unit: {{ $truck->unit_number ?? 'N/A' }}</h4>
            <ul class="list-group dark-list">
                <li class="list-group-item dark-item"><strong data-key="plate">Plate:</strong> {{ $truck->license_plate ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="brand">Brand:</strong> {{ $truck->brand ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="model">Model:</strong> {{ $truck->model ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="year">Year:</strong> {{ $truck->year ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="current_mileage">Current Mileage:</strong> {{ $truck->current_mileage ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="fuel_capacity">Fuel Capacity:</strong> {{ $truck->fuel_capacity ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="color">Color:</strong> {{ $truck->color ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="cab_type">Cab Type:</strong> {{ $truck->cab_type ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="transmission">Transmission:</strong> {{ $truck->transmission_type ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong data-key="motor_hours">Motor Hours:</strong> <span id="motor-hours">{{ $truck->current_motor_hours ?? 'N/A' }}</span> h</li>
                <li class="list-group-item dark-item">
                    <strong data-key="status">Status:</strong> 
                    @if($truck->status === 'active')
                        <span class="badge bg-success" data-key="active">Active</span>
                    @elseif($truck->status === 'inactive')
                        <span class="badge bg-danger" data-key="inactive">Inactive</span>
                    @else
                        <span class="badge bg-secondary">{{ $truck->status ?? 'N/A' }}</span>
                    @endif
                </li>
            </ul>
        </div>
    @else
        <div class="alert alert-danger dark-alert">
            <p data-key="no_truck_information_available">No truck information available.</p>
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ url('/driver/dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> <p data-key="back">Back</p>
        </a>
    </div>
</div>
<script src="{{ asset('js/translations.js') }}"></script>
<script>
    function refreshMotorHours() {
    fetch("{{ url('/driver/truck-motor-hours-json') }}")
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById('motor-hours').innerText = data.current_motor_hours;
                
                if(data.current_motor_hours >= 500){ // ejemplo alerta
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maintenance Alert',
                        text: 'This truck has ' + data.current_motor_hours + ' hours. Consider preventive maintenance.'
                    });
                }
            }
        });
    }
    setInterval(refreshMotorHours, 60000); // cada minuto
</script>

{{-- Estilos para modo oscuro --}}
@push('styles')
<style>
    body.dark-mode .dark-card {
        background-color: #1e293b; /* slate-800 */
        color: #e2e8f0; /* slate-200 */
        border: 1px solid #334155; /* slate-700 */
    }
    body.dark-mode .dark-list .dark-item {
        background-color: #0f172a; /* slate-900 */
        color: #e2e8f0; /* slate-200 */
        border-color: #334155;
    }
    body.dark-mode .dark-alert {
        background-color: #7f1d1d; /* rojo oscuro */
        color: #f8fafc; /* blanco */
    }
</style>
@endpush
@endsection

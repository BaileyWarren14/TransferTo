@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-truck"></i> Truck Information
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
            <h4 class="mb-3">Unit: {{ $truck->unit_number ?? 'N/A' }}</h4>
            <ul class="list-group dark-list">
                <li class="list-group-item dark-item"><strong>Plate:</strong> {{ $truck->license_plate ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Brand:</strong> {{ $truck->brand ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Model:</strong> {{ $truck->model ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Year:</strong> {{ $truck->year ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Current Mileage:</strong> {{ $truck->current_mileage ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Fuel Capacity:</strong> {{ $truck->fuel_capacity ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Color:</strong> {{ $truck->color ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Cab Type:</strong> {{ $truck->cab_type ?? 'N/A' }}</li>
                <li class="list-group-item dark-item"><strong>Transmission:</strong> {{ $truck->transmission_type ?? 'N/A' }}</li>
                <li class="list-group-item dark-item">
                    <strong>Status:</strong> 
                    @if($truck->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($truck->status === 'inactive')
                        <span class="badge bg-danger">Inactive</span>
                    @else
                        <span class="badge bg-secondary">{{ $truck->status ?? 'N/A' }}</span>
                    @endif
                </li>
            </ul>
        </div>
    @else
        <div class="alert alert-danger dark-alert">
            No truck information available.
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ url('/driver/dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

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

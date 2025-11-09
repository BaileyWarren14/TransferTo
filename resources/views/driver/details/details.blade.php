
@extends('layouts.app')

@section('content')
<title data-key="fuel_form">Fuel Form</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body.dark-mode .container,
body.dark-mode .card {
    background-color: #1e1e1e;
    color: #f0f0f0;
}

body.dark-mode .form-control {
    background-color: #2a2a2a !important;
    color: #f0f0f0 !important;
    border-color: #444 !important;
}

body.dark-mode .form-label {
    color: #f0f0f0 !important;
}

body.dark-mode .btn-primary {
    background-color: #2a5298 !important;
    border-color: #2a5298 !important;
}
</style>

<div class="text-center mb-3">
    <a href="{{ route('workorder.cistern.index') }}" class="btn btn-info px-4 py-2 rounded-pill">
        <span data-key="return_to_list_work_order_cistern">Return To List Work Order Cistern</span>
    </a>
</div>

<div class="container mt-4">
    <div class="card shadow-lg p-4 rounded-3">
        <h3 class="mb-4 text-center" data-key="fuel_log_form">Fuel Log Form</h3>

        <form action="{{ route('workorder.cistern.store') }}" method="POST">
            @csrf

            <!-- Date -->
            <div class="mb-3">
                <label for="date" class="form-label" data-key="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <!-- BOL Number -->
            <div class="mb-3">
                <label for="bol_number" class="form-label" data-key="bol_number">BOL Number (Bill of Lading)</label>
                <input type="text" class="form-control" id="bol_number" name="bol_number" placeholder="Enter BOL number" required>
            </div>

            <!-- Trailer -->
            <div class="mb-3">
                <label for="trailer" class="form-label">Trailer</label>
                <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Enter trailer number" required>
            </div>

            <!-- From -->
            <div class="mb-3">
                <label for="from" class="form-label" data-key="from">From</label>
                <input type="text" class="form-control" id="from" name="from" placeholder="Enter Origin" required>
            </div>

            <!-- Destination -->
            <div class="mb-3">
                <label for="destination" class="form-label" data-key="destination">Destination</label>
                <input type="text" class="form-control" id="destination" name="destination" placeholder="Enter Destination" required>
            </div>

            <!-- ISO Capacity -->
            <div class="mb-3">
                <label for="iso_capacity" class="form-label" data-key="iso_capacity">ISO Capacity</label>
                <input type="number" step="0.01" class="form-control" id="iso_capacity" name="iso_capacity" placeholder="Capacity" required>
            </div>

            <!-- Inches/Gallon -->
            <div class="mb-3">
                <label for="inches_gallon" class="form-label" data-key="inches_gallon">Inches/Gallon</label>
                <input type="number" step="0.01" class="form-control" id="inches_gallon" name="inches_gallon" placeholder="Enter Inches per gallon" required>
            </div>

            <!-- Mileage Before -->
            <div class="mb-3">
                <label for="mileage_before" class="form-label" data-key="mileage_before">Mileage Before</label>
                <input type="number" step="0.01" class="form-control" id="mileage_before" name="mileage_before"
                       value="{{ $currentMileage ?? '' }}" readonly>
            </div>

            <!-- Hidden fields -->
            <input type="hidden" name="mileage_after" value="0">
            <input type="hidden" name="total_miles" value="0">
            <input type="hidden" name="fuel_dispensed" value="0">

            <!-- Submit -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4" data-key="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

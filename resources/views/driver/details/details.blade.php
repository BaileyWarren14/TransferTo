@extends('layouts.app')


@section('content')
    <title data-key="fuel_form">Fuel Form</title>

    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
/* Dark Mode específico para Fuel Form */
body.dark-mode .container,
body.dark-mode .card {
    background-color: #1e1e1e; /* fondo oscuro */
    color: #f0f0f0;            /* texto claro */
}

body.dark-mode .form-control {
    background-color: #2a2a2a !important; /* inputs oscuros */
    color: #f0f0f0 !important;
    border-color: #444 !important;
}

body.dark-mode .form-control::placeholder {
    color: #bbb !important; /* placeholder más claro */
}

body.dark-mode .form-label {
    color: #f0f0f0 !important;
}

body.dark-mode .btn-primary {
    background-color: #2a5298 !important;
    border-color: #2a5298 !important;
    color: #fff !important;
}

body.dark-mode .btn-primary:hover {
    background-color: #1e3c72 !important;
    border-color: #1e3c72 !important;
}

/* Si quieres que los bordes de los inputs tengan efecto al focus */
body.dark-mode .form-control:focus {
    background-color: #2a2a2a !important;
    color: #f0f0f0 !important;
    border-color: #2a5298 !important;
    box-shadow: 0 0 0 0.2rem rgba(42,82,152,.25);
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

            <form action="{{ route('fuel.store') }}" method="POST">
                @csrf

                <!-- Date -->
                <div class="mb-3">
                    <label for="date" class="form-label" data-key="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <!-- Bill of Lading Number -->
                <div class="mb-3">
                    <label for="bol_number" class="form-label" data-key="bol_number">BOL Number (Bill of Lading)</label>
                    <input type="text" class="form-control" id="bol_number" name="bol_number" data-key="enter_bol_number" placeholder="Enter BOL number" required>
                </div>

                <!-- Trailer -->
                <div class="mb-3">
                    <label for="trailer" class="form-label">Trailer</label>
                    <input type="text" class="form-control" id="trailer" name="trailer" data-key="enter_trailer_number" placeholder="Enter trailer number" required>
                </div>

                <!-- From -->
                <div class="mb-3">
                    <label for="from" class="form-label" data-key="from">From</label>
                    <input type="text" class="form-control" id="from" name="from" data-key="enter_origin" placeholder="Enter Origin" required>
                </div>

                <!-- Destination -->
                <div class="mb-3">
                    <label for="destination" class="form-label" data-key="destination">Destination</label>
                    <input type="text" class="form-control" id="destination" name="destination" data-key="enter_destination" placeholder="Enter Destination" required>
                </div>

                <!-- ISO Capacity -->
                <div class="mb-3">
                    <label for="iso_capacity" class="form-label" data-key="iso_capacity">ISO Capacity</label>
                    <input type="number" step="0.01" class="form-control" id="iso_capacity" name="iso_capacity" data-key="capacity" placeholder="Capacity" required>
                </div>

                <!-- Inches/Gallon -->
                <div class="mb-3">
                    <label for="inches_gallon" class="form-label" data-key="inches_gallon">Inches/Gallon</label>
                    <input type="number" step="0.01" class="form-control" id="inches_gallon" name="inches_gallon" data-key="enter_inches_per_gallon" placeholder="Enter Inches per gallon" required>
                </div>

                <!-- Mileage Before -->
                <div class="mb-3">
                    <label for="mileage_before" class="form-label" data-key="mileage_before">Mileage Before</label>
                    <input type="number" step="0.01" class="form-control" id="mileage_before" name="mileage_before" data-key="enter_mileage_before" placeholder="Enter Mileage before trip" required>
                </div>

                <!-- Mileage After -->
                <div class="mb-3">
                    <label for="mileage_after" class="form-label" data-key="mileage_after">Mileage After</label>
                    <input type="number" step="0.01" class="form-control" id="mileage_after" name="mileage_after"  data-key="enter_mileage_after" placeholder="Mileage after trip" required>
                </div>

                <!-- Total Miles -->
                <div class="mb-3">
                    <label for="total_miles" class="form-label" data-key="total_miles">Total Miles</label>
                    <input type="number" step="0.01" class="form-control" id="total_miles" name="total_miles" data-key="total_miles_driven" placeholder="Total miles driven" required>
                </div>

                <!-- Fuel Dispensed -->
                <div class="mb-3">
                    <label for="fuel_dispensed" class="form-label" data-key="fuel_dispensed">Fuel Dispensed (Gallons)</label>
                    <input type="number" step="0.01" class="form-control" id="fuel_dispensed" name="fuel_dispensed" data-key="enter_fuel_dispensed" placeholder="Enter fuel dispensed" required>
                </div>

                <!-- Submit -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4" data-key="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
    


    <!-- Bootstrap JS desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
 

@endsection
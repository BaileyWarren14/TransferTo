@extends('layouts.app')

@section('content')
<title>Edit Fuel Log</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #0e0e0e;
        color: #f1f1f1;
        font-family: "Poppins", sans-serif;
    }

    .card {
        background: linear-gradient(145deg, #1a1a1a, #1f1f1f);
        border: 1px solid #2a2a2a;
        border-radius: 18px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: scale(1.01);
        box-shadow: 0 0 35px rgba(0, 0, 0, 0.6);
    }

    .form-label {
        color: #bbb;
        font-weight: 500;
    }

    .form-control {
        background-color: #222;
        border: 1px solid #333;
        color: #f8f9fa;
        border-radius: 10px;
        padding: 10px 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        background-color: #2a2a2a;
        border-color: #00aaff;
        box-shadow: 0 0 8px rgba(0, 170, 255, 0.3);
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #00aaff;
        font-size: 1.2rem;
    }

    .input-group-custom {
        position: relative;
    }

    .input-group-custom input {
        padding-left: 40px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        transition: 0.3s;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #0062cc;
        box-shadow: 0 0 10px rgba(0, 170, 255, 0.4);
    }

    .btn-outline-light {
        border-radius: 10px;
    }

    .header-accent {
        background: linear-gradient(90deg, #00aaff, #007bff);
        height: 5px;
        border-radius: 50px;
        margin-bottom: 20px;
    }

    .title {
        text-align: center;
        margin-bottom: 1rem;
        color: #00aaff;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5 fade-in">
    <div class="card p-4 mx-auto" style="max-width: 850px;">
        <div class="header-accent"></div>
        <h3 class="title"><i class="bi bi-pencil-square me-2"></i>Edit Fuel Log</h3>

        <form action="{{ route('workorder.cistern.update', $fuel->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-calendar input-icon"></i>
                    <label class="form-label">Date</label>
                    <input type="date" name="date" value="{{ old('date', $fuel->date) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-upc-scan input-icon"></i>
                    <label class="form-label">BOL Number</label>
                    <input type="text" name="bol_number" value="{{ old('bol_number', $fuel->bol_number) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-truck input-icon"></i>
                    <label class="form-label">Trailer</label>
                    <input type="text" name="trailer" value="{{ old('trailer', $fuel->trailer) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-geo-alt input-icon"></i>
                    <label class="form-label">From</label>
                    <input type="text" name="from" value="{{ old('from', $fuel->from) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-geo-fill input-icon"></i>
                    <label class="form-label">Destination</label>
                    <input type="text" name="destination" value="{{ old('destination', $fuel->destination) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-fuel-pump input-icon"></i>
                    <label class="form-label">ISO Capacity (Gallons)</label>
                    <input type="number" step="0.01" name="iso_capacity" value="{{ old('iso_capacity', $fuel->iso_capacity) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-rulers input-icon"></i>
                    <label class="form-label">Inches per Gallon</label>
                    <input type="number" step="0.01" name="inches_gallon" value="{{ old('inches_gallon', $fuel->inches_gallon) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-speedometer input-icon"></i>
                    <label class="form-label">Mileage Before</label>
                    <input type="number" name="mileage_before" id="mileage_before" value="{{ old('mileage_before', $fuel->mileage_before) }}" class="form-control" required>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-speedometer2 input-icon"></i>
                    <label class="form-label">Mileage After</label>
                    <input type="number" name="mileage_after" id="mileage_after" value="{{ old('mileage_after', $fuel->mileage_after) }}" class="form-control">
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-arrow-left-right input-icon"></i>
                    <label class="form-label">Total Miles</label>
                    <input type="number" id="total_miles" name="total_miles" value="{{ old('total_miles', $fuel->total_miles) }}" class="form-control" readonly>
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-droplet-half input-icon"></i>
                    <label class="form-label">Fuel Dispensed (Gallons)</label>
                    <input type="number" step="0.01" id="fuel_dispensed" name="fuel_dispensed" value="{{ old('fuel_dispensed', $fuel->fuel_dispensed) }}" class="form-control">
                </div>

                <div class="col-md-6 input-group-custom">
                    <i class="bi bi-graph-up input-icon"></i>
                    <label class="form-label">Efficiency (Miles/Gallon)</label>
                    <input type="number" step="0.01" id="efficiency" name="efficiency" value="{{ old('efficiency', $fuel->efficiency) }}" class="form-control" readonly>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('workorder.cistern.index') }}" class="btn btn-outline-light me-2">
                    <i class="bi bi-arrow-left-circle me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save2 me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const before = document.getElementById('mileage_before');
    const after = document.getElementById('mileage_after');
    const total = document.getElementById('total_miles');
    const fuel = document.getElementById('fuel_dispensed');
    const eff = document.getElementById('efficiency');

    function recalc() {
        const beforeVal = parseFloat(before.value) || 0;
        const afterVal = parseFloat(after.value) || 0;
        const fuelVal = parseFloat(fuel.value) || 0;

        const miles = afterVal - beforeVal;
        total.value = miles > 0 ? miles.toFixed(2) : 0;

        eff.value = (fuelVal > 0 && miles > 0) ? (miles / fuelVal).toFixed(2) : 0;
    }

    after.addEventListener('input', recalc);
    fuel.addEventListener('input', recalc);
});
</script>
@endsection

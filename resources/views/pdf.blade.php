<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trip Inspection PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { margin-bottom: 20px; }
        fieldset { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        legend { font-weight: bold; }
        .form-check-label { margin-left: 5px; }
        .row { margin-bottom: 10px; }
        .form-control, .form-check-input { pointer-events: none; }
    </style>
</head>
<body>
<div class="container my-4">
    <h1 class="text-center mb-4">Trip Inspection Report</h1>

    <!-- Type of Inspection -->
    <fieldset>
        <legend>Type of Inspection</legend>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" @if($inspection->pre_trip) checked @endif>
            <label class="form-check-label">Pre-trip Inspection</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" @if($inspection->post_trip) checked @endif>
            <label class="form-check-label">Post-trip Inspection</label>
        </div>
    </fieldset>

    <!-- Truck Info -->
    <fieldset>
        <legend>Truck Information</legend>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Truck/Tractor #</label>
                <input type="text" class="form-control" value="{{ $inspection->truck_number }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Odometer Reading</label>
                <input type="text" class="form-control" value="{{ $inspection->odometer }}">
                <div>
                    <input type="radio" @if($inspection->unit == 'km') checked @endif> Km
                    <input type="radio" @if($inspection->unit == 'miles') checked @endif> Miles
                </div>
            </div>
        </div>
    </fieldset>

    <!-- Condition -->
    <fieldset>
        <legend>Condition</legend>
        <div class="form-check">
            <input type="checkbox" @if($inspection->conditions == 'no_defect') checked @endif>
            <label>No defect detected</label>
        </div>
        <div class="form-check">
            <input type="checkbox" @if($inspection->conditions == 'defects') checked @endif>
            <label>Defects found</label>
        </div>
    </fieldset>

    <!-- Checklist -->
    <fieldset>
        <legend>Inspection Checklist</legend>
        <div class="row">
           @php
                // Asegurarnos de que $checklist sea un array
                $checklist = $inspection->checklist;
                if (!is_array($checklist) || empty($checklist)) {
                    $checklist = []; // Evita que array_chunk falle
                }

                // Calcular tamaño de columna, mínimo 1 si hay elementos
                $chunkSize = max(1, ceil(count($checklist)/3));
                $chunks = array_chunk($checklist, $chunkSize);
            @endphp
            @if(!empty($chunks))
                @foreach($chunks as $column)
                    <div class="col-md-4">
                        @foreach($column as $item)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" checked>
                                <label class="form-check-label">{{ ucfirst(str_replace('_',' ',$item)) }}</label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <p>No checklist items available.</p>
            @endif
        </div>
    </fieldset>

    <!-- Trailer Info -->
    <fieldset>
        <legend>Trailer(s)</legend>
        <div class="row">
            <div class="col-md-6">
                <label>Trailer #1</label>
                <input type="text" class="form-control" value="{{ $inspection->trailer1 }}">
            </div>
            <div class="col-md-6">
                <label>Trailer #2</label>
                <input type="text" class="form-control" value="{{ $inspection->trailer2 }}">
            </div>
        </div>
    </fieldset>

    <!-- Remarks -->
    <fieldset>
        <legend>Remarks</legend>
        <textarea class="form-control">{{ $inspection->remarks }}</textarea>
    </fieldset>

    <!-- Driver Info -->
    <fieldset>
        <div class="row text-center">
            <div class="col-md-4">
                <label>Driver's Name & Signature</label>
                <input type="text" class="form-control" value="{{ $inspection->signature }}">
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <input type="date" class="form-control" value="{{ $inspection->inspection_date }}">
            </div>
            <div class="col-md-4">
                <label>Time</label>
                <input type="time" class="form-control" value="{{ $inspection->inspection_time }}">
            </div>
        </div>
    </fieldset>

    <!-- Carrier/Agent's Report -->
    <fieldset>
        <legend>Carrier/Agent's Report</legend>
        <div class="form-check">
            <input type="checkbox" @if($inspection->above_corrected) checked @endif>
            <label>Above defects corrected.</label>
        </div>
        <div class="form-check">
            <input type="checkbox" @if($inspection->inspection_type == 'post-trip') checked @endif>
            <label>Above defects need not be corrected for safe operation.</label>
        </div>
    </fieldset>

    <fieldset>
        <div class="row text-center">
            <div class="col-md-4">
                <label>Signature</label>
                <input type="text" class="form-control" value="{{ $inspection->signature_agent }}">
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <input type="date" class="form-control" value="{{ $inspection->date_today2 }}">
            </div>
            <div class="col-md-4">
                <label>Time</label>
                <input type="time" class="form-control" value="{{ $inspection->hour_inspection2 }}">
            </div>
        </div>
    </fieldset>

</div>
<div class="footer">
        Generado automáticamente por Transfer-To
    </div>
</body>
</html>

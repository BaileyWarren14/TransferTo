<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trip Inspection Report</title>
    <style>
         body { font-family: "DejaVu Sans", sans-serif; font-size: 10px; color:#222; margin:12px; }
        h1 { text-align:center; margin-bottom:12px; font-size:18px; }
        fieldset { border:1px solid #333; padding:10px; margin-bottom:10px; }
        legend { font-weight:bold; padding:0 6px; }
        .col-md-4 {
        float: left;
        width: 33.3333%;
        box-sizing: border-box;
        padding-right: 6px;
        vertical-align: top;
    }
    .row::after {
        content: "";
        display: table;
        clear: both;
    }
    .form-label { font-weight:bold; display:block; margin-bottom:4px; }
    .check { margin:2px 0; }
        .col-md-6 { float: left;
        width: 50%; /* 2 columnas iguales */
        box-sizing: border-box;
        padding-right: 6px;
        vertical-align: top;}
        .form-label { font-weight: bold;
        display: block;
        margin-bottom: 4px;}
        .check { margin:2px 0; }
        .value-box { border-bottom:1px solid #aaa; padding:4px 2px; min-height:20px; }
        .small { font-size:11px; color:#444; }
        .center { text-align:center; }
    .col-md-3 {
        float: left;
        width: 25%; /* cuatro columnas iguales por fila */
        box-sizing: border-box;
        padding-right: 6px;
        vertical-align: top;
    }
    .footer {
        text-align: center;
        font-size: 10px;
        color: #666;
        margin-top: 20px;
        border-top: 1px solid #ccc;
        padding-top: 5px;
    }
    </style>
</head>
<body>
    <h1>Trip Inspection Report</h1>

    <!-- Type of Inspection -->
    <fieldset>
        <legend>Type of Inspection</legend>
        <p class="check">{{ $inspection->pre_trip ? '☑' : '☐' }} Pre-trip Inspection</p>
        <p class="check">{{ $inspection->post_trip ? '☑' : '☐' }} Post-trip Inspection</p>
    </fieldset>

    <!-- Truck Info -->
    <fieldset>
        <legend>Truck Information</legend>
        <div class="row">
        <div class="col-md-6">
            <label class="form-label">Truck/Tractor #</label>
            <p>{{ $inspection->truck_number }}</p>
        </div>
        <div class="col-md-6">
            <label class="form-label">Odometer Reading</label>
            <p>{{ $inspection->odometer }} 
                {{ $inspection->unit === 'km' ? 'Km' : 'Miles' }}
            </p>
        </div>
    </div>
    </fieldset>

    <!-- Condition -->
    <fieldset>
        <legend>Condition</legend>
        <p class="check">{{ $inspection->conditions === 'no_defect' ? '☑' : '☐' }} I detected no defect or deficiency in this commercial motor vehicle. </p>
        <p class="check">{{ $inspection->conditions === 'defects' ? '☑' : '☐' }} I found the following defects as noted below: </p>
    </fieldset>

    <!-- Checklist -->
    <fieldset>
        <legend>Inspection Checklist</legend>
         @php
            // asegurarnos que $chunks exista y checklist sea array
             $chunks = isset($items) ? array_chunk($items, ceil(count($items)/3)) : [];
            // forzar siempre 3 columnas (rellenar con arrays vacíos)
            $chunks = array_pad($chunks, 3, []);
            $checklist = isset($checklist) && is_array($checklist) ? $checklist : [];
        @endphp

        <div class="row">
            {{-- Forzamos exactamente 3 columnas (si no hay datos, saldrán vacías) --}}
            @for($colIndex = 0; $colIndex < 3; $colIndex++)
                <div class="col-md-4">
                    @php $col = $chunks[$colIndex] ?? []; @endphp
                    @foreach($col as $key)
                        @php
                            $keyNorm = strtolower(trim((string)$key)); // clave del item (ya en items)
                            // verificar si el checklist (guardado por usuario) contiene esta clave
                            $checked = in_array($keyNorm, $checklist, true);
                        @endphp
                        <p class="check">{{ $checked ? '☑' : '☐' }} {{ ucwords(str_replace('_',' ',$key)) }}</p>
                    @endforeach
                </div>
            @endfor
        </div>
    </fieldset>

    <!-- Trailer Info -->
    <fieldset>
        <legend>Trailer(s)</legend>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Trailer #1</label>
                <p>{{ $inspection->trailer1 }}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label">Trailer #2</label>
                <p>{{ $inspection->trailer2 }}</p>
            </div>
        </div>
    </fieldset>

    <!-- Remarks -->
    <fieldset>
        <legend>Remarks</legend>
        <p>{{ $inspection->remarks }}</p>
    </fieldset>

<!-- Driver -->
<fieldset>
    <legend>Driver</legend>
    <div class="row">
        <div class="col-md-4">
            <label class="form-label">Name & Signature</label>
            <p>{{ $inspection->signature }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label">Date</label>
            <p>{{ $inspection->inspection_date }}</p>
        </div>
        <div class="col-md-4">
            <label class="form-label">Time</label>
            <p>{{ $inspection->inspection_time }}</p>
        </div>
    </div>
</fieldset>

<!-- Carrier/Agent Report -->
<fieldset>
    <legend>Carrier/Agent's Report</legend>

    <!-- Primera fila: checkboxes -->
    <div class="row">
        <div class="col-md-6">
            <p class="check">{{ $inspection->above_corrected ? '☑' : '☐' }} Above defects corrected</p>
        </div>
        <div class="col-md-6">
            <p class="check">{{ $inspection->above_not_corrected ? '☑' : '☐' }} Defects need not be corrected</p>
        </div>
    </div>

    <!-- Segunda fila: firma y fecha/hora -->
    <div class="row">
        <div class="col-md-6">
            <label class="form-label">Agent Signature</label>
            <p>{{ $inspection->signature_agent }}</p>
        </div>
        <div class="col-md-6">
            <label class="form-label">Date / Time</label>
            <p>{{ $inspection->date_today2 }} / {{ $inspection->hour_inspection2 }}</p>
        </div>
    </div>
</fieldset>
<div class="footer">
    Created by Transfer To
</div
</body>
</html>

@extends('layouts.app')

@section('content')
  <title>Trip Inspection Report</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Responsivo -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--  <link href="{{ asset('js/inspection.css') }}" rel="stylesheet">  -->

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="{{ asset('js/inspection.js') }}" defer></script>
    
<style>
  /* Dark mode para radios dentro de input-group */
body.dark-mode .input-group .form-check-input {
    background-color: #1e1e1e; /* fondo del círculo */
    border-color: #333; /* borde del círculo */
    accent-color: #2a5298; /* color cuando está seleccionado */
}

body.dark-mode .input-group .form-check-label {
    color: #f0f0f0; /* color del texto */
}

/* Opcional: el contenedor de los radios */
body.dark-mode .input-group .input-group-text {
    background-color: #1e1e1e;
    border-color: #333;
    color: #f0f0f0;
}

</style>



  @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <form id="myForm" method="POST" action="{{ route('inspections.store') }}">
      @csrf
  <div class="container my-4" id="report">
    <h1 class="text-center mb-4">Trip Inspection Report</h1>

    



      <!-- Type of Inspection -->
      <fieldset class="mb-3">
        <legend class="fw-bold">Type of Inspection</legend>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="pre_trip" value="pre-trip" id="pretrip">
          <label class="form-check-label" for="pretrip">Pre-trip Inspection</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="post_trip" value="post-trip" id="posttrip">
          <label class="form-check-label" for="posttrip">Post-trip Inspection</label>
        </div>
      </fieldset>

      <!-- Truck Info -->
  <fieldset class="mb-3">
    <legend class="fw-bold">Truck Information</legend>
    <div class="row g-2">
      <div class="col-md-6">
        <label for="truck_number" class="form-label">Truck/Tractor #</label>
        <input type="text" class="form-control" id="truck_number" name="truck_number">
      </div>
      <div class="col-md-6">
        <label for="odometer" class="form-label">Odometer Reading</label>
        <div class="input-group">
          <input type="text" class="form-control" id="odometer" name="odometer">
          <div class="input-group-text">
            <div class="form-check form-check-inline mb-0">
              <input class="form-check-input" type="radio" name="unit" value="km" id="km">
              <label class="form-check-label" for="km">Km</label>
            </div>
            <div class="form-check form-check-inline mb-0">
              <input class="form-check-input" type="radio" name="unit" value="miles" id="miles">
              <label class="form-check-label" for="miles">Miles</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </fieldset>

      <!-- Condition -->
      <fieldset class="mb-3">
        <legend class="fw-bold">Condition</legend>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="conditions" value="no_defect" id="nodefect">
          <label class="form-check-label" for="nodefect">
            I detected no defect or deficiency in this commercial motor vehicle.
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="conditions" value="defects" id="defects">
          <label class="form-check-label" for="defects">
            I found the following defects as noted below:
          </label>
        </div>
      </fieldset>

      <!-- Checklist -->
      <fieldset class="mb-3">
        <legend class="fw-bold">Inspection Checklist</legend>
         <div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" id="check_all">
    <label class="form-check-label fw-bold" for="check_all">
      Select / Deselect All
    </label>
  </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="air_compressor" id="air_compressor" name="checklist[]"><label class="form-check-label" for="air_compressor">Air Compressor</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="air_lines" id="air_lines" name="checklist[]"><label class="form-check-label" for="air_lines">Air Lines</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="axles" id="axles" name="checklist[]"><label class="form-check-label" for="axles">Axles</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="battery" id="battery" name="checklist[]"><label class="form-check-label" for="battery">Battery(s)</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="belts" id="belts" name="checklist[]"><label class="form-check-label" for="belts">Belts/Hoses</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="body_frame" id="body_frame" name="checklist[]"><label class="form-check-label" for="body_frame">Body/Frame</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="brakes_adjustment" id="brakes_adjustment" name="checklist[]"><label class="form-check-label" for="brakes_adjustment">Brakes/Adjustment</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="brakes_service" id="brakes_service" name="checklist[]"><label class="form-check-label" for="brakes_service">Brakes - Service System</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="brakes_parking" id="brakes_parking" name="checklist[]"><label class="form-check-label" for="brakes_parking">Brakes - Parking System</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="charging_system" id="charging_system" name="checklist[]"><label class="form-check-label" for="charging_system">Charging System</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="clutch" id="clutch" name="checklist[]"><label class="form-check-label" for="clutch">Clutch</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="cooling_system" id="cooling_system" name="checklist[]"><label class="form-check-label" for="cooling_system">Cooling System</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="coupling_devices" id="coupling_devices" name="checklist[]"><label class="form-check-label" for="coupling_devices">Coupling Devices</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="documents" id="documents" name="checklist[]"><label class="form-check-label" for="documents">Documents (insurance, permits, etc.)</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="doors" id="doors" name="checklist[]"><label class="form-check-label" for="doors">Doors/Compartments</label></div>
            

          </div>

          <div class="col-md-4">
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="drive_lines" id="drive_lines" name="checklist[]"><label class="form-check-label" for="brakdrive_lineses_adjustment">Drive Lines</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="emergency_equipment" id="emergency_equipment" name="checklist[]"><label class="form-check-label" for="emergency_equipment">Emergency Equipment</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="emergency_windows" id="emergency_windows" name="checklist[]"><label class="form-check-label" for="emergency_windows">Emergency Windows/Exits</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="engine" id="engine" name="checklist[]"><label class="form-check-label" for="engine">Engine</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="exhaust_system" id="exhaust_system" name="checklist[]"><label class="form-check-label" for="exhaust_system">Exhaust System</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="fire_extinguishers" id="fire_extinguishers" name="checklist[]"><label class="form-check-label" for="fire_extinguishers">Fire Extinguishers</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="first_aid" id="first_aid" name="checklist[]"><label class="form-check-label" for="first_aid">First Aid Kit</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="fluid_leaks" id="fluid_leaks" name="checklist[]"><label class="form-check-label" for="fluid_leaks">Fluid Leaks</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="frame" id="frame" name="checklist[]"><label class="form-check-label" for="frame">Frame</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="fuel_system" id="fuel_system" name="checklist[]"><label class="form-check-label" for="fuel_system">Fuel System</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="heater" id="heater" name="checklist[]"><label class="form-check-label" for="heater">Heater/Defrosters</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="horns" id="horns" name="checklist[]"><label class="form-check-label" for="horns">Horns</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="inspection_decals" id="inspection_decals" name="checklist[]"><label class="form-check-label" for="inspection_decals">Inspection Decal/Licence Plates</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="interior_ligths" id="interior_ligths" name="checklist[]"><label class="form-check-label" for="interior_ligths">Interior Ligths</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="lights_reflectors" id="lights_reflectors" name="checklist[]"><label class="form-check-label" for="lights_reflectors">Lights/Reflectors</label></div>   
          </div>

          <div class="col-md-4">
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="load_security_device" id="load_security_device" name="checklist[]"><label class="form-check-label" for="load_security_device">Load Security Devices</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="lubrication_system" id="lubrication_system" name="checklist[]"><label class="form-check-label" for="lubrication_system">Lubrication System(s)</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="mirrows" id="mirrows" name="checklist[]"><label class="form-check-label" for="mirrows">Mirrows</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="mud_flaps" id="mud_flaps" name="checklist[]"><label class="form-check-label" for="mud_flaps">Mud Flaps</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="oil_pressure" id="oil_pressure" name="checklist[]"><label class="form-check-label" for="oil_pressure">Oil Pressure</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="rear_end" id="rear_end" name="checklist[]"><label class="form-check-label" for="rear_end">Rear End</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="recording_devices" id="recording_devices" name="checklist[]"><label class="form-check-label" for="recording_devices">Recording Device(s)</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="seats" id="seats" name="checklist[]"><label class="form-check-label" for="seats">Seats</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="suspension" id="suspension" name="checklist[]"><label class="form-check-label" for="suspension">Suspension</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="steering_mechanism" id="steering_mechanism" name="checklist[]"><label class="form-check-label" for="steering_mechanism">Steering Mechanism</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="transmission" id="transmission" name="checklist[]"><label class="form-check-label" for="transmission">Transmission(s)</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="wheels_tires" id="wheels_tires" name="checklist[]"><label class="form-check-label" for="wheels_tires">Wheels/Tires/Studs</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="windows" id="windows" name="checklist[]"><label class="form-check-label" for="windows">Windows/Visibility</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="wipers" id="wipers" name="checklist[]"><label class="form-check-label" for="wipers">Wipers/Washers</label></div>
            <div class="form-check"><input class="form-check-input checklist" type="checkbox" value="other" id="other"name="checklist[]"><label class="form-check-label" for="other">Other</label></div>
          </div>
        </div>
      </fieldset>

      <!-- Trailer Info -->
      <fieldset class="mb-3">
        <legend class="fw-bold">Trailer(s)</legend>
        <div class="row g-2">
          <div class="col-md-6">
            <label for="trailer1" class="form-label">Trailer #1</label>
            <input type="text" class="form-control" id="trailer1" name="trailer1">
          </div>
          <div class="col-md-6">
            <label for="trailer2" class="form-label">Trailer #2</label>
            <input type="text" class="form-control" id="trailer2" name="trailer2">
          </div>
          
        </div>
      </fieldset>

      <!-- Remarks -->
      <fieldset class="mb-3">
        <legend class="fw-bold">Remarks</legend>
        <textarea class="form-control" name="remarks" rows="4"></textarea>
      </fieldset>

      <fieldset class="mb-3">
  <div class="row g-3 align-items-center text-center">

    <!-- Driver Name & Signature -->
    <div class="col-md-4">
      <input type="text" name="signature" id="signature" class="form-control" placeholder="Enter name & signature">
      <label for="signature" class="form-label d-block">Driver's Name & Signature</label>
    </div>

    <!-- Date -->
    <div class="col-md-4">
      <input type="date" name="inspection_date" id="inspection_date" class="form-control">
      <label for="inspection_date" class="form-label d-block">Date</label>
    </div>

    <!-- Time -->
    <div class="col-md-4">
      <input type="time" name="inspection_time" id="inspection_time" class="form-control">
      <label for="inspection_time" class="form-label d-block">Time</label>
    </div>

  </div>
</fieldset>

       
      <!-- Carrier Agent Report -->
      <fieldset class="mb-3">
        <legend class="fw-bold">Carrier/Agent's Report</legend>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="above_corrected" value="above_corrected" id="above_corrected">
          <label class="form-check-label" for="above_corrected">Above defects corrected.</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="above_not_corrected" value="above_not_corrected-trip" id="above_not_corrected">
          <label class="form-check-label" for="above_not_corrected">Above defects need not be corrected for safe operation of vehicle.</label>
        </div>
      </fieldset>

      <fieldset class="mb-3">
          <div class="row g-3 align-items-center text-center">

            <!-- Agent Name & Signature -->
            <div class="col-md-4">
            <input type="text" name="signature_agent" id="signature_agent" class="form-control" placeholder="Enter signature">
            <label for="signature" class="form-label d-block">Signature</label>
            </div>

            <!-- Date -->
            <div class="col-md-4">
            <input type="date" name="date_today2" id="date_today2" class="form-control">
            <label for="date_today" class="form-label d-block">Date</label>
            </div>

            <!-- Time -->
            <div class="col-md-4">
            <input type="time" name="hour_inspection2" id="hour_inspection2" class="form-control">
            <label for="hour_inspection" class="form-label d-block">Time</label>
            </div>

        </div>
        </fieldset>

      <button type="submit"   class="btn btn-primary w-100" >Submit Report</button>
    
  </div>
    </form>
    <script>
     document.getElementById('myForm').addEventListener('submit', function(e){
    e.preventDefault(); // evitar envío normal

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
                title: 'Inspection Saved!',
                text: "Do you want to download the PDF report?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Download PDF',
                cancelButtonText: 'Close'
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "/inspections/pdf/" + data.inspection_id;
                }
            });
        } else {
            Swal.fire('Error', 'Failed to save inspection', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', err.message, 'error');
    });
});

</script>
  
  
@endsection

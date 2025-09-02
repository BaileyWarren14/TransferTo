<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Trip Inspection Report</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
  <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Trip Inspection Report</h1>

    <form action="#" method="post" class="space-y-6">

      <!-- Type of Inspection -->
      <fieldset class="border p-4 rounded-lg">
        <legend class="font-semibold">Type of Inspection</legend>
        <div class="flex flex-wrap gap-4 mt-2">
          <label class="flex items-center gap-2">
            <input type="radio" name="inspection_type" value="pre-trip" class="w-4 h-4"> Pre-trip Inspection
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="inspection_type" value="post-trip" class="w-4 h-4"> Post-trip Inspection
          </label>
        </div>
      </fieldset>

      <!-- Truck Info -->
      <fieldset class="border p-4 rounded-lg">
        <legend class="font-semibold">Truck Information</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
          <label class="flex flex-col">
            Truck/Tractor #
            <input type="text" name="truck_number" class="border rounded-lg p-2 mt-1">
          </label>
          <label class="flex flex-col">
            Odometer Reading
            <input type="text" name="odometer" class="border rounded-lg p-2 mt-1">
          </label>
        </div>
        <div class="flex gap-6 mt-4">
          <label class="flex items-center gap-2">
            <input type="radio" name="unit" value="km" class="w-4 h-4"> Km
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="unit" value="miles" class="w-4 h-4"> Miles
          </label>
        </div>
      </fieldset>

      <!-- Condition -->
      <fieldset class="border p-4 rounded-lg">
        <legend class="font-semibold">Condition</legend>
        <div class="flex flex-col gap-2 mt-2">
          <label class="flex items-center gap-2">
            <input type="radio" name="condition" value="no_defect" class="w-4 h-4">
            I detected no defect or deficiency in this commercial motor vehicle.
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="condition" value="defects" class="w-4 h-4">
            I found the following defects as noted below:
          </label>
        </div>
      </fieldset>

      <!-- Checklist -->
      <fieldset class="border p-4 rounded-lg">
        <legend class="font-semibold">Inspection Checklist</legend>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
          <!-- Lista de checkboxes -->
          <label><input type="checkbox" name="items" value="air_compressor"> Air Compressor</label>
          <label><input type="checkbox" name="items" value="air_lines"> Air Lines</label>
          <label><input type="checkbox" name="items" value="axles"> Axles</label>
          <label><input type="checkbox" name="items" value="battery"> Battery(s)</label>
          <label><input type="checkbox" name="items" value="belts"> Belts/Hoses</label>
          <label><input type="checkbox" name="items" value="body_frame"> Body/Frame</label>
          <label><input type="checkbox" name="items" value="brakes_adjustment"> Brakes/Adjustment</label>
          <label><input type="checkbox" name="items" value="brakes_service"> Brakes - Service System</label>
          <label><input type="checkbox" name="items" value="brakes_parking"> Brakes - Parking System</label>
          <label><input type="checkbox" name="items" value="charging"> Charging System</label>
          <label><input type="checkbox" name="items" value="clutch"> Clutch</label>
          <label><input type="checkbox" name="items" value="cooling"> Cooling System</label>
          <label><input type="checkbox" name="items" value="coupling"> Coupling Devices</label>
          <label><input type="checkbox" name="items" value="documents"> Documents</label>
          <label><input type="checkbox" name="items" value="doors"> Doors/Compartments</label>
          <label><input type="checkbox" name="items" value="drive_lines"> Drive Lines</label>
          <label><input type="checkbox" name="items" value="emergency_equipment"> Emergency Equipment</label>
          <label><input type="checkbox" name="items" value="engine"> Engine</label>
          <label><input type="checkbox" name="items" value="exhaust"> Exhaust System</label>
          <label><input type="checkbox" name="items" value="fire_extinguishers"> Fire Extinguishers</label>
          <label><input type="checkbox" name="items" value="first_aid"> First Aid Kit</label>
          <label><input type="checkbox" name="items" value="fluid_leaks"> Fluid Leaks</label>
          <label><input type="checkbox" name="items" value="fuel_system"> Fuel System</label>
          <label><input type="checkbox" name="items" value="horns"> Horns</label>
          <label><input type="checkbox" name="items" value="inspection_decals"> Inspection Decal/Licence Plates</label>
          <label><input type="checkbox" name="items" value="lights_reflectors"> Lights/Reflectors</label>
          <label><input type="checkbox" name="items" value="load_security"> Load Security Devices</label>
          <label><input type="checkbox" name="items" value="mirrors"> Mirrors</label>
          <label><input type="checkbox" name="items" value="mud_flaps"> Mud Flaps</label>
          <label><input type="checkbox" name="items" value="oil_pressure"> Oil Pressure</label>
          <label><input type="checkbox" name="items" value="suspension"> Suspension</label>
          <label><input type="checkbox" name="items" value="steering"> Steering Mechanism</label>
          <label><input type="checkbox" name="items" value="transmission"> Transmission(s)</label>
          <label><input type="checkbox" name="items" value="wheels_tires"> Wheels/Tires/Studs</label>
          <label><input type="checkbox" name="items" value="windows"> Windows/Visibility</label>
          <label><input type="checkbox" name="items" value="wipers"> Wipers/Washers</label>
          <label><input type="checkbox" name="items" value="other"> Other</label>
        </div>
      </fieldset>

      <!-- Trailer Info -->
      <fieldset class="border p-4 rounded-lg">
        <legend class="font-semibold">Trailer(s)</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
          <label class="flex flex-col">
            Trailer #1
            <input type="text" name="trailer1" class="border rounded-lg p-2 mt-1">
          </label>
          <label class="flex flex-col">
            Trailer #2
            <input type="text" name="trailer2" class="border rounded-lg p-2 mt-1">
          </label>
        </div>
      </fieldset>

      <!-- Remarks -->
      <fieldset class="border p-4 rounded-lg">
        <legend class="font-semibold">Remarks</legend>
        <textarea name="remarks" rows="4" class="w-full border rounded-lg p-2 mt-1"></textarea>
      </fieldset>

      <!-- Submit -->
      <div class="text-center">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
          Submit Report
        </button>
      </div>
    </form>
  </div>
</body>
</html>

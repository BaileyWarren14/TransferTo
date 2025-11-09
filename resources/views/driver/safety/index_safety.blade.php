@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="card shadow p-4 dark-card">
        <h2 class="mb-3" data-key="safety_title">Safety</h2>
        <p class="text-muted mb-4" data-key="safety_intro">Follow these safety recommendations to ensure a secure trip.</p>

        <h4 class="mt-3" data-key="driver_safety_title">Driver Safety (70%)</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item" data-key="safety_driver_1">Do not drive if you feel tired or drowsy.</li>
            <li class="list-group-item" data-key="safety_driver_2">Always wear your seat belt while driving.</li>
            <li class="list-group-item" data-key="safety_driver_3">Respect speed limits and traffic signs.</li>
            <li class="list-group-item" data-key="safety_driver_4">Avoid distractions such as using your phone while driving.</li>
            <li class="list-group-item" data-key="safety_driver_5">Take regular breaks on long trips.</li>
            <li class="list-group-item" data-key="safety_driver_6">Stay hydrated and eat light meals during the trip.</li>
            <li class="list-group-item" data-key="safety_driver_7">Perform a quick personal check before starting your shift (fatigue, stress, alcohol, medication).</li>
        </ul>

        <h4 class="mt-3" data-key="truck_safety_title">Truck Safety (30%)</h4>
        <ul class="list-group">
            <li class="list-group-item" data-key="safety_truck_1">Check tire pressure and condition before each trip.</li>
            <li class="list-group-item" data-key="safety_truck_2">Inspect lights, brakes, and mirrors regularly.</li>
            <li class="list-group-item" data-key="safety_truck_3">Report any mechanical issues immediately to maintenance.</li>
            <li class="list-group-item" data-key="safety_truck_4">Ensure cargo is properly secured before departure.</li>
        </ul>
    </div>
</div>
@endsection
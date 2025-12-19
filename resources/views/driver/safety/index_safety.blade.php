@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0 
                main-card
                transition-colors">

        <div class="card-body">
            <!-- Título principal más grande -->
            <h1 class="mb-4 fw-bold fs-1" data-key="safety_title">Safety</h1>
            <p class="text-adaptive mb-4" data-key="safety_intro">
                Follow these safety recommendations to ensure a secure trip.
            </p>

            <!-- DRIVER SAFETY -->
            <!-- Menos espacio entre títulos -->
            <h4 class="mt-2 fw-semibold mb-3" data-key="driver_safety_title">Driver Safety (70%)</h4>
            <ul class="list-group mb-4">
                @foreach([
                    'Do not drive if you feel tired or drowsy.',
                    'Always wear your seat belt while driving.',
                    'Respect speed limits and traffic signs.',
                    'Avoid distractions such as using your phone while driving.',
                    'Take regular breaks on long trips.',
                    'Stay hydrated and eat light meals during the trip.',
                    'Perform a quick personal check before starting your shift (fatigue, stress, alcohol, medication).'
                ] as $index => $item)
                <li class="list-group-item 
                           bg-white dark-item
                           text-dark dark-text
                           border-0 mb-2 
                           rounded shadow-sm 
                           d-flex align-items-start
                           transition
                           hover-effect">
                    <span class="me-2 text-primary">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                    <span data-key="safety_driver_{{ $index + 1 }}">{{ $item }}</span>
                </li>
                @endforeach
            </ul>

            <!-- TRUCK SAFETY -->
            <h4 class="mt-2 fw-semibold mb-3" data-key="truck_safety_title">Truck Safety (30%)</h4>
            <ul class="list-group">
                @foreach([
                    'Check tire pressure and condition before each trip.',
                    'Inspect lights, brakes, and mirrors regularly.',
                    'Report any mechanical issues immediately to maintenance.',
                    'Ensure cargo is properly secured before departure.'
                ] as $index => $item)
                <li class="list-group-item 
                           bg-white dark-item
                           text-dark dark-text
                           border-0 mb-2 
                           rounded shadow-sm 
                           d-flex align-items-start
                           transition
                           hover-effect">
                    <span class="me-2 text-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </span>
                    <span data-key="safety_truck_{{ $index + 1 }}">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
.transition { transition: all 0.3s ease; }

.hover-effect {
    transition: all 0.3s ease;
}

/* Hover modo claro */
.hover-effect:hover {
    background-color: #e9f0ff; /* ligero azul claro */
    color: #000 !important;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
}

/* Hover modo oscuro */
body.dark-mode .hover-effect:hover {
    background-color: #2c2f38 !important;
    color: #e9ecef !important;
    box-shadow: 0 0 15px rgba(0, 123, 255, 0.4);
}


.main-card {
    background-color: #ffffff;
    color: #212529;
}
body.dark-mode .main-card {
    background-color: #1c1f26 !important;
    color: #e9ecef !important;
}

.dark-item { background-color: #ffffff; }
.dark-text { color: #212529; }

body.dark-mode .dark-item { background-color: #262a33 !important; }
body.dark-mode .dark-text { color: #e9ecef !important; }

.text-adaptive {
    color: #6c757d; /* color claro normal */
    transition: color 0.3s ease;
}

body.dark-mode .text-adaptive {
    color: #adb5bd; /* color para dark mode */
}

</style>
@endsection

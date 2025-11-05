@extends('layouts.app')

@section('content')

<style>
    .vehicle-sidebar {
        position: fixed;
        top: 0;
        right: -50%; /* Oculto inicialmente */
        width: 50%;
        height: 100%;
        background-color: #212529;
        color: white;
        z-index: 1050;
        box-shadow: -4px 0 12px rgba(0, 0, 0, 0.4);
        transition: right 0.4s ease;
        overflow-y: auto;
        border-left: 3px solid #0d6efd;
    }

    .vehicle-sidebar.active {
        right: 0;
    }

    .vehicle-sidebar .sidebar-header {
        background-color: #0d6efd;
        color: white;
        padding: 1rem;
    }

    .vehicle-sidebar .btn-outline-light {
        border: none;
        font-size: 1.5rem;
        color: white;
    }

    .vehicle-sidebar .btn-outline-light:hover {
        color: #ccc;
    }
</style>



 <a href="{{ route('driver.logs.log_book') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> <span data-key="back_to_logbook">Back to logbook</span>
    </a>

<div class="container mt-4">

<!-- Contenedor superior de estado -->
<div class="card shadow-sm mb-4 p-3 d-flex flex-row justify-content-between align-items-center">

   <div class="d-flex align-items-center flex-grow-1">
        <div id="statusCircle"
            class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
            style="width: 60px; height: 60px; font-size: 0.9rem; background-color: gray;">
            OFF
        </div>

        <div class="ms-3">
            <h6 id="statusText" class="mb-1">OFF DUTY</h6>
            <small id="statusDuration" class="text-muted">0h 00m</small>
        </div>
    </div>

    <!-- Derecha: Contenedor del botón de vehículo -->
    <div class="d-flex justify-content-end align-items-center" style="min-width: 250px;">
        <button id="vehicleCard" type="button" 
        class="btn w-100 text-end bg-light px-4 py-2 rounded shadow-sm d-flex align-items-center border-0"
        data-bs-toggle="modal" data-bs-target="#truckModal"> 
            <div class="me-2 text-end flex-grow-1"> 
                <h6 id="truckPlate" class="mb-0 fw-bold">
                    {{ $assignedTruck ? $assignedTruck->license_plate : 'No Truck Assigned' }}
                </h6> 
                <small class="text-muted">
                    Current Vehicle
                </small>
            </div> 
            <i class="fas fa-exchange-alt fa-lg text-primary">

            </i>
        </button>
    </div>
</div>


    <!-- Hoy -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span data-key="today">Today</span>
            <a href="{{ route('driver.logs.activities', ['date' => \Carbon\Carbon::now('America/Mexico_City')->toDateString()]) }}" class="btn btn-light btn-sm">➡️</a>
        </div>
        <div class="card-body text-center">
            <h5>{{ \Carbon\Carbon::now()->format('l, M d, Y') }}</h5>

            <p>{{ $totalOnDutyHours }} hr {{ $totalOnDutyMins }} min</p>
            <div class="d-flex justify-content-between align-items-start">
                 <!-- Gráfica -->
                <div class="chart-container" style="height:200px; flex: 0 0 95%; max-width:95%; min-width:90%">
                    <canvas id="logbookChart"></canvas>
                </div>

                <!-- Resumen compacto al lado derecho -->
                <div class="state-summary" 
                    style="flex: 0 0 5%; max-width:5%; min-width:10%; font-size:0.65rem; text-align:left; margin-left:5px; line-height:3.7;">
                    <ul id="stateSummaryList" class="list-unstyled mb-0"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos 14 días -->
    <div class="card shadow mb-4">
        <div class="card-header bg-secondary text-white" ><span data-key="last_days">Last 14 Days</span></div>
        <div class="card-body">
            @foreach($last14Days as $date => $logs)
                @php
                    $firstOff = null;
                    $totalMinutes = 0;
                    foreach($logs as $log){
                        if($log->status === 'OFF'){
                            $firstOff = \Carbon\Carbon::parse($log->changed_at);
                        } else {
                            if($firstOff){
                                
                                $totalMinutes += \Carbon\Carbon::parse($log->changed_at)->diffInMinutes($firstOff);
                                $firstOff = null;
                            }
                        }
                    }
                    $hours = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;
                @endphp
                <div class="d-flex justify-content-between align-items-center border p-2 mb-2 rounded">
                    <div>
                        <strong>{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</strong><br>
                        {{ $hours }} hr {{ $minutes }} min
                    </div>
                    <a href="{{ route('driver.logs.activities', ['date' => $date]) }}" class="btn btn-primary btn-sm">➡️</a>
                </div>
            @endforeach
        </div>
    </div>

</div>



<!-- Modal de selección de camión -->
<div class="modal fade" id="truckModal" tabindex="-1" aria-labelledby="truckModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="truckModalLabel">Select a Truck</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div id="vehicleList" class="list-group mb-3 text-dark">
          <div class="text-center text-muted py-3" id="loadingTrucks">Loading trucks...</div>
        </div>
      </div>

      <div class="modal-footer border-0">
        <button id="confirmTruck" class="btn btn-primary w-100" disabled>
          Confirm Selection
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 📌 Recibimos directamente desde PHP 
    const labels = @json($labels);
    const duty_status = @json($dutyStatuses);
    const rawLogs = @json($rawLogs);
    
    // Debug temporal
    /*alert(
        "📌 Registros originales de la BD:\n" +
        JSON.stringify(rawLogs, null, 2) +
        "\n\n📌 Labels (96 bloques):\n" +
        JSON.stringify(labels) +
        "\n\n📌 Duty Statuses (96 valores):\n" +
        JSON.stringify(duty_status)
    );*/

    // Opcional: debug
    console.log("Registros originales:", rawLogs);
    console.log("96 bloques:", labels);
    console.log("Estados de cada bloque:", duty_status);

    // Categorías del eje Y
    const yCategories = ['OFF', 'SB', 'D', 'ON', 'WT'];

    const minutesPerBlock = 1;
    const stateTimes = { 'OFF':0, 'SB':0, 'D':0, 'ON':0, 'WT':0 };

    // Calcular tiempo por estado
    duty_status.forEach(statusIndex => {
         if (statusIndex === null) return; // ignorar bloques futuros
        const state = yCategories[statusIndex];
        stateTimes[state] += minutesPerBlock;
    });

    // 🔹 Mostrar todos los estados aunque estén en 0
    const stateSummary = [];
    let totalMinutes = 0;

    for (const [state, mins] of Object.entries(stateTimes)) {
        const hours = Math.floor(mins / 60);
        const remainingMins = mins % 60;

        // Aquí quitamos el nombre del estado y solo mostramos tiempo
        stateSummary.push(`${hours}h ${remainingMins}m`);
        totalMinutes += mins;
    }

    // 🔹 Total general
    //const totalHours = Math.floor(totalMinutes / 60);
    //const totalMins = totalMinutes % 60;
    //stateSummary.push(`Total: ${totalHours}h ${totalMins}m`);

    // Llenar lista compacta
    const summaryList = document.getElementById('stateSummaryList');
    stateSummary.forEach(text => {
        const li = document.createElement('li');
        li.textContent = text;
        summaryList.appendChild(li);
    });

    const ctx = document.getElementById('logbookChart').getContext('2d');
    new Chart(ctx, {
          type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Driver Status',
                    data: duty_status,       // 96 valores
                    borderColor: 'blue',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0,
                    stepped: true,    // línea escalonada
                    fill: false,
                    
                }]
              
    },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2.5,
            scales: {
                y: {
                    type: 'linear',
                    min: 0,
                    max: 4,
                    reverse: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return yCategories[value] ?? value;
                        }
                    },
                    grid: { drawTicks: true, color: '#ccc' }
                    },
                x: {
                    grid: {
                        drawTicks: true,
                        tickLength: 5,
                        color: ctx => {
                            if (ctx.index % 60 === 0) {
                                return '#444'; // línea fuerte cada 60
                            } else if (ctx.index % 15 === 0) {
                                return '#aaa'; // línea fina cada 15
                            } else {
                                return 'transparent'; // no dibujar
                            }
                        },
                        borderColor: '#333'
                    },
                    ticks: {
                        autoSkip: false,
                        callback: function(value, index) {
                            // Mostrar solo las etiquetas de cada hora (cada 4 bloques de 15 min)
                            return index % 60 === 0 ? labels[index] : '';
                        },
                        font: { size: 10 },
                        maxRotation: 0,
                        minRotation: 0
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Status: ${yCategories[context.raw]} - ${labels[context.dataIndex]}`;
                        }
                    }
                }
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    const statusDisplay = document.getElementById('statusDisplay');
    const timeDisplay = document.getElementById('timeDisplay');
    const vehicleCard = document.getElementById('vehicleCard');

    async function fetchCurrentStatus() {
        try {
            const response = await fetch("{{ route('driver.status') }}");
            const data = await response.json();
            
            if (data.status) {
                statusDisplay.textContent = data.status;
                timeDisplay.textContent = data.duration + ' desde último cambio';
            }
        } catch (error) {
            console.error('Error al obtener el estado:', error);
        }
    }

    // Cargar estado actual al iniciar
    fetchCurrentStatus();

    // Actualizar cada 60 segundos
    setInterval(fetchCurrentStatus, 60000);

    
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const vehicleList = document.getElementById('vehicleList');
    const confirmButton = document.getElementById('confirmTruck');
    const truckPlate = document.getElementById('truckPlate');
    const modal = document.getElementById('truckModal');

    let selectedTruckId = null;

    // Load available trucks when modal opens
    modal.addEventListener('show.bs.modal', async function () {
        vehicleList.innerHTML = '<div class="text-center text-muted py-3">Loading trucks...</div>';
        confirmButton.disabled = true;
        selectedTruckId = null;

        try {
            const response = await fetch('{{ route("driver.trucks.available") }}');
            const trucks = await response.json();

            if (trucks.length === 0) {
                vehicleList.innerHTML = '<div class="text-center text-muted py-3">No trucks available.</div>';
                return;
            }

            vehicleList.innerHTML = trucks.map(truck => `
                <button class="list-group-item list-group-item-action bg-dark text-white border-secondary mb-2"
                        data-id="${truck.id}">
                    <strong>${truck.license_plate}</strong> — ${truck.brand} ${truck.model ?? ''}
                    ${truck.driver_id ? '<span class="badge bg-primary float-end">Assigned to you</span>' : ''}
                </button>
            `).join('');

            document.querySelectorAll('#vehicleList button').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('#vehicleList button').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    selectedTruckId = btn.dataset.id;
                    confirmButton.disabled = false;
                });
            });
        } catch (error) {
            vehicleList.innerHTML = '<div class="text-center text-danger py-3">Error loading trucks.</div>';
        }
    });

    // Confirm truck selection
    confirmButton.addEventListener('click', async function () {
        if (!selectedTruckId) return;

        try {
            const res = await fetch(`/driver/trucks/${selectedTruckId}/assign`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();

            if (res.ok) {
                truckPlate.textContent = data.truck.license_plate;

                Swal.fire({
                    icon: 'success',
                    title: 'Truck Assigned',
                    text: `Truck ${data.truck.license_plate} has been successfully assigned to you.`,
                    confirmButtonColor: '#0d6efd'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Assignment Failed',
                    text: data.message || 'An error occurred while assigning the truck.',
                    confirmButtonColor: '#dc3545'
                });
            }

            // Close modal
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Could not connect to the server. Please try again later.',
                confirmButtonColor: '#dc3545'
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', async function () {
    try {
        const response = await fetch('{{ route("driver.status.current") }}');
        const data = await response.json();

        document.getElementById('statusCircle').textContent = data.status;
        document.getElementById('statusCircle').style.backgroundColor = data.color;
        document.getElementById('statusText').textContent = data.status_text;
        document.getElementById('statusDuration').textContent = data.duration;
    } catch (error) {
        console.error('Error fetching duty status:', error);
    }
});
</script>


@endsection

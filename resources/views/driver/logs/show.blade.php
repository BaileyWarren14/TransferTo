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

    <!-- Izquierda: Estado actual -->
    <div class="d-flex align-items-center">
        <!-- Círculo de estado -->
        <div id="statusCircle" class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
             style="width: 60px; height: 60px; font-size: 0.9rem; background-color: gray;">
            OFF
        </div>

        <!-- Texto y tiempo -->
        <div class="ms-3">
            <h6 id="statusText" class="mb-1">OFF DUTY</h6>
            <small id="statusDuration" class="text-muted">0h 00m</small>
        </div>
    </div>

    <!-- Derecha: Vehículo -->
    <div id="vehicleCard" class="text-end bg-light px-4 py-2 rounded shadow-sm d-flex align-items-center"
         style="cursor: pointer; transition: background-color 0.3s;">
        <div class="me-2 text-end">
            <h6 id="truckPlate" class="mb-0 fw-bold">XYZ-1234</h6>
            <small class="text-muted">Current Vehicle</small>
        </div>
        <i class="fas fa-exchange-alt fa-lg text-primary"></i> <!-- icono tipo "tab" -->
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

<!-- Sidebar derecho del vehículo -->
<div class="sidebar-body p-3">
    <h6 class="text-light mb-3">Select a Truck</h6>

    <!-- Contenedor para la lista de camiones -->
    <div id="vehicleList" class="list-group mb-3 text-dark">
        <div class="text-center text-muted py-3" id="loadingTrucks">Loading trucks...</div>
    </div>

    <button id="confirmTruck" class="btn btn-primary w-100 mt-3" disabled>
        Confirm Selection
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Acción del botón
    // Sidebar de vehículo
    const sidebar = document.getElementById('vehicleSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');

    // Abrir sidebar
    vehicleCard.addEventListener('click', () => {
        sidebar.classList.add('active');
    });

    // Cerrar sidebar
    closeSidebarBtn.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });

    // Cerrar si se hace clic fuera (opcional)
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !vehicleCard.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });
});

const vehicleList = document.getElementById('vehicleList');
const loadingTrucks = document.getElementById('loadingTrucks');
const confirmTruck = document.getElementById('confirmTruck');
let selectedTruckId = null;

// Cargar lista de camiones desde el servidor
async function loadTrucks() {
    try {
        loadingTrucks.textContent = 'Loading trucks...';
        const response = await fetch("{{ route('driver.vehicles') }}");
        const trucks = await response.json();

        vehicleList.innerHTML = '';

        if (trucks.length === 0) {
            vehicleList.innerHTML = '<div class="text-center text-muted py-3">No trucks found</div>';
            return;
        }

        trucks.forEach(truck => {
            const item = document.createElement('button');
            item.className = 'list-group-item list-group-item-action';
            item.innerHTML = `
                <div class="fw-bold">${truck.plate}</div>
                <small>${truck.model} (${truck.year})</small>
            `;
            item.addEventListener('click', () => selectTruck(item, truck.id));
            vehicleList.appendChild(item);
        });
    } catch (error) {
        console.error('Error loading trucks:', error);
        vehicleList.innerHTML = '<div class="text-danger text-center py-3">Error loading trucks</div>';
    }
}

// Seleccionar un camión
function selectTruck(element, truckId) {
    document.querySelectorAll('#vehicleList .list-group-item').forEach(btn => {
        btn.classList.remove('active');
    });
    element.classList.add('active');
    selectedTruckId = truckId;
    confirmTruck.disabled = false;
}

// Confirmar selección (ejemplo)
confirmTruck.addEventListener('click', async () => {
    if (!selectedTruckId) return;

    try {
        // Aquí puedes enviar la selección al backend (si quieres guardarla)
        const response = await fetch("{{ route('driver.set_vehicle') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ vehicle_id: selectedTruckId })
        });

        const data = await response.json();

        if (data.success) {
            alert(`✅ Vehicle changed to: ${data.vehicle.plate}`);
            sidebar.classList.remove('active');
            document.getElementById('truckPlate').textContent = data.vehicle.plate;
        } else {
            alert('❌ Error changing vehicle');
        }
    } catch (error) {
        console.error('Error confirming truck:', error);
    }
});

// Cargar camiones cuando se abre el sidebar
vehicleCard.addEventListener('click', () => {
    sidebar.classList.add('active');
    loadTrucks();
});

</script>
@endsection

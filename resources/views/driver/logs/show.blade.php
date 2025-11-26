@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
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

    @media (max-width: 768px) {
    .vehicle-sidebar {
        width: 100% !important;
        right: -100% !important; /* oculto completamente fuera del viewport */
        border-left: none;
        border-top: 4px solid #0d6efd;
        bottom: 0;
        height: 70%; /* ajustable según prefieras */
        max-height: 100%;
        border-radius: 0;
    }

    .vehicle-sidebar.active {
        right: 0 !important;
        left: 0;
    }

    /* Ajustes del botón que abre el sidebar para que no se salga en móvil */
    #openSidebar {
        top: 16px !important;
        right: 12px !important;
    }

    /* Ajustes del mapa y charts para que quepan mejor en móvil */
    #map { height: 220px; width: 96% !important; }
    .chart-container canvas { height: 130px !important; }
}

</style>

<style>
    /* ====== Sidebar Derecho ====== */
    .vehicle-sidebar {
        position: fixed;
        top: 0;
        right: -50%; /* Oculto por defecto */
        width: 50%;
        height: 100%;
        background-color: #212529;
        color: white;
        z-index: 1050;
        box-shadow: -4px 0 12px rgba(0,0,0,0.4);
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

    /* ====== Charts y Mapa ====== */
    .charts-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin: 20px;
    }

    .chart-container {
        position: relative;
       
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        min-height: 200px;
    }

    .chart-container canvas {
        width: 100% !important;
        height: 150px !important;
    }

    .chart-label {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 18px;
        font-weight: bold;
    }

    #map {
        width: 90%;
        height: 250px;
        margin: 0 auto 20px auto;
        border-radius: 8px;
    }

    /* Botón para abrir sidebar */
    .open-sidebar-btn {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 1060;
    }
</style>

    <!-- ====== Botones de Navegación (Fijos arriba) ====== -->
    <div class="nav-buttons-container mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <!-- Botones principales -->
        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
            <!-- Descargar libro electrónico 8 días -->
            <a href="{{ route('driver.logbook.download') }}" class="btn btn-primary w-100 w-sm-auto">
                <i class="fas fa-download me-1"></i>
                <span data-key="download_logbook">Descargar libro electrónico 8 días</span>
            </a>

            <!-- Compartir -->
            <button type="button" class="btn btn-success w-100 w-sm-auto" id="shareLogbook"  onclick="shareLogbook()">
                <i class="fas fa-share-alt me-1"></i>
                <span data-key="shareLogbook">Compartir</span>
            </button>
            
        </div>

        <!-- Derecha: Dashboard / Sidebar -->
        <button id="openSidebar" class="btn btn-secondary mt-2 mt-md-0">
            <i class="bi bi-clock-history me-1"></i>
        </button>
    </div>
</div>

<div class="container mt-4">
    <!-- Contenedor superior de estado -->
    <div class="card shadow-sm mb-4 p-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

        <div class="d-flex align-items-center w-100 w-md-auto">
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
        <div class="d-flex justify-content-end w-100 w-md-auto">
            <button id="vehicleCard" type="button"
                    class="btn w-100 text-end bg-light px-4 py-2 rounded shadow-sm d-flex align-items-center border-0"
                    data-bs-toggle="modal" data-bs-target="#truckModal">
                <div class="me-2 text-end flex-grow-1">
                    <h6 id="truckPlate" class="fw-bold">
                        {{ $assignedTruck->license_plate ?? 'Current Truck' }}
                    </h6>
                    <small class="text-muted">Current Vehicle</small>
                </div>
                <i class="fas fa-exchange-alt fa-lg text-primary"></i>
            </button>
        </div>
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

           
            <div class="chart-container" style="height:200px; width:100%;">
                    <canvas id="logbookChart"></canvas>
                </div>

            <!-- <div class="d-flex justify-content-between align-items-start"> -->
                 <!-- Gráfica -->
                
                <!-- Resumen compacto al lado derecho -->
                <!-- <div class="state-summary" 
                    style="flex: 0 0 5%; max-width:5%; min-width:10%; font-size:0.65rem; text-align:left; margin-left:5px; line-height:3.7;">
                    <ul id="stateSummaryList" class="list-unstyled mb-0"></ul>
                </div>
            </div> -->
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

<!-- ====== SIDEBAR DERECHO ====== -->
<div id="vehicleSidebar" class="vehicle-sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"></h5>
        <button class="btn btn-outline-light" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="p-3">
        <p class="plan-title text-light fw-bold mb-3">Texas Oil and Gas 70 hours / 7 days</p>

        <div class="charts-wrapper">
            <div class="chart-container">
                <canvas id="driveChart"></canvas>
                <div class="chart-label" id="driveLabel">--:--:--</div>
                <span class="badge bg-primary mt-2">Drive</span>
            </div>

            <div class="chart-container">
                <canvas id="shiftChart"></canvas>
                <div class="chart-label" id="shiftLabel">--:--:--</div>
                <span class="badge bg-success mt-2">Shift</span>
            </div>

            <div class="chart-container">
                <canvas id="cycleChart"></canvas>
                <div class="chart-label" id="cycleLabel">--:--:--</div>
                <span class="badge bg-secondary mt-2">Cycle</span>
            </div>
        </div>

        <div id="map"></div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


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

    const ctx = document.getElementById('logbookChart').getContext('2d');
    new Chart(ctx, {
          type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Driver Status',
                    data: duty_status,       
                    borderColor: 'blue',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0,
                    stepped: true,    
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
                 document.getElementById('truckPlate').textContent = data.truck.license_plate;

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

            // Cierra el modal correctamente y elimina el backdrop
            const modalEl = document.getElementById('truckModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

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

document.addEventListener('DOMContentLoaded', function() {
    const statusCircle = document.getElementById('statusCircle');
    const statusText = document.getElementById('statusText');
    const statusDuration = document.getElementById('statusDuration');

    async function fetchCurrentStatus() {
        try {
            const response = await fetch("{{ route('driver.status') }}");
            const data = await response.json();

            if (data.status) {
                if(statusCircle) {
                    statusCircle.textContent = data.status;
                    statusCircle.style.backgroundColor = data.color ?? 'gray';
                }

                if(statusText) statusText.textContent = data.status_text ?? data.status;
                if(statusDuration) statusDuration.textContent = formatElapsedMinutes(data.elapsed_minutes);
            }
        } catch (error) {
            console.error('Error al obtener el estado:', error);
        }
    }

    // Función para convertir minutos a hh:mm
    function formatElapsedMinutes(minutes) {
        if (!minutes) return '0h 00m';
        const hours = Math.floor(minutes / 60);
        const mins = Math.floor(minutes % 60);
        return `${hours}h ${mins.toString().padStart(2,'0')}m`;
    }

    // Cargar estado al iniciar
    fetchCurrentStatus();

    // Actualizar cada 60 segundos
    setInterval(fetchCurrentStatus, 60000);
});

// Mostrar y ocultar sidebar
document.getElementById('openSidebar')?.addEventListener('click', () => {
    document.getElementById('vehicleSidebar').classList.add('active');
});
document.getElementById('closeSidebar')?.addEventListener('click', () => {
    document.getElementById('vehicleSidebar').classList.remove('active');
});
</script>

<script>
    /* =================== Constantes =================== */
    const DRIVE_LIMIT = 11 * 3600;   // 11 horas en segundos
    const SHIFT_LIMIT = 14 * 3600;   // 14 horas en segundos
    const CYCLE_LIMIT = 70 * 3600;   // 70 horas en segundos
    const UPDATE_INTERVAL = 2000;   // 10 segundos

    /* =================== Estado global =================== */
    let timers = {
        drive: { remaining: DRIVE_LIMIT, chart: null, labelId: 'driveLabel', canvasId: 'driveChart', color: '#007bff' },
        shift: { remaining: SHIFT_LIMIT, chart: null, labelId: 'shiftLabel', canvasId: 'shiftChart', color: '#28a745' },
        cycle: { remaining: CYCLE_LIMIT, chart: null, labelId: 'cycleLabel', canvasId: 'cycleChart', color: '#6c757d' }
    };

    let chartsCreated = false;

    /* =================== Helpers =================== */
    function secondsToHMS(s) {
        s = Math.max(0, Math.floor(s));

        const h = Math.floor(s / 3600).toString().padStart(2, '0');
        const m = Math.floor((s % 3600) / 60).toString().padStart(2, '0');
        const sec = Math.floor(s % 60).toString().padStart(2, '0');

        return `${h}:${m}:${sec}`;
    }

    function secondsToHHMM(hoursDecimal) {
        const totalMinutes = Math.floor(hoursDecimal * 60);
        const h = Math.floor(totalMinutes / 60);
        const m = totalMinutes % 60;

        return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
    }

    function createDoughnutChart(canvasId, initialRemaining, total, color) {
        const el = document.getElementById(canvasId);

        if (!el) return null;

        const ctx = el.getContext('2d');

        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Remaining', 'Elapsed'],
                datasets: [{
                    data: [initialRemaining, Math.max(0, total - initialRemaining)],
                    backgroundColor: [color, '#e9ecef'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%'
            }
        });
    }

    /* =================== Actualizar UI =================== */
    function updateTimersUI() {
        Object.values(timers).forEach(timer => {
            if (timer.chart) {
                const elapsed = Math.max(0, (timer.total || 0) - timer.remaining);
                timer.chart.data.datasets[0].data = [timer.remaining, elapsed];
                timer.chart.update('none');
            }
                const labelEl = document.getElementById(timer.labelId);
                if (labelEl) labelEl.innerText = secondsToHMS(timer.remaining);
        });
            const shiftTimerEl = document.getElementById('shiftTimerText');
            if (shiftTimerEl) {
                shiftTimerEl.innerText = secondsToHMS(timers.shift.remaining);
            }
    }

    /* =================== Función principal =================== */
    async function updateTimers() {
        try {
            const response = await fetch('/driver/timers');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const data = await response.json();


                // Campos esperados del backend
                const driveUsed = parseFloat(data.DriveHoy) || 0;
                const shiftUsed = parseFloat(data.ShiftHoy) || 0;
                const cycleUsed = parseFloat(data.CycleTotal) || 0;

                // Calcular el tiempo restante
                const driveRemaining = Math.max(0, DRIVE_LIMIT - driveUsed);
                const shiftRemaining = Math.max(0, SHIFT_LIMIT - shiftUsed);
                const cycleRemaining = Math.max(0, CYCLE_LIMIT - cycleUsed);

                // Actualizar el estado global
                timers.drive.remaining = driveRemaining;
                timers.shift.remaining = shiftRemaining;
                timers.cycle.remaining = cycleRemaining;

                // Asignar totales (para los cálculos de "elapsed")
                timers.drive.total = DRIVE_LIMIT;
                timers.shift.total = SHIFT_LIMIT;
                timers.cycle.total = CYCLE_LIMIT;

                // Crear charts si aún no existen
                if (!chartsCreated) {
                    timers.drive.chart = createDoughnutChart('driveChart', driveRemaining, DRIVE_LIMIT, timers.drive.color);
                    timers.shift.chart = createDoughnutChart('shiftChart', shiftRemaining, SHIFT_LIMIT, timers.shift.color);
                    timers.cycle.chart = createDoughnutChart('cycleChart', cycleRemaining, CYCLE_LIMIT, timers.cycle.color);
                    chartsCreated = true;
                }

                // Actualizar UI
                updateTimersUI();

                // (Opcional) Mostrar log en consola
                console.log(`Drive Remaining: ${secondsToHMS(driveRemaining)} | Shift Remaining: ${secondsToHMS(shiftRemaining)} | Cycle Remaining: ${secondsToHMS(cycleRemaining)}`);
        } catch (err) {
            console.error('Error al obtener timers:', err);
        }

    }

    /* =================== Inicializar =================== */
    document.addEventListener('DOMContentLoaded', () => {
        updateTimers();       // Llamada inicial al backend

        // ⏱️ Actualiza los timers del servidor cada 10 segundos
        setInterval(updateTimers, UPDATE_INTERVAL);

        // 🕐 Inicia el tick local (descuento por segundo)
        setInterval(tickClient, 1000);
    });

    function tickClient() {
        let needsUpdate = false;

        Object.values(timers).forEach(timer => {
            if (timer.remaining > 0) {
                timer.remaining = Math.max(0, timer.remaining - 1); 
                needsUpdate = true;
            }
        });

        if (needsUpdate) updateTimersUI(); 
    }
   

// ---------------- MAPA ---------------- //
    const map = L.map('map').setView([0,0],13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'Map data © OpenStreetMap contributors' }).addTo(map);
    const marker = L.marker([0,0]).addTo(map);
    function updateLocation(){
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(pos=>{
                marker.setLatLng([pos.coords.latitude,pos.coords.longitude]);
                map.setView([pos.coords.latitude,pos.coords.longitude],13);
            });
        }
    }
    updateLocation();
    setInterval(updateLocation,3000);

async function shareLogbook() {
    const email = prompt("Introduce el correo al que deseas enviar el logbook:");
    if (!email) return;

    try {
        const res = await fetch("{{ route('driver.logbook.email') }}", {
            method: "POST",
            credentials: "same-origin", // asegura que se envíen cookies de sesión
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest" // ayuda a Laravel a detectar AJAX
            },
            body: JSON.stringify({ email })
        });

        console.log("Fetch status:", res.status, res.statusText);

        // Si no es 2xx, leer el cuerpo como texto (útil para ver HTML de redirect o error)
        if (!res.ok) {
            const text = await res.text();
            console.error("Server returned non-OK response:", res.status, text);
            // Mostrar alerta amigable
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Server responded with status ${res.status}. Open console for more details.`
            });
            return;
        }

        // Intentar parsear JSON seguro
        const data = await res.json();
        console.log("Response JSON:", data);

        Swal.fire({
            icon: 'success',
            title: 'Enviado',
            text: data.message || 'El logbook ha sido enviado correctamente.'
        });

    } catch (err) {
        // Error de red o parseo -> mostrar info útil en consola
        console.error("Fetch failed:", err);
        Swal.fire({
            icon: 'error',
            title: 'Network/error',
            text: 'No se pudo conectar al servidor. Revisa la consola (F12) y la pestaña Network.'
        });
    }
}


</script>

@endsection

@extends('layouts.app')

@section('content')

<!-- ======================= CSS ======================= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
/* ======================= Estilos Dashboard ======================= */
/* ======================= Estilos Generales ======================= */
.app-body {
    width: 100%;
    min-height: 100vh;
    padding: 20px;
    background-color: #f5f5f5;
    box-sizing: border-box;
    position: relative; /* necesario para que el sidebar se posicione relativo a esto */
    max-width: 1200px;  /* igual que tu contenedor principal */
    margin: 0 auto;     /* centra en desktop */
    min-height: 100vh;
    padding-bottom: 70px; /* espacio para el sidebar colapsado */
    box-sizing: border-box;
}
body.dark-mode .app-body {
    background-color: #121212;
    color: #f0f0f0;
}

/* =================== Contenedores principales (Cards) =================== */
.top-cards {
    display: flex;
    justify-content: space-around;
    margin-bottom: 30px;
    overflow-x: auto;     /* 🎯 Scroll horizontal solo aquí */
    padding-bottom: 10px; /* Espacio para que no se corte */
    -webkit-overflow-scrolling: touch; /* Suavidad en iOS */
}
.card-item {
    flex: 1;
    margin: 0 10px;
    padding: 20px;
    border-radius: 10px;
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;   /* Asegura centrado */
    gap: 8px;                  /* Espacio entre icono y texto */
    font-size: 1.2rem;
    min-width: 200px;     /* Para que se vean tipo “cards” scrollables */
    flex: 0 0 auto;     
}
.card-item i { 
    font-size: 2rem; 
}
.card-logs   { background-color: #007bff; }
.card-support{ background-color: #28a745; }
.card-docs   { background-color: #001f3f; }

/* Dark mode para cards */
body.dark-mode .card-logs    { background-color: #0056b3; }
body.dark-mode .card-support { background-color: #1f7a33; }
body.dark-mode .card-docs    { background-color: #00172d; }

/* =================== Compliance y Maintenance =================== */
.section-container {
    background-color: #fff;
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.section-header {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    font-size: 1.1rem;
}
.section-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    cursor: pointer;
}

/* Dark mode sections */
body.dark-mode .section-container {
    background-color: #1e1e1e;
    box-shadow: 0 2px 6px rgba(0,0,0,0.5);
    color: #f0f0f0;
}
body.dark-mode .section-item span { color: #f0f0f0; }



/* =================== Sidebar Inferior =================== */
.bottom-sidebar {
    
     position: absolute;  /* relativo a .app-body */
   padding: 20px;
    background-color: #fff;
    box-sizing: border-box;


    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: 1200px;  /* igual que tu main content */
    border-top: 2px solid #ddd;
    box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
    transition: height 0.3s ease-in-out;
    overflow: hidden;
    z-index: 999;
}
@media (max-width: 768px) {
    .top-cards {
        overflow-x: auto;
        justify-content: flex-start;
    }
}
/* Altura contraída */
.bottom-sidebar.collapsed {
    height: 70px;
}

/* Altura expandida */
.bottom-sidebar.expanded {
    height: 90%;
    width: 100%;
    
}

/* Botón para expandir */
.toggle-btn {
    width: 100%;
    text-align: center;
    padding: 5px 0;
    cursor: pointer;
    font-size: 18px;
    background: #f0f0f0;
    border-bottom: 1px solid #ddd;
}

/* Dark mode sidebar */
body.dark-mode .bottom-sidebar {
    background-color: #1a1a1a;
    border-top: 2px solid #333;
    box-shadow: 0 -3px 10px rgba(0,0,0,0.7);
}
body.dark-mode .toggle-btn { background: #2a2a2a; color: #f0f0f0; border-bottom: 1px solid #444; }
body.dark-mode .header-right .subtext { color: #aaa; }

/* Header superior */
.sidebar-header {
    display: flex;
    justify-content: space-between;
    padding: 10px 15px;
    flex-wrap: wrap;
}

.header-left,
.header-right {
    display: flex;
    flex-direction: column;
}

.header-right {
    text-align: right;
}

.header-right .subtext {
    font-size: 12px;
    color: #555;
}

/* Contenido scroll */
.sidebar-scroll {
    
    padding: 10px 15px;
    overflow-y: auto;
    height: calc(100% - 60px); /* Resta toggle + header */
}
body.dark-mode .sidebar-scroll { color: #f0f0f0; }

.plan-title {
    font-weight: bold;
    margin-bottom: 10px;
}

/* Timers internos */
.timers {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    gap: 10px;
    margin-bottom: 20px;
}

.timer-item {
    background: #f7f7f7;
    padding: 8px;
    border-radius: 5px;
    display: flex;
    justify-content: space-between;
}

/* =================== Charts Responsive =================== */
.charts-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.chart-container {
    position: relative;
    background: #f9f9f9;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    min-height: 200px;
}
body.dark-mode .timer-item { background: #2a2a2a; }

.chart-container canvas {
    width: 100% !important;
    height: 150px !important;
}

.chart-label {
    position: absolute;
    top: 10px;
    left: 10px;
    font-weight: bold;
}

/* Dark mode charts */
body.dark-mode .chart-container { background: #2a2a2a; color: #f0f0f0; }

/* =================== Mapa =================== */
#map {
    width: 100%;
    height: 200px;
    background: #ddd;
    border-radius: 8px;
}
body.dark-mode #map { background: #333; }
.chart-container {
    position: relative;
    width: 200px;
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

.chart-label {
    position: absolute;
    top: 42%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 20px;
    font-weight: bold;
    pointer-events: none;
}
/* =================== Ajustes para móvil =================== */
@media (max-width: 768px) {
    .app-body {
        min-height: 90vh; /* antes era 100vh, ahora más pequeño en móvil */
        padding: 15px 10px; /* opcional: menos padding en móvil */
    }

    .top-cards {
        overflow-x: auto;
        justify-content: flex-start;
    }

    .section-container {
        padding: 10px 15px;
    }

    .bottom-sidebar.expanded {
        height: 90%; /* opcional: menos alto en móvil */
    }
}
</style>

<!-- ======================= Contenido ======================= -->
<div class="app-body">
    <h1 id="greeting" data-key="" style="margin-bottom: 40px;"></h1>
    

    <!-- =================== Contenedores principales =================== -->
    <div class="top-cards">
        <div class="card-item card-logs" onclick="location.href='{{ route('driver.logs.show') }}'">
            <i class="bi bi-bar-chart-line-fill"></i>

            <span data-key="logs">Logs</span>
            
        </div>
        <div class="card-item card-support" onclick="location.href='#'">
            <i class="fa-solid fa-headset"></i>

            <span data-key="support">Support</span>
            
        </div>
        <div class="card-item card-docs" onclick="location.href='#'">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span data-key="docs">Docs</span>
            
        </div>
    </div>

    <!-- =================== Compliance =================== -->
    <div class="section-container">
        <div class="section-header">
            <span data-key="compliance">Compliance</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.logs.show') }}'">
            <span data-key="unidentified_trips">Unidentified trips</span>
            <span>5 &gt;</span>
        </div>
    </div>

    <!-- =================== Maintenance =================== -->
    <div class="section-container">
        <div class="section-header">
            <span data-key="maintenance">Maintenance</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.inspections') }}'">
            <span data-key="pre_trip_vehicle_inspection">Pre-trip Vehicle Inspection</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.inspections') }}'">
            <span data-key="post_trip_vehicle_inspection">Post-trip Vehicle Inspection</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.inspections') }}'">
            <span data-key="vehicle_inspection">Vehicle Inspection</span>
            <span>&gt;</span>
        </div>
    </div>
    





    <!-- =================== Sidebar Inferior =================== -->
    <div id="bottomSidebar" class="bottom-sidebar collapsed">
    @php
    $serverTimers = $initialTimers ?? [
        'drive_remaining' => 11*3600,
        'shift_remaining' => 14*3600,
        'cycle_remaining' => 70*3600,
        'current_status' => 'OFF'
    ];
    @endphp
    <!-- Botón toggle -->
    <div class="toggle-btn" onclick="toggleSidebar()">─</div>

    <!-- Encabezado (Status / Truck / Shift timer) -->
    <div class="sidebar-header">
        <div class="header-left">
            <span id="statusText">Status: OFF</span>
            <span id="truckText">Truck: --</span>
        </div>
        <div class="header-right">
            <span id="shiftTimerText">11:43:00</span>
            <span class="subtext">Left in shift</span>
        </div>
    </div>

    <!-- Contenido con scroll al expandir -->
    <div class="sidebar-scroll">
        <p class="plan-title">Texas Oil and Gas 70 hours / 7 days</p>

       
    

        <!-- =================== Charts =================== -->
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

        <!-- =================== Mapa =================== -->
        <div id="map"></div>
    </div>
</div>
</div>

<!-- =================== JS =================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @php
        $driver = auth()->guard('driver')->user();
    @endphp

    window.DRIVER_NAME = "{{ $driver ? ($driver->name ?? $driver->full_name ?? $driver->nombre ?? $driver->username ?? 'Conductor') : 'Conductor' }}";
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
    /* =================== Greeting =================== */
    function showGreeting(){
        const name = window.DRIVER_NAME || 'Driver';
        const lang = localStorage.getItem('language') || 'es';
        const t = window.translations?.[lang] || {};
        const hour = new Date().getHours();
        let greeting = '';

        if(hour>=5 && hour<12) greeting = t.greeting_morning || (lang==='es'?'Buenos días':'Good morning');

        else if(hour>=12 && hour<18) greeting = t.greeting_afternoon || (lang==='es'?'Buenas tardes':'Good afternoon');

        else if(hour>=18 && hour<22) greeting = t.greeting_evening || (lang==='es'?'Buena tarde':'Good evening');

        else greeting = t.greeting_night || (lang==='es'?'Buenas noches':'Good night');

        
        const el = document.getElementById('greeting');
        if(el) el.innerText = `${greeting}, ${name}!`;
    }


    /* =================== Inicializar =================== */
    /* =================== Inicializar =================== */
    document.addEventListener('DOMContentLoaded', () => {
        updateTimers();       // Llamada inicial al backend
        showGreeting();       // Mostrar saludo

        // ⏱️ Actualiza los timers del servidor cada 10 segundos
        setInterval(updateTimers, UPDATE_INTERVAL);

        // 🕐 Inicia el tick local (descuento por segundo)
        setInterval(tickClient, 1000);
    });






    /* =================== Sidebar toggle =================== */
    function toggleSidebar(){
        const sidebar = document.getElementById('bottomSidebar');
        sidebar.classList.toggle('collapsed');
        sidebar.classList.toggle('expanded');
    }

    /* =================== Startup =================== */
    document.addEventListener('DOMContentLoaded', function(){
        initTimers();
        showGreeting();
        updateTimers();
        setInterval(updateTimers, 60000);

        window.addEventListener('beforeunload', () => {
            saveStateToStorage();
            if(tickIntervalHandle) clearInterval(tickIntervalHandle);
            if(syncIntervalHandle) clearInterval(syncIntervalHandle);
        });
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
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Hacemos la petición al endpoint del controlador
        const response = await fetch('/driver/status_truck');
        const data = await response.json();
        
        // Si hubo error de autenticación
        if (data.error) {
            console.error('Error de autenticación:', data.error);
            return;
        }

        // ✅ Primero obtener los elementos del DOM
        const statusEl = document.getElementById('statusText');
        const truckEl = document.getElementById('truckText');

        // ✅ Actualizar los elementos
        if (statusEl) {
            statusEl.textContent = `Status: ${data.status || 'OFF'}`;
        }
        
        if (truckEl) {
            // Usar license_plate en lugar de name
            const truckInfo = data.truck?.license_plate || data.truck?.name || data.truck?.id || '--';
            truckEl.textContent = `Truck: ${truckInfo}`;
        }

        /* ✅ Mostrar alert DESPUÉS de actualizar los elementos
        if (statusEl && truckEl) {
            alert(
                `✅ DATOS ACTUALIZADOS\n\n` +
                `Driver: ${data.driver}\n` +
                `Status: ${data.status}\n` +
                `Truck: ${data.truck?.license_plate || '--'}\n\n` +
                `${statusEl.textContent}\n` +
                `${truckEl.textContent}`
            );
        }*/

        // Log en consola
        console.log('✅ Status y Truck actualizados:', {
            driver: data.driver,
            status: data.status,
            truck: data.truck?.license_plate || 'No asignado'
        });
        
    } catch (error) {
        console.error('❌ Error al obtener datos del driver:', error);
        alert('❌ Error al cargar los datos del conductor');
    }
});

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

</script>



@if(session('alert_message'))
<script>
Swal.fire({
    icon:'warning',
    title:'Attention',
    text:"{{ session('alert_message') }}",
    confirmButtonText:'OK'
});
</script>

@endif

@endsection
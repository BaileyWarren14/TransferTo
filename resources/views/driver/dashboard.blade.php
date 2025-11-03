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
    height: 60%;
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
        height: 65%; /* opcional: menos alto en móvil */
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
    
    /* =================== Sidebar toggle =================== */
function toggleSidebar(){
    const sidebar = document.getElementById('bottomSidebar');
    sidebar.classList.toggle('collapsed');
    sidebar.classList.toggle('expanded');
}


  // Variables globales desde Laravel
window.TIMERS_ROUTE = "{{ route('driver.timers') }}";
window.DRIVER_NAME = "{{ auth()->guard('driver')->user()->name ?? 'Driver' }}";

/* =================== Helpers =================== */
function secondsToHMS(s){
    s = Math.max(0, Math.floor(s));
    const h = Math.floor(s/3600).toString().padStart(2,'0');
    const m = Math.floor((s%3600)/60).toString().padStart(2,'0');
    const sec = Math.floor(s%60).toString().padStart(2,'0');
    return `${h}:${m}:${sec}`;
}
async function fetchTimersAndShowAlert() {
    try {
        const res = await fetch('/driver/timers');
        const data = await res.json();

        const tiempoD = data.D;
        const tiempoON = data.ON;
        const tiempoOFF = data.OFF;
        const totaltime = tiempoD + tiempoON;
        const status = data.status || 'OFF';
        const elapsedMinutes = data.elapsed_minutes || 0;

        document.getElementById('estadoD').innerText = `${tiempoD} seg`;
        document.getElementById('estadoON').innerText = `${tiempoON} seg`;
        document.getElementById('estadoOFF').innerText = `${tiempoOFF} seg`;

        alert(
            `🟢 Estado actual: ${status}\n` +
            `⏱️ Tiempo transcurrido: ${Math.floor(elapsedMinutes * 60)} segundos\n` +
            `🚗 Drive: ${tiempoD } segundos\n` +
            `🕒 Shift: ${tiempoON } segundos\n` +
            `🔄 Cycle: ${totaltime } segundos\n` +
            `🔄 OFF Duty: ${tiempoOFF } segundos\n`
        );
    } catch(e) {
        console.error(e);
    }
}

document.addEventListener('DOMContentLoaded', fetchTimersAndShowAlert);

function showRestAlert(timerName){
    Swal.fire({
        icon:'warning',
        title:'Rest Required',
        text:`Your ${timerName} time has ended. Please take a break and change duty status.`,
        confirmButtonText:'OK'
    });
}


/* =================== Constantes =================== */
const DRIVE_TOTAL = 11*3600;
const SHIFT_TOTAL = 14*3600;
const CYCLE_TOTAL = 70*3600;
const STORAGE_KEY = 'driver_timers_state';
const SYNC_INTERVAL = 10000; // 10 segundos
const CLIENT_TICK_INTERVAL = 1000; // 1 segundo

/* =================== Estado global =================== */
let timers = {
    drive:{remaining:DRIVE_TOTAL,total:DRIVE_TOTAL,running:false,chart:null,labelId:'driveLabel',canvasId:'driveChart',name:'Drive'},
    shift:{remaining:SHIFT_TOTAL,total:SHIFT_TOTAL,running:false,chart:null,labelId:'shiftLabel',canvasId:'shiftChart',name:'Shift'},
    cycle:{remaining:CYCLE_TOTAL,total:CYCLE_TOTAL,running:false,chart:null,labelId:'cycleLabel',canvasId:'cycleChart',name:'Cycle'}
};

let currentStatus = 'OFF';
let chartsCreated = false;
let syncIntervalHandle = null;
let tickIntervalHandle = null;

/* =================== LocalStorage =================== */
function saveStateToStorage(){
    try {
        const state = {
            drive_remaining: timers.drive.remaining,
            shift_remaining: timers.shift.remaining,
            cycle_remaining: timers.cycle.remaining,
            current_status: currentStatus,
            timestamp: Date.now(),
            drive_running: timers.drive.running,
            shift_running: timers.shift.running,
            cycle_running: timers.cycle.running
        };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        console.log('💾 State saved');
    } catch(e) {
        console.warn('Could not save state:', e);
    }
}







function loadStateFromStorage(){
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if(!stored) return null;
        
        const state = JSON.parse(stored);
        const age = Date.now() - (state.timestamp || 0);
        
        if(age > 30000) {
            console.log('Stored state too old, syncing with server');
            return null;
        }
        
        console.log('📦 Loading from localStorage (age: ' + Math.floor(age/1000) + 's)');
        return state;
    } catch(e) {
        console.warn('Could not load state:', e);
        return null;
    }
}


document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('{{ route("driver.status") }}');
        let driveMax = DRIVE_TOTAL;
        let shiftMax = SHIFT_TOTAL;
        let cycleMax = CYCLE_TOTAL;
        let driveRem = DRIVE_TOTAL;
        let shiftRem = SHIFT_TOTAL;
        let cycleRem = CYCLE_TOTAL;
        let status = 'OFF';
        let elapsedSeconds = 0;

        if (res.ok) {
            const data = await res.json();

            status = data.status || 'OFF';
            const elapsedMinutes = data.elapsed_minutes || 0;

            // Valores en minutos que vienen del servidor
            const driveMinutes = data.drive_time || driveMax/60;
            const shiftMinutes = data.shift_time || shiftMax/60;
            const cycleMinutes = data.cycle_time || cycleMax/60;

            // Convertimos todo a segundos
            const driveSeconds = driveMinutes * 60;
            const shiftSeconds = shiftMinutes * 60;
            const cycleSeconds = cycleMinutes * 60;
            elapsedSeconds = elapsedMinutes * 60;

            // Calculamos los timers restantes
            driveRem = Math.max(0, driveSeconds - elapsedSeconds);
            shiftRem = Math.max(0, shiftSeconds - elapsedSeconds);
            cycleRem = Math.max(0, cycleSeconds - elapsedSeconds);

            console.log("🟢 Estado actual:", status);
            console.log("⏱️ Tiempo transcurrido:", elapsedSeconds, "s");
            console.log("🚗 Drive:", driveRem, "s");
            console.log("🕒 Shift:", shiftRem, "s");
            console.log("🔄 Cycle:", cycleRem, "s");

            alert(
            `🟢 Estado actual: ${status}\n` +
            `⏱️ Tiempo transcurrido: ${Math.floor(elapsedMinutes * 60)} segundos\n` +
            `🚗 Drive: ${driveRem } segundos\n` +
            `🕒 Shift: ${shiftRem } segundos\n` +
            `🔄 Cycle: ${cycleRem } segundos`
        );
            
        } else {
            console.warn("No se obtuvieron datos del servidor. Se usarán valores por defecto.");
        }

        // Actualizamos timers globales
        timers.drive.remaining = driveRem;
        timers.shift.remaining = shiftRem;
        timers.cycle.remaining = cycleRem;

        timers.drive.total = driveMax;
        timers.shift.total = shiftMax;
        timers.cycle.total = cycleMax;

        // Arrancamos estados según status
        updateRunningStates(status);

        // Inicializamos gráficas si no existen
        if(!chartsCreated){
            timers.drive.chart = createDoughnutChart('driveChart', timers.drive.remaining, timers.drive.total, '#007bff');
            timers.shift.chart = createDoughnutChart('shiftChart', timers.shift.remaining, timers.shift.total, '#28a745');
            timers.cycle.chart = createDoughnutChart('cycleChart', timers.cycle.remaining, timers.cycle.total, '#6c757d');
            chartsCreated = true;
        }

        // Actualizamos UI inmediatamente
        updateTimersUI();

        // Arrancamos tickClient
        if(!tickIntervalHandle){
            tickIntervalHandle = setInterval(tickClient, CLIENT_TICK_INTERVAL);
        }

    } catch (err) {
        console.error("❌ Error:", err);
        alert("No se pudo obtener el estado actual del conductor. Se usarán valores por defecto.");
        
        // En caso de error, inicializamos con máximos
        timers.drive.remaining = DRIVE_TOTAL;
        timers.shift.remaining = SHIFT_TOTAL;
        timers.cycle.remaining = CYCLE_TOTAL;
        timers.drive.total = DRIVE_TOTAL;
        timers.shift.total = SHIFT_TOTAL;
        timers.cycle.total = CYCLE_TOTAL;

        updateRunningStates('OFF');

        if(!chartsCreated){
            timers.drive.chart = createDoughnutChart('driveChart', timers.drive.remaining, timers.drive.total, '#007bff');
            timers.shift.chart = createDoughnutChart('shiftChart', timers.shift.remaining, timers.shift.total, '#28a745');
            timers.cycle.chart = createDoughnutChart('cycleChart', timers.cycle.remaining, timers.cycle.total, '#6c757d');
            chartsCreated = true;
        }

        updateTimersUI();
        tickIntervalHandle = setInterval(tickClient, CLIENT_TICK_INTERVAL);
    }
});
/* =================== Charts =================== */
function createDoughnutChart(canvasId, initialRemaining, total, color){
    const el = document.getElementById(canvasId);
    if(!el) return null;
    const ctx = el.getContext('2d');
    return new Chart(ctx,{
        type:'doughnut',
        data:{ 
            labels:['Remaining','Elapsed'], 
            datasets:[{ 
                data:[initialRemaining, Math.max(0,total-initialRemaining)], 
                backgroundColor:[color,'#e9ecef'], 
                borderWidth:0 
            }] 
        },
        options:{ 
            plugins:{legend:{display:false}}, 
            responsive:true, 
            maintainAspectRatio:false, 
            cutout:'70%' 
        }
    });
}

/* =================== Running states =================== */
function updateRunningStates(status){
    currentStatus = status;
    timers.drive.running = (status === 'D');
    timers.shift.running = ['D', 'ON', 'SB'].includes(status);
    timers.cycle.running = ['D', 'ON'].includes(status);
    
    console.log(`Status: ${status} | Drive: ${timers.drive.running} | Shift: ${timers.shift.running} | Cycle: ${timers.cycle.running}`);
}

/* =================== Update UI =================== */
function updateTimersUI(){
    Object.values(timers).forEach(timer => {
        if(timer.chart){
            const elapsed = Math.max(0, timer.total - timer.remaining);
            timer.chart.data.datasets[0].data = [timer.remaining, elapsed];
            timer.chart.update('none');
        }
        
        const labelEl = document.getElementById(timer.labelId);
        if(labelEl) labelEl.innerText = secondsToHMS(timer.remaining);
    });
    
    const shiftTimerEl = document.getElementById('shiftTimerText');
    if(shiftTimerEl) shiftTimerEl.innerText = secondsToHMS(timers.shift.remaining);
}

/* =================== Apply server data =================== */
function applyServerData(data){
    if(!data) return;

    console.log('📡 Server sync:', {
        drive: data.drive_remaining,
        shift: data.shift_remaining,
        cycle: data.cycle_remaining,
        status: data.current_status
    });

    const driveRem = Math.max(0, Math.min(Number(data.drive_remaining) || 0, DRIVE_TOTAL));
    const shiftRem = Math.max(0, Math.min(Number(data.shift_remaining) || 0, SHIFT_TOTAL));
    const cycleRem = Math.max(0, Math.min(Number(data.cycle_remaining) || 0, CYCLE_TOTAL));

    timers.drive.remaining = driveRem;
    timers.shift.remaining = shiftRem;
    timers.cycle.remaining = cycleRem;

    const status = (data.current_status || 'OFF').toString();
    updateRunningStates(status);

    if(!chartsCreated){
        timers.drive.chart = createDoughnutChart('driveChart', driveRem, DRIVE_TOTAL, '#007bff');
        timers.shift.chart = createDoughnutChart('shiftChart', shiftRem, SHIFT_TOTAL, '#28a745');
        timers.cycle.chart = createDoughnutChart('cycleChart', cycleRem, CYCLE_TOTAL, '#6c757d');
        chartsCreated = true;
    }

    updateTimersUI();
    
    const statusEl = document.getElementById('statusText');
    if(statusEl) statusEl.innerText = `Status: ${currentStatus}`;

    saveStateToStorage();
}

/* =================== Apply stored state =================== */
function applyStoredState(state){
    console.log('📦 Applying stored state');

    timers.drive.remaining = Math.max(0, Math.min(state.drive_remaining || DRIVE_TOTAL, DRIVE_TOTAL));
    timers.shift.remaining = Math.max(0, Math.min(state.shift_remaining || SHIFT_TOTAL, SHIFT_TOTAL));
    timers.cycle.remaining = Math.max(0, Math.min(state.cycle_remaining || CYCLE_TOTAL, CYCLE_TOTAL));

    updateRunningStates(state.current_status || 'OFF');

    if(!chartsCreated){
        timers.drive.chart = createDoughnutChart('driveChart', timers.drive.remaining, DRIVE_TOTAL, '#007bff');
        timers.shift.chart = createDoughnutChart('shiftChart', timers.shift.remaining, SHIFT_TOTAL, '#28a745');
        timers.cycle.chart = createDoughnutChart('cycleChart', timers.cycle.remaining, CYCLE_TOTAL, '#6c757d');
        chartsCreated = true;
    }

    updateTimersUI();
    
    const statusEl = document.getElementById('statusText');
    if(statusEl) statusEl.innerText = `Status: ${currentStatus}`;
}

/* =================== Client tick =================== */
function tickClient(){
    let needsUpdate = false;
    let shouldAlert = [];

    Object.values(timers).forEach(timer => {
        if(timer.running && timer.remaining > 0){
            timer.remaining = Math.max(0, timer.remaining - 1);
            needsUpdate = true;

            if(timer.remaining === 0){
                shouldAlert.push(timer.name);
            }
        }
    });

    if(needsUpdate){
        updateTimersUI();
        saveStateToStorage();
    }

    shouldAlert.forEach(name => showRestAlert(name));
}

/* =================== Sync with server =================== */
async function syncWithServer(){
    try {
        console.log('🔄 Fetching from:', window.TIMERS_ROUTE);
        
        const res = await fetch(window.TIMERS_ROUTE, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if(!res.ok) {
            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }

        const data = await res.json();
        applyServerData(data);
        
    } catch(err) {
        console.error('❌ Sync error:', err);
    }
}

/* =================== Initialize =================== */
async function initTimers(){
    console.log('🚀 Initializing timer system...');
    console.log('Route:', window.TIMERS_ROUTE);
    
    const storedState = loadStateFromStorage();
    
    if(storedState){
        applyStoredState(storedState);
        syncWithServer();
    } else {
        await syncWithServer();
    }
    
    tickIntervalHandle = setInterval(tickClient, CLIENT_TICK_INTERVAL);
    syncIntervalHandle = setInterval(syncWithServer, SYNC_INTERVAL);
    
    document.addEventListener('visibilitychange', () => {
        if(document.visibilityState === 'visible'){
            console.log('👁️ Tab visible - syncing...');
            syncWithServer();
        }
    });
    
    console.log('✅ Timer system ready');
}

/* =================== Cleanup =================== */
window.addEventListener('beforeunload', () => {
    saveStateToStorage();
    if(tickIntervalHandle) clearInterval(tickIntervalHandle);
    if(syncIntervalHandle) clearInterval(syncIntervalHandle);
});


/* =================== Startup =================== */
document.addEventListener('DOMContentLoaded', function () {
    initTimers();

    // Nombre del conductor
    const name = window.DRIVER_NAME || 'Driver';

    // Idioma
    const lang = localStorage.getItem('language') || 'es';
    const t = window.translations?.[lang] || {};

    // Hora actual
    const now = new Date();
    const hour = now.getHours();

    let greeting = '';

    if (hour >= 5 && hour < 12) {
        greeting = t.greeting_morning || (lang === 'es' ? 'Buenos días' : 'Good morning');
    } else if (hour >= 12 && hour < 18) {
        greeting = t.greeting_afternoon || (lang === 'es' ? 'Buenas tardes' : 'Good ');
    } else if (hour >= 18 && hour < 22) {
        greeting = t.greeting_evening || (lang === 'es' ? 'Buena tarde' : 'Good evening');
    } else {
        greeting = t.greeting_night || (lang === 'es' ? 'Buenas noches' : 'Good night');
    }

    // Mostrar el saludo
    const greetingEl = document.getElementById('greeting');
    if (greetingEl) greetingEl.innerText = `${greeting}, ${name}!`;
});
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

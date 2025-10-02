@extends('layouts.app')


@section('content')

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    #map {
        height: 400px;
        width: 100%;
        margin-top: 20px;
    }

    .chart-container {
        width: 200px;
        height: 200px;
        display: inline-block;
        margin: 20px;
        position: relative;
        text-align: center;
    }

    .chart-label {
        position: absolute;
        width: 100%;
        text-align: center;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        font-size: 18px;
    }

    .chart-title {
        margin-top: 210px;
        font-weight: bold;
        font-size: 16px;
    }

    .charts-wrapper {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .dashboard-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .app-body {
    width: 100%;
    min-height: 100vh; /* altura completa del viewport */
    padding: 20px;
    background-color: #f5f5f5;
    box-sizing: border-box; /* para que padding no afecte al width */
}
/* Dark Mode para app-body */
body.dark-mode .app-body {
    background-color: #121212;
    color: #f0f0f0;
}

body.dark-mode .app-body input,
body.dark-mode .app-body select,
body.dark-mode .app-body textarea,
body.dark-mode .app-body button {
    background-color: #1e1e1e !important;
    color: #f0f0f0 !important;
    border-color: #333 !important;
}

</style>


<h1>Welcome to Truck Dashboard</h1>
    <p>Welcome, {{ auth()->guard('driver')->user()->name }}</p>
<div class="app-body">
    <div class="charts-wrapper">
        <div class="chart-container">
            <canvas id="driveChart"></canvas>
            <div class="chart-label" id="driveLabel"></div>
            <div class="d-flex justify-content-center mt-2">
                <span class="badge bg-primary px-3 py-2">Drive</span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="shiftChart"></canvas>
            <div class="chart-label" id="shiftLabel"></div>
            <div class="d-flex justify-content-center mt-2">
                <span class="badge bg-success px-3 py-2">Shift</span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="cycleChart"></canvas>
            <div class="chart-label" id="cycleLabel"></div>
            <div class="d-flex justify-content-center mt-2">
                <span class="badge bg-secondary px-3 py-2">Cycle</span>
            </div>
        </div>
    </div>

    <div id="map"></div>

</div>




<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ---------------- CONFIG ---------------- //
let currentStatus = "OFF";
let offStartTime = null;
let offElapsed = 0;

function createCountdownChart(canvasId, labelId, totalHours, color) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    const totalSeconds = totalHours * 3600;

    // Obtener tiempo guardado en localStorage
    let savedRemaining = localStorage.getItem(canvasId);
    savedRemaining = savedRemaining !== null ? parseInt(savedRemaining, 10) : totalSeconds;

    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: { 
            labels: ['Remaining','Elapsed'], 
            datasets:[{data:[savedRemaining,totalSeconds-savedRemaining], backgroundColor:[color,'#e9ecef'], borderWidth:0}] 
        },
        options: { plugins:{ legend:{ display:false } }, responsive:true, maintainAspectRatio:false }
    });

    const timer = { id: canvasId, label: labelId, total: totalSeconds, remaining: savedRemaining, running: false, chart };

    // Mostrar el tiempo inicial inmediatamente
    updateLabel(timer);

    return timer;
}

// Crear cronómetros
const timers = {
    drive: createCountdownChart('driveChart', 'driveLabel', 11, '#007bff'),
    shift: createCountdownChart('shiftChart', 'shiftLabel', 14, '#28a745'),
    cycle: createCountdownChart('cycleChart', 'cycleLabel', 70, '#6c757d'),
};

function updateLabel(timer){
    const h = Math.floor(timer.remaining / 3600).toString().padStart(2,'0');
    const m = Math.floor((timer.remaining % 3600)/60).toString().padStart(2,'0');
    const s = Math.floor(timer.remaining % 60).toString().padStart(2,'0');
    document.getElementById(timer.label).innerText = `${h}:${m}:${s}`;
}

// ---------------- TICK ---------------- //
function tickTimers() {
    Object.values(timers).forEach(timer => {
        if (timer.running && timer.remaining > 0) {
            timer.remaining -= 1;
            timer.chart.data.datasets[0].data = [timer.remaining, timer.total - timer.remaining];
            timer.chart.update();
            updateLabel(timer);

            // Guardar valor en localStorage
            localStorage.setItem(timer.id, timer.remaining);
        }
    });

    // lógica OFF continua...
    if(currentStatus === "OFF") {
        if(!offStartTime) offStartTime = Date.now();
        offElapsed = (Date.now() - offStartTime)/1000;
        if(offElapsed >= 10*3600){
            timers.drive.remaining = timers.drive.total;
            timers.shift.remaining = timers.shift.total;
            timers.drive.chart.data.datasets[0].data = [timers.drive.remaining,0];
            timers.shift.chart.data.datasets[0].data = [timers.shift.remaining,0];
            timers.drive.chart.update();
            timers.shift.chart.update();
            updateLabel(timers.drive);
            updateLabel(timers.shift);
            offStartTime = null;
            offElapsed = 0;

            // Guardar valores reiniciados
            localStorage.setItem(timers.drive.id, timers.drive.remaining);
            localStorage.setItem(timers.shift.id, timers.shift.remaining);
        }
    }
}

setInterval(tickTimers, 1000);

// ---------------- CAMBIO DE ESTADO ---------------- //
function changeStatus(newStatus) {
    if (currentStatus === "OFF" && newStatus !== "OFF") {
        offStartTime = null;
        offElapsed = 0;
    }

    currentStatus = newStatus;

    timers.drive.running = (newStatus === "D");
    timers.shift.running = (newStatus !== "OFF");
    timers.cycle.running = (newStatus !== "OFF");

    if(newStatus === "OFF") offStartTime = Date.now();

    // Guardar estado y tiempos en localStorage
    localStorage.setItem("currentStatus", currentStatus);
    Object.values(timers).forEach(timer => {
        localStorage.setItem(timer.id, timer.remaining);
    });

    // Refrescar etiquetas
    Object.values(timers).forEach(timer => updateLabel(timer));
}


// ---------------- CARGAR ESTADO INICIAL ---------------- //
let initialLog = @json($lastLog ?? null);
let savedStatus = localStorage.getItem("currentStatus");

// Punto 2: aquí decides cuál usar (localStorage o servidor)
currentStatus = savedStatus || (initialLog ? initialLog.status : "OFF");
changeStatus(currentStatus);

// ---------------- ACTUALIZAR AUTOMÁTICAMENTE ---------------- //
setInterval(() => {
    fetch("{{ route('driver.logs.latest') }}")
        .then(res => res.json())
        .then(data => {
            let lastStatus = data ? data.status : "OFF";
            if(lastStatus !== currentStatus){
                changeStatus(lastStatus);
            }
        });
}, 10000);

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
        icon: 'warning',
        title: 'Attention',
        text: "{{ session('alert_message') }}",
        confirmButtonText: 'OK'
    });
</script>

<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/leaflet.js') }}"></script>
<script src="{{ asset('js/chart.js') }}"></script>
@endif

@endsection
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
}
.card-item i { 
    font-size: 2rem; 
}
.card-logs   { background-color: #007bff; }
.card-support{ background-color: #28a745; }
.card-docs   { background-color: #001f3f; }

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

/* =================== Sidebar Inferior =================== */
.bottom-sidebar {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #fff;
    border-top: 2px solid #ddd;
    box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
    transition: height 0.3s ease-in-out;
    overflow: hidden;
    z-index: 999;
}

/* Altura contraída */
.bottom-sidebar.collapsed {
    height: 70px;
}

/* Altura expandida */
.bottom-sidebar.expanded {
    height: 60vh;
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

/* =================== Mapa =================== */
#map {
    width: 100%;
    height: 200px;
    background: #ddd;
    border-radius: 8px;
}
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
</style>

<!-- ======================= Contenido ======================= -->
<div class="app-body">
    <h1>Welcome to Truck Dashboard</h1>
    <p>Welcome, {{ auth()->guard('driver')->user()->name }}</p>

    <!-- =================== Contenedores principales =================== -->
    <div class="top-cards">
        <div class="card-item card-logs" onclick="location.href='{{ route('driver.logs.show') }}'">
            <i class="bi bi-bar-chart-line-fill"></i>

            <span>Logs</span>
            
        </div>
        <div class="card-item card-support" onclick="location.href='#'">
            <i class="fa-solid fa-headset"></i>

            <span>Support</span>
            
        </div>
        <div class="card-item card-docs" onclick="location.href='#'">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Docs</span>
            
        </div>
    </div>

    <!-- =================== Compliance =================== -->
    <div class="section-container">
        <div class="section-header">
            <span>Compliance</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.logs.show') }}'">
            <span>Unidentified trips</span>
            <span>5 &gt;</span>
        </div>
    </div>

    <!-- =================== Maintenance =================== -->
    <div class="section-container">
        <div class="section-header">
            <span>Maintenance</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.inspections') }}'">
            <span>Pre-trip Vehicle Inspection</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.inspections') }}'">
            <span>Post-trip Vehicle Inspection</span>
            <span>&gt;</span>
        </div>
        <div class="section-item" onclick="location.href='{{ route('driver.inspections') }}'">
            <span>Vehicle Inspection</span>
            <span>&gt;</span>
        </div>
    </div>

   
</div>

@php
$serverTimers = $initialTimers ?? [
    'drive_remaining' => 11*3600,
    'shift_remaining' => 14*3600,
    'cycle_remaining' => 70*3600,
    'current_status' => 'OFF'
];
@endphp

<!-- =================== Sidebar Inferior =================== -->
<div id="bottomSidebar" class="bottom-sidebar collapsed">

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


<!-- =================== JS =================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>

<script>
/* =================== Sidebar toggle =================== */
function toggleSidebar(){
    const sidebar = document.getElementById('bottomSidebar');
    sidebar.classList.toggle('collapsed');
    sidebar.classList.toggle('expanded');
}

/* =================== Timers y Charts =================== */
const serverTimers = @json($serverTimers);
const DRIVE_TOTAL = 11*3600;
const SHIFT_TOTAL = 14*3600;
const CYCLE_TOTAL = 70*3600;

function secondsToHMS(s){
    s = Math.max(0, Math.floor(s));
    const h = Math.floor(s/3600).toString().padStart(2,'0');
    const m = Math.floor((s%3600)/60).toString().padStart(2,'0');
    const sec = Math.floor(s%60).toString().padStart(2,'0');
    return `${h}:${m}:${sec}`;
}

function showRestAlert(timerName){
    Swal.fire({
        icon:'warning',
        title:'Rest Required',
        text:`Your ${timerName} time has ended. Please take a break and change duty status.`,
        confirmButtonText:'OK'
    });
}

let currentStatus = serverTimers.current_status || 'OFF';

const timers = {
    drive:{remaining:serverTimers.drive_remaining,total:DRIVE_TOTAL,running:(serverTimers.current_status==='D'),chart:null,labelId:'driveLabel',canvasId:'driveChart',name:'Drive'},
    shift:{remaining:serverTimers.shift_remaining,total:SHIFT_TOTAL,running:(serverTimers.current_status!=='OFF' && serverTimers.current_status!=='SB'),chart:null,labelId:'shiftLabel',canvasId:'shiftChart',name:'Shift'},
    cycle:{remaining:serverTimers.cycle_remaining,total:CYCLE_TOTAL,running:(serverTimers.current_status!=='OFF'),chart:null,labelId:'cycleLabel',canvasId:'cycleChart',name:'Cycle'}
};

function createDoughnutChart(canvasId,initialRemaining,total,color){
    const ctx = document.getElementById(canvasId).getContext('2d');
    return new Chart(ctx,{
        type:'doughnut',
        data:{labels:['Remaining','Elapsed'],datasets:[{data:[initialRemaining,Math.max(0,total-initialRemaining)],backgroundColor:[color,'#e9ecef'],borderWidth:0}]},
        options:{plugins:{legend:{display:false}},responsive:true,maintainAspectRatio:false,cutout:'70%'}
    });
}

timers.drive.chart = createDoughnutChart(timers.drive.canvasId,timers.drive.remaining,timers.drive.total,'#007bff');
timers.shift.chart = createDoughnutChart(timers.shift.canvasId,timers.shift.remaining,timers.shift.total,'#28a745');
timers.cycle.chart = createDoughnutChart(timers.cycle.canvasId,timers.cycle.remaining,timers.cycle.total,'#6c757d');

Object.values(timers).forEach(t=>{
    const el = document.getElementById(t.labelId);
    if(el) el.innerText = secondsToHMS(t.remaining);
    // también sidebar
    const sbEl = document.getElementById(t.labelId.replace('Label','SidebarTimer'));
    if(sbEl) sbEl.innerText = secondsToHMS(t.remaining);
});

let lastTick = Date.now();

function tickClient(){
    const now = Date.now();
    const deltaSec = Math.floor((now-lastTick)/1000);
    if(deltaSec<=0) return;

    Object.values(timers).forEach(timer=>{
        if(timer.running && timer.remaining>0){
            timer.remaining = Math.max(0,timer.remaining - deltaSec);
            timer.chart.data.datasets[0].data = [timer.remaining, Math.max(0,timer.total-timer.remaining)];
            timer.chart.update();
            const lbl = document.getElementById(timer.labelId);
            if(lbl) lbl.innerText = secondsToHMS(timer.remaining);
            const sbEl = document.getElementById(timer.labelId.replace('Label','SidebarTimer'));
            if(sbEl) sbEl.innerText = secondsToHMS(timer.remaining);
            if(timer.remaining===0){
                timer.running=false;
                showRestAlert(timer.name);
            }
        }
    });
    lastTick = now;
}

setInterval(tickClient,1000);

function recalibrateFromServer(){
    fetch("{{ route('driver.timers') }}",{method:'GET',credentials:'same-origin'})
        .then(res=>{if(!res.ok)throw new Error('Network response not ok');return res.json()})
        .then(data=>{
            if(data.drive_remaining!==undefined) timers.drive.remaining=parseInt(data.drive_remaining,10);
            if(data.shift_remaining!==undefined) timers.shift.remaining=parseInt(data.shift_remaining,10);
            if(data.cycle_remaining!==undefined) timers.cycle.remaining=parseInt(data.cycle_remaining,10);

            timers.drive.running = (data.current_status==='D');
            timers.shift.running = (data.current_status!=='OFF' && data.current_status!=='SB');
            timers.cycle.running = (data.current_status!=='OFF');

            Object.values(timers).forEach(t=>{
                t.chart.data.datasets[0].data=[t.remaining,Math.max(0,t.total-t.remaining)];
                t.chart.update();
                const lbl=document.getElementById(t.labelId);
                if(lbl) lbl.innerText = secondsToHMS(t.remaining);
                const sbEl = document.getElementById(t.labelId.replace('Label','SidebarTimer'));
                if(sbEl) sbEl.innerText = secondsToHMS(t.remaining);
            });

            currentStatus = data.current_status||currentStatus;
            lastTick=Date.now();
        })
        .catch(err=>console.error('Recalibrate error',err));
}

document.addEventListener('visibilitychange',()=>{if(document.visibilityState==='visible'){recalibrateFromServer();}});
setInterval(recalibrateFromServer,5*60*1000);

setInterval(()=>{
    fetch("{{ route('driver.logs.latest') }}",{method:'GET',credentials:'same-origin'})
        .then(res=>res.ok?res.json():Promise.reject('no ok'))
        .then(lastLog=>{
            const lastStatus = lastLog ? lastLog.status:'OFF';
            if(lastStatus!==currentStatus){
                currentStatus=lastStatus;
                timers.drive.running=(currentStatus==='D');
                timers.shift.running=(currentStatus!=='OFF' && currentStatus!=='SB');
                timers.cycle.running=(currentStatus!=='OFF');
                recalibrateFromServer();
            }
        }).catch(err=>{});
},10000);

/* =================== Mapa =================== */
const map = L.map('map').setView([0,0],13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'Map data © OpenStreetMap contributors'}).addTo(map);
const marker = L.marker([0,0]).addTo(map);
function updateLocation(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(pos=>{
            const lat=pos.coords.latitude;
            const lng=pos.coords.longitude;
            marker.setLatLng([lat,lng]);
            map.setView([lat,lng],13);
        },err=>{}, {enableHighAccuracy:true,maximumAge:5000,timeout:5000});
    }
}
updateLocation();
setInterval(updateLocation,3000);

/* =================== Inicialización =================== */
(function init(){
    timers.drive.running = (serverTimers.current_status==='D');
    timers.shift.running = (serverTimers.current_status!=='OFF' && serverTimers.current_status!=='SB');
    timers.cycle.running = (serverTimers.current_status!=='OFF');
    recalibrateFromServer();
    Object.values(timers).forEach(t=>{
        if(!Number.isFinite(t.remaining)||t.remaining<0){
            t.remaining=t.total;
            t.chart.data.datasets[0].data=[t.remaining,0];
            t.chart.update();
            const lbl=document.getElementById(t.labelId);
            if(lbl) lbl.innerText=secondsToHMS(t.remaining);
            const sbEl = document.getElementById(t.labelId.replace('Label','SidebarTimer'));
            if(sbEl) sbEl.innerText = secondsToHMS(t.remaining);
        }
    });
    lastTick=Date.now();
})();
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

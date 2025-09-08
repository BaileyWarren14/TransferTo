@extends('layouts.app')


@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

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

</style>


<h1>Welcome to Truck Dashboard</h1>
    <p>Bienvenido, {{ auth()->guard('driver')->user()->name }}</p>
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
   

function createCountdownChart(canvasId, labelId, totalHours, color) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    const totalSeconds = totalHours * 3600;

    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Remaining', 'Elapsed'],
            datasets: [{
                data: [totalSeconds, 0],
                backgroundColor: [color, '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            rotation: 0,
            circumference: 360,
            plugins: { legend: { display: false } }
        }
    });

    function formatTime(seconds) {
        const h = Math.floor(seconds / 3600).toString().padStart(2,'0');
        const m = Math.floor((seconds % 3600) / 60).toString().padStart(2,'0');
        const s = Math.floor(seconds % 60).toString().padStart(2,'0');
        return `${h}:${m}:${s}`;
    }

    function update() {
        if(chart.data.datasets[0].data[0] > 0){
            chart.data.datasets[0].data[0] -= 1;
            chart.data.datasets[0].data[1] += 1;
            chart.update();
            document.getElementById(labelId).innerText = formatTime(chart.data.datasets[0].data[0]);
        }
    }

    document.getElementById(labelId).innerText = formatTime(totalSeconds);
    setInterval(update, 1000);
    return chart;
}

// Crear los tres cronómetros con colores diferentes
createCountdownChart('driveChart', 'driveLabel', 11, '#007bff');
createCountdownChart('shiftChart', 'shiftLabel', 14, '#28a745');
createCountdownChart('cycleChart', 'cycleLabel', 70, '#6c757d');

// Leaflet Map: Real-time GPS
const map = L.map('map').setView([0,0], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © OpenStreetMap contributors'
}).addTo(map);

const marker = L.marker([0,0]).addTo(map);

function updateLocation() {
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 13);
        }, err => { 
            console.error("Error obtaining GPS location: " + err.message); 
        });
    } else { 
        console.error("Geolocation not supported."); 
    }
}

updateLocation();
setInterval(updateLocation, 300000); // actualizar cada 5 min


</script>


@endsection
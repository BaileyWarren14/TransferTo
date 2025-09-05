<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    #map { height: 400px; width: 100%; margin-top: 20px; }
    .chart-container { width: 150px; display: inline-block; margin: 20px; position: relative; }
    .chart-container canvas { position: absolute; left: 0; top: 0; }
    .chart-label { position: absolute; width: 100%; text-align: center; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 14px; }
</style>
</head>
<body class="container">

<h1 class="mt-3">Truck Dashboard</h1>

<div class="d-flex justify-content-center">
    <div class="chart-container" style="width: 200px; height: 200px;">
        <canvas id="driveChart"></canvas>
        <div class="chart-label" id="driveLabel"></div>
        <p class="text-center">Drive (11h)</p>
    </div>
    <div class="chart-container" style="width: 200px; height: 200px;">
        <canvas id="shiftChart"></canvas>
        <div class="chart-label" id="shiftLabel"></div>
        <p class="text-center">Shift (14h)</p>
    </div>
    <div class="chart-container" style="width: 200px; height: 200px;">
        <canvas id="cycleChart"></canvas>
        <div class="chart-label" id="cycleLabel"></div>
        <p class="text-center">Cycle (70h)</p>
    </div>
</div>

<div id="map"></div>

<script>
   function createCountdownChart(id, labelId, totalHours) {
    const ctx = document.getElementById(id).getContext('2d');
    const totalSeconds = totalHours * 3600;

    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Remaining', 'Elapsed'],
            datasets: [{
                data: [totalSeconds, 0],
                backgroundColor: ['#007bff', '#e9ecef'],
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

const driveChart = createCountdownChart('driveChart', 'driveLabel', 11);
const shiftChart = createCountdownChart('shiftChart', 'shiftLabel', 14);
const cycleChart = createCountdownChart('cycleChart', 'cycleLabel', 70);
    // 🌍 Leaflet Map: Real-time GPS
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
    setInterval(updateLocation, 300000); // update every 5 min
</script>

</body>
</html>

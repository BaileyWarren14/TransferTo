<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truck Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body { padding: 20px; }
        .timer { font-size: 2rem; margin-bottom: 20px; }
        #map { height: 400px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4">Truck Dashboard</h1>

    <div class="row text-center">
        <div class="col-md-4">
            <div class="timer" id="driveTimer">11:00:00</div>
            <p>Drive</p>
        </div>
        <div class="col-md-4">
            <div class="timer" id="shiftTimer">14:00:00</div>
            <p>Shift</p>
        </div>
        <div class="col-md-4">
            <div class="timer" id="cycleTimer">70:00:00</div>
            <p>Cycle</p>
        </div>
    </div>

    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// ===== Timers =====
function startTimer(id, seconds) {
    const el = document.getElementById(id);
    setInterval(() => {
        if (seconds > 0) seconds--;
        let h = Math.floor(seconds / 3600);
        let m = Math.floor((seconds % 3600) / 60);
        let s = seconds % 60;
        el.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }, 1000);
}

// Inicializamos timers
startTimer('driveTimer', 11*3600);
startTimer('shiftTimer', 14*3600);
startTimer('cycleTimer', 70*3600);

// ===== Map =====
// Coordenadas iniciales
let lat = 20.6597;
let lng = -103.3496;

let map = L.map('map').setView([lat, lng], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let marker = L.marker([lat, lng]).addTo(map).bindPopup("Truck Location").openPopup();

// Simulación de movimiento
function updateLocation() {
    lat += (Math.random() - 0.5) / 1000;
    lng += (Math.random() - 0.5) / 1000;
    marker.setLatLng([lat, lng]);
    map.setView([lat, lng]);
}

// Actualiza cada 5 minutos (para test podemos poner 5 segundos)
setInterval(updateLocation, 5000); // cambia 5000 a 300000 para 5 minutos reales
</script>

</body>
</html>

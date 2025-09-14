@extends('layouts.app')

@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logbook Chart</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background-color: #f5f5f5;
    }
    .chart-container {
        width: 100%;
        
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        height: 200px; /* altura fija para ver cuadrícula clara */
    }
    canvas {
        width: 100% !important;
        height: 100% !important;
    }
</style>

<h2 style="text-align:center;">Logbook Style Chart</h2>

<div class="chart-container">
    <canvas id="logbookChart"></canvas>
</div>

<script>
const ctx = document.getElementById('logbookChart').getContext('2d');

// Etiquetas cada 15 minutos
let labels = [];
for (let h = 0; h < 24; h++) {
    labels.push(`${h}:00`);
    labels.push(`${h}:15`);
    labels.push(`${h}:30`);
    labels.push(`${h}:45`);
}

// Categorías Y
const yCategories = ['OFF', 'SB', 'D', 'ON', 'WT'];
const statusMap = { 'OFF': 0, 'SB': 1, 'D': 2, 'ON': 3, 'WT': 4 };

// Datos de ejemplo (ajústalos a tu caso)
const data = [];
for (let i = 0; i < labels.length; i++) {
    if (i < 20) data.push(statusMap.OFF);
    else if (i < 40) data.push(statusMap.D);
    else if (i < 60) data.push(statusMap.ON);
    else data.push(statusMap.SB);
}

const logbookChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Driver Status',
            data: data,
            borderColor: 'blue',
            borderWidth: 2,
            pointRadius: 0,
            tension: 0,
            stepped: true,
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 2.5,
        scales: {
            y: {
                type: 'category',
                labels: yCategories,
                reverse: true,
                grid: {
                    drawTicks: true,
                    color: '#ccc'
                },
                title: {
                    display: true,
                    text: 'Status'
                }
            },
            x: {
                grid: {
                    drawTicks: true,
                    color: (ctx) => {
                        // Hora completa (más fuerte) vs subdivisiones (más claras)
                        return ctx.tick.label && ctx.tick.label.includes(":00") ? '#666' : '#ddd';
                    }
                },
                ticks: {
                    autoSkip: false,
                    callback: function(value, index) {
                        // Solo mostrar etiquetas de hora completa
                        return labels[index].includes(":00") ? labels[index] : '';
                    },
                    maxRotation: 90,
                    minRotation: 90
                },
                title: {
                    display: true,
                    text: 'Hour'
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const yIndex = context.raw;
                        return `Status: ${yCategories[yIndex]}`;
                    }
                }
            }
        }
    }
});
</script>
@endsection

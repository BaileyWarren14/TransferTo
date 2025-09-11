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
        max-width: 1000px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
    }
    canvas {
        width: 100% !important;
        height: auto !important;
    }
</style>
</head>
<body>

<h2 style="text-align:center;">Logbook Style Chart</h2>

<div class="chart-container">
    <canvas id="logbookChart"></canvas>
</div>

<script>
const ctx = document.getElementById('logbookChart').getContext('2d');

// Eje X: m1-m11, n1-n11, m1
const labels = [
    'M','1','2','3','4','5','6','7','8','9','10','11','N',
    '1','2','3','4','5','6','7','8','9','10','11',
    'M'
];

// Eje Y: off, sb, d, on → convertimos a valores numéricos para la gráfica
const yCategories = ['off', 'sb', 'd', 'on'];


// Ejemplo de datos
const data = [
    0,0,0,0,0,1,2,3,0,1,2,
    3,0,1,2,3,0,1,2,3,0,1,
    2,0,0
];

// Configuración del gráfico
const logbookChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Driver Status',
            data: data,
            borderColor: 'black',
            borderWidth: 2,
            pointRadius: 0,
            tension: 0,
            stepped: true,
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // << clave para móvil
        aspectRatio: 2, // proporción ancho/alto (ajústalo a tu gusto, ej. 1.5 para más alto)
        scales: {
            y: {
                type: 'category',
                labels: yCategories,
                grid: {
                    drawTicks: false,
                    color: '#ccc'
                },
                title: {
                    display: true,
                    text: 'Status'
                }
            },
            x: {
                grid: {
                    drawTicks: false,
                    color: '#eee'
                },
                title: {
                    display: true,
                    text: 'Time Segments'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
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
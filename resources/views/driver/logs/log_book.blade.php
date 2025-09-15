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
        padding-top: 20px;
        
        
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        height: 150px; /* altura fija para ver cuadrícula clara */
    }
    canvas {
        width: 100% !important;
        height: 100% !important;
    }
    /* Altura dinámica según pantalla */
    @media (max-width: 576px) { /* móviles */
        .chart-container {
            height: 150px; 
            width: 95vw; /* ocupar todo el ancho de la pantalla */
            margin-left: -25px; 
            margin-right: -30px; 
            border-radius: 0; /* opcional: quitar bordes en móvil */
        }
    }
    @media (min-width: 577px) and (max-width: 992px) { /* tablets */
        .chart-container {
            height: 150px; 
            width: 95vw; /* ocupar todo el ancho de la pantalla */
            margin-left: -25px; 
            margin-right: -30px; 
            border-radius: 0; /* opcional: quitar bordes en móvil */
            
        }
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

// Medianoche inicio
labels.push("M"); // etiqueta visible
labels.push('');
    labels.push('');
    labels.push('');
// De 1 a 11 AM
for (let h = 1; h <= 11; h++) {
    labels.push(h.toString());   // hora completa, etiqueta visible
    labels.push('');              // +15 min, sin etiqueta
    labels.push('');              // +30 min, sin etiqueta
    labels.push('');              // +45 min, sin etiqueta
}

// Mediodía
labels.push("N"); // etiqueta visible
labels.push('');
    labels.push('');
    labels.push('');

// De 1 a 11 PM
for (let h = 1; h <= 11; h++) {
    labels.push(h.toString());   // hora completa
    labels.push('');
    labels.push('');
    labels.push('');
}

// Medianoche fin
labels.push("M"); // etiqueta visible

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
                    text: ''
                }
                
            },
            x: {
                grid: {
                    
                     drawTicks: true,
                    tickLength: 5,
                    // Colores de línea según subdivisión
                    color: function(ctx) {
                        const index = ctx.index;
                        // Línea principal en la etiqueta de hora
                        if (index % 4 === 0) return '#666'; 
                        // Líneas subdivisión
                        return '#ddd';
                    },
                    borderColor: '#333'
                },
                ticks: {
                    autoSkip: false,
                    
                    callback: function(value, index) {
                        // Solo mostrar etiquetas de hora completa
                        return labels[index];
                    },
                     font: {
                        size: 10 // tamaño de labels eje X
                    },
                    maxRotation: 0,
                    minRotation: 0
                },
                title: {
                    display: true,
                    text: ''
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

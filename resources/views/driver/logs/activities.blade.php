@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Activities for {{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</h4>

    <!-- Botón para regresar a show -->
    <a href="{{ route('driver.logs.show') }}" class="btn btn-secondary mb-3">
        ← Back to Logbook
    </a>
    <!-- Gráfica con resumen de estados -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Today</span>
            <a href="{{ route('driver.logs.activities', ['date' => \Carbon\Carbon::now('America/Mexico_City')->toDateString()]) }}" class="btn btn-light btn-sm">➡️</a>
        </div>
        <div class="card-body text-center">
            <h5>{{ \Carbon\Carbon::now('America/Mexico_City')->format('l, M d, Y') }}</h5>

            
            <div class="d-flex justify-content-between align-items-start">
                 <!-- Gráfica -->
                <div class="chart-container" style="height:200px; flex: 0 0 95%; max-width:95%; min-width:90%">
                    <canvas id="logbookChart"></canvas>
                </div>

                <!-- Resumen compacto al lado derecho -->
                <div class="state-summary" 
                    style="flex: 0 0 5%; max-width:5%; min-width:10%; font-size:0.65rem; text-align:left; margin-left:5px; line-height:3.7;">
                    <ul id="stateSummaryList" class="list-unstyled mb-0"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de actividades -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">Activity Details</div>
        <div class="card-body">
            @foreach($activities as $activity)
                <div class="mb-3 p-2 rounded border"
                    style="cursor:pointer;"
                    onclick="window.location='{{ route('driver.duty_status.edit', $activity['id']) }}'">
                    <span class="badge bg-secondary rounded-circle p-2">
                        {{ $activity['status'] }}
                    </span>
                    <strong>{{ $activity['time'] }}</strong> |
                    {{ $activity['duration'] }} <br>
                    <small class="text-muted">{{ $activity['location'] }}</small>
                </div>
            @endforeach
        </div>
    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($labels);
const dutyStatuses = @json($dutyStatuses);

// Categorías del eje Y
const yCategories = ['OFF', 'SB', 'D', 'ON', 'WT'];

const minutesPerBlock = 1;
const stateTimes = { 'OFF':0, 'SB':0, 'D':0, 'ON':0, 'WT':0 };

// Calcular tiempo por estado
dutyStatuses.forEach(statusIndex => {
    if (statusIndex === null) return; // ignorar bloques futuros
    const state = yCategories[statusIndex];
    stateTimes[state] += minutesPerBlock;
});

// 🔹 Mostrar todos los estados aunque estén en 0
const stateSummary = [];
let totalMinutes = 0;

for (const [state, mins] of Object.entries(stateTimes)) {
    const hours = Math.floor(mins / 60);
    const remainingMins = mins % 60;
    stateSummary.push(`${hours}h ${remainingMins}m`);
    totalMinutes += mins;
}

// Llenar lista compacta
const summaryList = document.getElementById('stateSummaryList');
stateSummary.forEach(text => {
    const li = document.createElement('li');
    li.textContent = text;
    summaryList.appendChild(li);
});

const ctx = document.getElementById('logbookChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Driver Status',
            data: dutyStatuses,   // 👈 aquí corregido
            borderColor: 'blue',
            borderWidth: 2,
            pointRadius: 0,
            tension: 0,
            stepped: true,
            fill: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 2.5,
        scales: {
            y: {
                type: 'linear',
                min: 0,
                max: 4,
                reverse: true,
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return yCategories[value] ?? value;
                    }
                },
                grid: { drawTicks: true, color: '#ccc' }
            },
            x: {
                grid: {
                    drawTicks: true,
                    tickLength: 5,
                    color: ctx => {
                        if (ctx.index % 60 === 0) {
                            return '#444'; // línea fuerte cada 60 min
                        } else if (ctx.index % 15 === 0) {
                            return '#aaa'; // línea fina cada 15
                        } else {
                            return 'transparent';
                        }
                    },
                    borderColor: '#333'
                },
                ticks: {
                    autoSkip: false,
                    callback: function(value, index) {
                        return index % 60 === 0 ? labels[index] : '';
                    },
                    font: { size: 10 },
                    maxRotation: 0,
                    minRotation: 0
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `Status: ${yCategories[context.raw]} - ${labels[context.dataIndex]}`;
                    }
                }
            }
        }
    }
});
</script>
@endsection

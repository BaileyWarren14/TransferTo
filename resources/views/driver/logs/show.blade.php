@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back
</a>
<div class="container mt-4">

    <!-- Hoy -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Today</span>
            <a href="{{ route('driver.logs.show') }}" class="btn btn-light btn-sm">➡️</a>
        </div>
        <div class="card-body text-center">
            <h5>{{ \Carbon\Carbon::now('America/Mexico_City')->format('l, M d, Y') }}</h5>

            <p>{{ $totalOnDutyHours }} hr {{ $totalOnDutyMins }} min</p>
            <div class="chart-container" style="height:200px;">
                <canvas id="logbookChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Últimos 14 días -->
    <div class="card shadow mb-4">
        <div class="card-header bg-secondary text-white">Last 14 Days</div>
        <div class="card-body">
            @foreach($last14Days as $date => $logs)
                @php
                    $firstOff = null;
                    $totalMinutes = 0;
                    foreach($logs as $log){
                        if($log->status === 'OFF'){
                            $firstOff = \Carbon\Carbon::parse($log->changed_at);
                        } else {
                            if($firstOff){
                                $totalMinutes += \Carbon\Carbon::parse($log->changed_at)->diffInMinutes($firstOff);
                                $firstOff = null;
                            }
                        }
                    }
                    $hours = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;
                @endphp
                <div class="d-flex justify-content-between align-items-center border p-2 mb-2 rounded">
                    <div>
                        <strong>{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</strong><br>
                        {{ $hours }} hr {{ $minutes }} min
                    </div>
                    <a href="{{ route('driver.logs.show') }}" class="btn btn-primary btn-sm">➡️</a>
                </div>
            @endforeach
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 📌 Recibimos directamente desde PHP
    const labels = @json($labels);
    const duty_status = @json($dutyStatuses);
    const rawLogs = @json($rawLogs);
    
    // Debug temporal
    alert(
        "📌 Registros originales de la BD:\n" +
        JSON.stringify(rawLogs, null, 2) +
        "\n\n📌 Labels (96 bloques):\n" +
        JSON.stringify(labels) +
        "\n\n📌 Duty Statuses (96 valores):\n" +
        JSON.stringify(duty_status)
    );

    // Opcional: debug
    console.log("Registros originales:", rawLogs);
    console.log("96 bloques:", labels);
    console.log("Estados de cada bloque:", duty_status);

    // Categorías del eje Y
    const yCategories = ['OFF', 'SB', 'D', 'ON', 'WT'];

    

    const ctx = document.getElementById('logbookChart').getContext('2d');
    new Chart(ctx, {
          type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Driver Status',
                    data: duty_status,       // 96 valores
                    borderColor: 'blue',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0,
                    stepped: true,    // línea escalonada
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
                        color: ctx => ctx.index % 4 === 0 ? '#666' : '#ddd',
                        borderColor: '#333'
                    },
                    ticks: {
                        autoSkip: false,
                        callback: function(value, index) {
                            // Mostrar solo las etiquetas de cada hora (cada 4 bloques de 15 min)
                            return index % 4 === 0 ? labels[index] : '';
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
                            return `Status: ${yCategories[context.raw]}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection

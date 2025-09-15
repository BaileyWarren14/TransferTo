@extends('layouts.app')

@section('content')
<div class="container">
    <div class="logbook-entry text-center mb-4">
        <!-- Parte superior -->
        <h4>
            {{ \Carbon\Carbon::parse($logbook->date)->format('l, M d') }}
        </h4>

        <!-- Parte media -->
        <p>Total ON DUTY Hours: <strong>{{ $logbook->total_on_duty_hours }}</strong></p>
        <p>
            Inspection: 
            @if($logbook->inspection)
                <span class="text-success">✅ Hubo inspección</span>
            @else
                <span class="text-danger">❌ No hubo inspección</span>
            @endif
        </p>

        <!-- Parte inferior: Chart -->
        <div class="chart-container">
            <canvas id="logbookChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('logbookChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($labels) !!}, 
        datasets: [{
            label: 'Duty Status',
            data: {!! json_encode($logbook->duty_statuses) !!},
            borderColor: 'blue',
            borderWidth: 2,
            pointRadius: 0,
            stepped: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { 
                type: 'category', 
                labels: ['OFF','SB','D','ON','WT'], 
                reverse: true 
            }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">Logbook Details</div>
        <div class="card-body">
            <!-- Gráfica interactiva -->
            <div class="chart-container mb-4" style="height:300px;">
                <canvas id="detailChart"></canvas>
            </div>

            <!-- Tabla de logs -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \App\Helpers\TimeHelper::userTime($log->changed_at, 'H:i') }}</td>
                            <td>{{ $log->status }}</td>
                            <td>
                                <a href="{{ route('driver.logs.edit', $log->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('driver.logs.destroy', $log->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($labels);
const dutyStatuses = @json($dutyStatuses);
const yCategories = ['OFF','SB','D','ON','WT'];

// Convertir a índices para ChartJS
const statusIndices = dutyStatuses.map(s => s ? yCategories.indexOf(s) : null);

const ctx = document.getElementById('detailChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Driver Status',
            data: statusIndices,
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
        scales: {
            y: {
                type: 'linear',
                min: 0,
                max: 4,
                reverse: true,
                ticks: {
                    stepSize: 1,
                    callback: function(value) { return yCategories[value] ?? value; }
                },
                grid: { drawTicks: true, color: '#ccc' }
            },
            x: {
                grid: { drawTicks: true, tickLength: 5, color: '#ccc', borderColor:'#333' },
                ticks: { autoSkip:true, maxRotation:0, minRotation:0 }
            }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection
    
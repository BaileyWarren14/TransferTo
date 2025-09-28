@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <a href="{{ route('driver.logs.show') }}" class="btn btn-secondary mb-3">⬅️ Back</a>

    <!-- Gráfica de 24h -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">Today - 24h Graph</div>
        <div class="card-body">
            <canvas id="logbookChart" style="height:250px;"></canvas>
        </div>
    </div>

    <!-- Tabla editable de logs -->
    <div class="card shadow mb-4">
        <div class="card-header bg-secondary text-white">Logs Details</div>
        <div class="card-body table-responsive">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('H:i') }}</td>
                            <td>{{ $log->status }}</td>
                            <td>{{ $log->location }}</td>
                            <td>{{ $log->notes }}</td>
                            <td class="d-flex gap-1">
                                <!-- Editar -->
                                <form action="{{ route('driver.logs.update', $log->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-warning">Edit</button>
                                </form>
                                <!-- Eliminar -->
                                <form action="{{ route('driver.logs.destroy', $log->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('logbookChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Duty Status',
                data: @json($dutyStatuses),
                borderColor: 'blue',
                borderWidth: 2,
                pointRadius: 0,
                stepped: true,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'category',
                    labels: ['OFF','SB','D','ON','WT']
                }
            }
        }
    });
</script>
@endsection

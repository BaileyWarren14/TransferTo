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
            <h5>{{ \Carbon\Carbon::today()->format('l, M d, Y') }}</h5>
            <p>{{ $totalOnDutyHours }} hr {{ $totalOnDutyMins }} min</p>
            <div class="chart-container" style="height:200px;">
                <canvas id="todayChart"></canvas>
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
    alert(
        "Labels: " + {!! json_encode($labels) !!} + "\n" +
        "Duty Statuses: " + {!! json_encode($dutyStatuses) !!}
    );
const ctx = document.getElementById('todayChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Duty Status',
            data: {!! json_encode($dutyStatuses) !!},
            borderColor: 'blue',
            borderWidth: 2,
            stepped: true,
            pointRadius: 0,
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'category',
                labels: ['OFF','SB','D','ON','WT','PC','YM'],
                reverse: true
            }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection



<!--

@extends('layouts.app')

@section('content')

    <a href="{{ url()->previous() }}" class="btn btn-secondary">
       <i class="fas fa-arrow-left me-1"></i> Back
    </a>
<div class="container mt-4">

    <!-- Gráfica del día -->
    <!--<div class="logbook-entry text-center mb-4 card p-3 shadow-lg rounded-4">
        <h4>{{ \Carbon\Carbon::today()->format('l, M d, Y') }}</h4>
        <p>Total ON DUTY Hours: <strong>{{ $totalOnDutyHours }}</strong></p>

        <div class="chart-container">
            <canvas id="logbookChart"></canvas>
        </div>
    </div>

    <!-- Logs últimos 14 días -->
    <!--<div class="card shadow-lg rounded-4">
        <div class="card-header bg-dark text-white text-center rounded-top-4">
            <h5 class="mb-0">📝 Last 14 Days Logs</h5>
        </div>
        <div class="card-body">
            @if($logs->isEmpty())
                <div class="alert alert-warning text-center">No logs found.</div>
            @else
                <table class="table table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('H:i:s') }}</td>
                                <td>
                                    @php
                                        $colors = ['ON'=>'success','OFF'=>'secondary','SB'=>'info','D'=>'primary','WT'=>'warning','PC'=>'dark','YM'=>'danger'];
                                    @endphp
                                    <span class="badge bg-{{ $colors[$log->status] ?? 'secondary' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td>{{ $log->location }}</td>
                                <td>{{ $log->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <div class="card shadow-lg rounded-4">
    <div class="card-header bg-dark text-white text-center rounded-top-4">
        <h5 class="mb-0">📝 Last 14 Days Summary</h5>
    </div>
    <div class="card-body">
        @if(empty($summary) || count($summary) === 0)
            <div class="alert alert-warning text-center">No logs found.</div>
        @else
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Total ON Duty Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary as $day)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($day['date'])->format('l, M d') }}
                            </td>
                            <td>
                                <strong>{{ $day['onDutyHours'] }}</strong> hrs
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
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
            data: {!! json_encode($dutyStatuses) !!},
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
                labels: ['OFF','SB','D','ON','WT','PC','YM'],
                reverse: true
            }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection

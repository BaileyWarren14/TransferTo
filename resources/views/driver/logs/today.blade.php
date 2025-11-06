@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary">
       <i class="fas fa-arrow-left me-1"></i> <span data-key="back">Back</span>
    </a>
<div class="container-fluid mt-3 px-0">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-dark text-white text-center rounded-top-4">
            <h4 class="mb-0" >📘 <span data-key="today_duty_logs">Today's Duty Log </span></h4>
        </div>
        <div class="card-body p-0">
            @if($todayLogs->isEmpty())
                <div class="alert alert-warning text-center m-3">
                    <span data-key="no_records">No records found for today.</span>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th data-key="time">Time</th>
                                <th data-key="status">Status</th>
                                <th data-key="location">Location</th>
                                <th data-key="notes">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayLogs as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
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
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    body.dark-mode {
        background-color: #121212;
        color: #f0f0f0;
    }

    body.dark-mode .card {
        background-color: #1e1e1e;
        color: #f0f0f0;
        border: 1px solid #333;
    }

    body.dark-mode .table {
        color: #f0f0f0;
        background-color: #1e1e1e;
        border-color: #333;
    }

    body.dark-mode .table thead {
        background-color: #2c2c2c;
        color: #f0f0f0;
    }

    body.dark-mode .table tbody tr {
        border-bottom: 1px solid #444;
    }

    body.dark-mode .table-bordered th,
    body.dark-mode .table-bordered td {
        border: 1px solid #444;
    }

    body.dark-mode .badge {
        color: #fff;
    }

    body.dark-mode .alert-warning {
        background-color: #333;
        color: #f0f0f0;
        border-color: #444;
    }

    /* Ajustes para móviles: ancho completo y sin margen */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .table-responsive {
            margin: 0;
        }

        .card {
            border-radius: 0.5rem;
        }
    }
</style>


@if (session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: "Duty Status change successfully saved",
        icon: 'success',
        confirmButtonText: 'accept',
        confirmButtonColor: '#3085d6',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif
@endsection

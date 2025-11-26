@extends('layouts.app')

@section('content')
<div class="container py-4 hos-container">
    <h2 class="fw-bold mb-4">Driver HOS Reports</h2>

    @if(count($allViolations) > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($allViolations as $v)
                <div class="hos-item p-3 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-primary">{{ $v['driver'] }}</span>
                        <span class="hos-date small">{{ $v['display_date'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold text-danger">{{ $v['category'] }}</span>

                        <span>{{ $v['message'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="fst-italic text-muted">No violations detected.</p>
    @endif
</div>

<style>
    /* ======== Estilo general modo claro ======== */
    .hos-container {
        background-color: #f8f9fa;
        color: #212529;
        border-radius: 8px;
        min-height: 100vh;
        transition: background-color 0.4s, color 0.4s;
    }

    .hos-item {
        background-color: #ffffff;
        border: none;
        transition: background-color 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .hos-item:hover {
        background-color: #e9ecef;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    }

    .hos-date {
        color: #6c757d;
        transition: color 0.3s;
    }

    /* ======== Modo oscuro ======== */
    body.dark-mode .hos-container {
        background-color: #0d1117;
        color: #e9ecef;
    }

    body.dark-mode .hos-item {
        background-color: #1c1f26;
        color: #e9ecef;
    }

    body.dark-mode .hos-item:hover {
        background-color: #0d1621;
        box-shadow: 0 0 15px rgba(0, 128, 255, 0.4);
    }

    body.dark-mode .hos-date {
        color: #adb5bd;
    }

    body.dark-mode h2 {
        color: #f1f3f5;
    }
</style>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4 hos-container">
    <h2 class="fw-bold mb-4" data-key="driver_hos_reports">Driver HOS Reports</h2>

    
    <form method="GET"
      action="{{ url('/admin/reports/filter') }}"
      class="card p-3 mb-4 shadow-sm">

        <div class="row g-3 align-items-end">

            <!-- Driver -->
            <div class="col-md-3">
                <label class="form-label fw-semibold" data-key="driver">Driver</label>
                <input type="text"
                    name="driver"
                    value="{{ request('driver') }}"
                    class="form-control"
                    placeholder="Driver name" data-key="driver_names">
            </div>

            <!-- Category -->
            <div class="col-md-3">
                <label class="form-label fw-semibold" data-key="category">Category</label>
                <select name="category" class="form-select">
                    <option value="" data-key="all">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}"
                            {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- From -->
            <div class="col-md-2">
                <label class="form-label fw-semibold" data-key="desde">From</label>
                <input type="date"
                    name="from"
                    value="{{ request('from') }}"
                    class="form-control">
            </div>

            <!-- To -->
            <div class="col-md-2">
                <label class="form-label fw-semibold" data-key="to">To</label>
                <input type="date"
                    name="to"
                    value="{{ request('to') }}"
                    class="form-control">
            </div>

            <!-- Buttons -->
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <span data-key="filter">Filter</span>
                </button>

                <a href="{{ url('/reports') }}"
                class="btn btn-outline-secondary w-100">
                    <span data-key="clear">Clear</span>
                </a>
            </div>

        </div>
    </form>


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
        <p class="fst-italic text-muted" data-key="no_violations_detected">No violations detected.</p>
    @endif
</div>

<style>
    
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
    body.dark-mode .card {
    background-color: #1c1f26;
    border: none;
     color: #e9ecef;
    }

    body.dark-mode .form-control,
    body.dark-mode .form-select {
        background-color: #0d1117;
        color: #e9ecef;
        border-color: #2c313a;
    }

    body.dark-mode .form-control::placeholder {
        color: #9aa0a6;
    }

    body.dark-mode .text-muted {
        color: #adb5bd !important;
    }
</style>

@endsection

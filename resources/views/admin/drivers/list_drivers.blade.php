@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-primary"><i class="fas fa-users me-2"></i><span data-key="driver_list">Driver List</span></h2>
        <a href="{{ route('drivers.create') }}" 
           class="btn btn-success btn-lg text-white shadow add-driver-btn mt-2 mt-md-0"
           style="text-decoration: none;">
            <i class="fas fa-plus-circle me-2"></i> <span data-key="add_new_driver">Add New Driver</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-4 filter-container">
        <div class="card-body">
            <form method="GET" action="{{ route('drivers.index') }}">
                <div class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold" data-key="first_name">First Name</label>
                        <input type="text"
                            name="name"
                            value="{{ request('name') }}"
                            class="form-control"
                            placeholder="John">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold" data-key="last_name">Last Name</label>
                        <input type="text"
                            name="lastname"
                            value="{{ request('lastname') }}"
                            class="form-control"
                            placeholder="Doe">
                    </div>

                     {{-- License Number --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold" data-key="driver_license">License</label>
                        <input type="text"
                            name="license_number"
                            value="{{ request('license_number') }}"
                            class="form-control"
                            placeholder="DL123456">
                    </div>

                     <div class="col-md-2">
                        <label class="form-label fw-semibold" data-key="status">Status</label>
                        <select name="status" class="form-select">
                            <option value="" data-key="all">All</option>
                            <option value="ON" {{ request('status') == 'ON' ? 'selected' : '' }} data-key="">ON Duty</option>
                            <option value="D" {{ request('status') == 'D' ? 'selected' : '' }} data-key="">Drive</option>
                            <option value="OFF" {{ request('status') == 'OFF' ? 'selected' : '' }} data-key="">OFF Duty</option>
                            <option value="PC" {{ request('status') == 'PC' ? 'selected' : '' }} data-key="">Personal Conveyance</option>
                            <option value="WT" {{ request('status') == 'WT' ? 'selected' : '' }} data-key="">Waiting Time</option>
                            <option value="SB" {{ request('status') == 'SB' ? 'selected' : '' }} data-key="">Sleeper Berth</option>
                            <option value="YM" {{ request('status') == 'YM' ? 'selected' : '' }} data-key="">Yard Move</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> <span data-key="filter">Filter</span>
                        </button>

                        <a href="{{ route('drivers.index') }}"
                        class="btn btn-outline-secondary w-100">
                            <i class="fas fa-eraser me-1"></i> <span data-key="clear">Clear</span>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @if($drivers->count() > 0)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0 driver-table">
                        <thead class="table-header">
                            <tr>
                                <th>ID</th>
                                <th data-key="first_name">First Name</th>
                                <th data-key="last_name">Last Name</th>
                                <th data-key="phone">Phone</th>
                                <th data-key="email">Email</th>
                                <th data-key="license">License</th>
                                <th data-key="status">Status</th>
                                <th class="text-center" data-key="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($drivers as $driver)
                                <tr>
                                    <td>{{ $driver->id }}</td>
                                    <td>{{ $driver->name }}</td>
                                    <td>{{ $driver->lastname }}</td>
                                    <td>{{ $driver->phone_number ?? '-' }}</td>
                                    <td>{{ $driver->email ?? '-' }}</td>
                                    <td>{{ $driver->license_number ?? '-' }}</td>
                                    <td>
                                        @if($driver->status === 'active')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $driver->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="{{ route('admin.drivers.documents', $driver->id) }}" 
                                            class="btn btn-info btn-sm action-btn text-white" style="text-decoration: none;">
                                                <i class="fas fa-file-alt me-1"></i> <span data-key="view_documents">View Documents</span>
                                            </a>
                                            <a href="{{ route('drivers.edit', $driver->id) }}" 
                                               class="btn btn-primary btn-sm action-btn" style="text-decoration: none;">
                                                <i class="fas fa-edit me-1"></i> <span data-key="edit"> Edit</span>
                                            </a>
                                            <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST" class="delete-form" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm action-btn delete-btn"
                                                        data-driver-name="{{ $driver->name }}">
                                                    <i class="fas fa-trash-alt me-1"></i> <span data-key="delete">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center mt-4 rounded">
            <i class="fas fa-info-circle me-1"></i> No drivers registered.
        </div>
    @endif
</div>

<style>
/* --- Tabla y botones: igual que tu estilo anterior --- */
/* Agrega tus estilos aquí */
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Delegación de eventos para eliminar múltiples registros sin recargar
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const button = e.target.closest('.delete-btn');
            const form = button.closest('.delete-form');
            const driverName = button.dataset.driverName;

            Swal.fire({
                title: `Delete ${driverName}?`,
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
});
</script>
<style>
    .dark-mode .filter-container {
        background-color: #1f2937;
        border: 1px solid #374151;
    }

    .dark-mode .filter-container .form-label {
        color: #d1d5db;
    }

    .dark-mode .filter-container .form-control,
    .dark-mode .filter-container .form-select {
        background-color: #111827;
        color: #e5e7eb;
        border: 1px solid #374151;
    }

    .dark-mode .filter-container .form-control::placeholder {
        color: #9ca3af;
    }

    .dark-mode .filter-container .form-control:focus,
    .dark-mode .filter-container .form-select:focus {
        background-color: #111827;
        color: #e5e7eb;
        border-color: #6366f1;
        box-shadow: 0 0 0 0.15rem rgba(99,102,241,.25);
    }

    /* Botones */
    .dark-mode .btn-outline-secondary {
        color: #e5e7eb;
        border-color: #6b7280;
    }

    .dark-mode .btn-outline-secondary:hover {
        background-color: #374151;
    }

    /* =========================
    TABLA
    ========================= */

    .dark-mode .card {
        background-color: #1f2937;
    }

    .dark-mode table {
        color: #e5e7eb;
    }

    .dark-mode .table-bordered th,
    .dark-mode .table-bordered td {
        border-color: #374151;
    }

    .dark-mode .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #111827;
    }

    .dark-mode .table-striped > tbody > tr:nth-of-type(even) {
        background-color: #1f2937;
    }

    .dark-mode .table tbody tr:hover {
        background-color: #374151;
    }

    /* Header tabla */
    .dark-mode .table-header th {
        background: linear-gradient(90deg, #4338ca, #6366f1);
        color: #ffffff;
    }

    /* =========================
    BADGES
    ========================= */

    .dark-mode .badge.bg-success {
        background-color: #16a34a !important;
    }

    .dark-mode .badge.bg-secondary {
        background-color: #6b7280 !important;
    }

    /* =========================
    ALERTS
    ========================= */

    .dark-mode .alert-success {
        background-color: #064e3b;
        color: #ecfdf5;
        border-color: #065f46;
    }

    .dark-mode .alert-info {
        background-color: #1e3a8a;
        color: #eff6ff;
        border-color: #1d4ed8;
    }

    /* =========================
    TEXTOS
    ========================= */

    .dark-mode h2,
    .dark-mode .text-primary {
        color: #c7d2fe !important;
    }
</style>
@endsection

@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-primary" ><i class="fas fa-truck-pickup me-2"></i><span data-key="trailer_list">Trailer List</span></h2>
        <a href="{{ route('trailers.create') }}" 
           class="btn btn-success btn-lg text-white shadow add-trailer-btn mt-2 mt-md-0"
           style="text-decoration: none;">
           <i class="fas fa-plus-circle me-2"></i> <span data-key="add_new_trailer">Add New Trailer</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-4 filter-container">
        <div class="card-body">
            <form method="GET" action="{{ route('trailers.index') }}">
                <div class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">License Plate</label>
                        <input type="text"
                               name="license_plate"
                               value="{{ request('license_plate') }}"
                               class="form-control"
                               placeholder="ABC-123">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold" data-key="type">Type</label>
                        <select name="trailer_type" class="form-select">
                            <option value="" data-key="all_types">All types</option>

                            <option value="drybox" {{ request('trailer_type') == 'drybox' ? 'selected' : '' }}>
                                Dry box
                            </option>

                            <option value="cistern" {{ request('trailer_type') == 'cistern' ? 'selected' : '' }}>
                                Cistern
                            </option>

                            <option value="platform" {{ request('trailer_type') == 'platform' ? 'selected' : '' }}>
                                Platform
                            </option>

                            <option value="pneumatic" {{ request('trailer_type') == 'pneumatic' ? 'selected' : '' }}>
                                Pneumatic
                            </option>

                            <option value="other" {{ request('trailer_type') == 'other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Axles</label>
                        <input type="number"
                               name="axles"
                               value="{{ request('axles') }}"
                               class="form-control"
                               placeholder="2, 3">
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-50">
                            <i class="fas fa-filter me-1"></i> <span data-key="filter">Filter</span>
                        </button>

                        <a href="{{ route('trailers.index') }}"
                           class="btn btn-outline-secondary w-50">
                            <i class="fas fa-eraser me-1"></i> <span data-key="clear">Clear</span>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @if($trailers->count() > 0)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0 trailer-table">
                        <thead class="table-header">
                            <tr>
                                <th>ID</th>
                                <th data-key="axles">Axles</th>
                                <th data-key="type">Type</th>
                                <th data-key="license_plate">License Plate</th>
                                <th class="text-center" data-key="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trailers as $trailer)
                            <tr>
                                <td>{{ $trailer->id }}</td>
                                <td>{{ $trailer->axles }}</td>
                                <td>{{ $trailer->trailer_type }}</td>
                                <td>{{ $trailer->license_plate }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('trailers.edit', $trailer->id) }}" 
                                           class="btn btn-primary btn-sm action-btn">
                                           <i class="fas fa-edit me-1"></i> <span data-key="edit">Edit</span>
                                        </a>
                                        <form action="{{ route('trailers.destroy', $trailer->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-trailer="{{ $trailer->trailer_type }}">
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
            <i class="fas fa-info-circle me-1"></i> No trailers registered.
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const trailerType = this.dataset.trailer;

                Swal.fire({
                    title: `Delete ${trailerType}?`,
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
            });
        });
    });
    Swal.fire({
        title: '¡Eliminado!',
        text: data.message, // <-- aquí muestra "Registro eliminado correctamente"
        icon: 'success',
        confirmButtonText: 'OK'
    });

</script>

<style>
    /* Tabla y botones estilo moderno similar a Trucks/Drivers */
    .trailer-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .trailer-table th, .trailer-table td {
        border: 1px solid #dee2e6;
        vertical-align: middle;
        text-align: center;
        transition: background-color 0.3s;
    }
    .trailer-table tbody tr:nth-of-type(odd) { background-color: #f8f9fa; }
    .trailer-table tbody tr:hover { background-color: #dbe4ff; }
    .table-header th { background: linear-gradient(90deg,#4e54c8,#8f94fb); color:white; font-weight:600; }

    .add-trailer-btn, .action-btn { border-radius: 8px; padding: 6px 12px; }
    .add-trailer-btn:hover, .action-btn:hover { transform: translateY(-2px); transition: transform 0.2s; }
    .btn-primary { background-color:#007bff; }
    .btn-danger { background-color:#dc3545; }

    body.dark-mode .card {
        background-color: #1e1e2f;
        border: 1px solid #2c2c3a;
    }

    body.dark-mode .trailer-table {
        background-color: #1e1e2f;
    }

    body.dark-mode .trailer-table th,
    body.dark-mode .trailer-table td {
        background-color: #1e1e2f;
        color: #e9ecef;
        border-color: #343a40;
    }

    body.dark-mode .filter-container {
        background-color: #1c1f26;
        box-shadow: 0 0 15px rgba(0, 128, 255, 0.15);
    }

    body.dark-mode .filter-container .form-label {
        color: #e9ecef;
    }

    body.dark-mode .filter-container .form-control {
        background-color: #0d1117;
        color: #e9ecef;
        border-color: #30363d;
    }

    body.dark-mode .filter-container .form-control::placeholder {
        color: #8b949e;
    }

    body.dark-mode .trailer-table th,
    body.dark-mode .trailer-table td {
        background-color: #1c1f26;
        color: #e9ecef;
        border-color: #30363d;
    }

    body.dark-mode .trailer-table tbody tr:nth-of-type(odd) {
        background-color: #161b22;
    }

    body.dark-mode .trailer-table tbody tr:hover {
        background-color: #0d1621;
    }

    body.dark-mode .table-header th {
        background: linear-gradient(90deg, #3b3f99, #5c60d6);
    }

    /* =========================
    ALERTAS DARK MODE
    ========================= */
    body.dark-mode .alert-info {
        background-color: #0d1621;
        color: #cfe2ff;
        border-color: #084298;
    }
    .filter-container .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        height: calc(2.25rem + 2px);
        font-size: 1rem;
        background-color: #ffffff;
    }

    /* Quitar flecha fea por defecto (opcional, más limpio) */
    .filter-container .form-select {
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    /* =========================
    DARK MODE SELECT
    ========================= */
    body.dark-mode .filter-container .form-select {
        background-color: #0d1117;
        color: #e9ecef;
        border-color: #30363d;
    }

    body.dark-mode .filter-container .form-select option {
        background-color: #0d1117;
        color: #e9ecef;
    }

</style>

@endsection

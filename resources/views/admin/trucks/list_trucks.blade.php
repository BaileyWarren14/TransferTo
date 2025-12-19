@extends('layouts.app')

@section('content')
<style>
    /* Tabla similar a drivers */
    .truck-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .truck-table th, .truck-table td {
        border: 1px solid #dee2e6;
        vertical-align: middle;
        text-align: center;
    }
    .truck-table tbody tr:nth-of-type(odd) { background-color: #f8f9fa; }
    .truck-table tbody tr:hover { background-color: #dbe4ff; }
    .table-header th {
        background: linear-gradient(90deg, #4e54c8, #8f94fb);
        color: white;
    }
    .add-truck-btn { border-radius: 10px; padding: 10px 20px; }
    .action-btn { border-radius: 8px; padding: 6px 12px; min-width: 70px; }

    body.dark-mode .card {
        background-color: #1c1f26;
        border: 1px solid #2c313a;
    }

    body.dark-mode .card label {
        color: #e9ecef;
        font-weight: 500;
    }

    /* Inputs & Selects */
    body.dark-mode .form-control,
    body.dark-mode .form-select {
        background-color: #0d1117;
        color: #e9ecef;
        border-color: #2c313a;
    }

    body.dark-mode .form-control::placeholder {
        color: #9aa0a6;
    }

    /* Focus */
    body.dark-mode .form-control:focus,
    body.dark-mode .form-select:focus {
        background-color: #0d1117;
        color: #ffffff;
        border-color: #4e54c8;
        box-shadow: 0 0 0 0.2rem rgba(78, 84, 200, 0.25);
    }

    /* Buttons */
    body.dark-mode .btn-primary {
        background-color: #4e54c8;
        border-color: #4e54c8;
    }

    body.dark-mode .btn-primary:hover {
        background-color: #5f65e0;
        border-color: #5f65e0;
    }

    body.dark-mode .btn-outline-secondary {
        color: #adb5bd;
        border-color: #adb5bd;
    }

    body.dark-mode .btn-outline-secondary:hover {
        background-color: #adb5bd;
        color: #0d1117;
    }

    /* Disabled (si algún día lo usas) */
    body.dark-mode .form-control:disabled {
        background-color: #161b22;
        color: #6c757d;
    }
</style>



<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-primary"><i class="fas fa-truck me-2"></i><span data-key="tuck_list">Truck List</span></h2>
        <a href="{{ route('trucks.create') }}" 
           class="btn btn-success btn-lg text-white shadow add-truck-btn mt-2 mt-md-0"
           style="text-decoration: none;">
            <i class="fas fa-plus-circle me-2"></i> <span data-key="add_new_truck">Add New Truck</span>
        </a>
    </div>
    <br>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET"
      action="{{ route('trucks.list_trucks') }}"
      class="card p-3 mb-4 shadow-sm">

        <div class="row g-3 align-items-end">

            <!-- License plate -->
            <div class="col-md-2">
                <label class="form-label fw-semibold" data-key="license_plate">License Plate</label>
                <input type="text"
                    name="license_plate"
                    value="{{ request('license_plate') }}"
                    class="form-control"
                    placeholder="ABC-123">
            </div>

            <!-- Brand -->
            <div class="col-md-2">
                <label class="form-label fw-semibold" data-key="brand">Brand</label>
                <input type="text"
                    name="brand"
                    value="{{ request('brand') }}"
                    class="form-control">
            </div>

            <!-- Driver -->
            <div class="col-md-2">
                <label class="form-label fw-semibold" data-key="driver">Driver</label>
                <input type="text"
                    name="driver"
                    value="{{ request('driver') }}"
                    class="form-control"
                    placeholder="Driver name" data-key="driver_names">
            </div>

            <!-- Status -->
            <div class="col-md-2">
                <label class="form-label fw-semibold" data-key="status">Status</label>
                <select name="status" class="form-select">
                    <option value="" data-key="all">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} data-key="active">Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }} data-key="inactive">Inactive</option>
                </select>
            </div>

            <!-- Year -->
            <div class="col-md-2">
                <label class="form-label fw-semibold" data-key="year">Year</label>
                <input type="number"
                    name="year"
                    value="{{ request('year') }}"
                    class="form-control">
            </div>

            <!-- Buttons -->
            <div class="col-md-2 d-flex gap-2 flex-column flex-md-row">

                <button class="btn btn-primary w-50">
                    <i class="fas fa-filter me-1"></i> <span data-key="filter">Filter</span>
                </button>
                <a href="{{ route('trucks.list_trucks') }}"
                class="btn btn-outline-secondary w-50">
                      <i class="fas fa-eraser me-1"></i> <span data-key="clear">Clear</span>
                </a>
            </div>

        </div>
    </form>


    @if($trucks->count() > 0)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0 truck-table">
                        <thead class="table-header">
                            <tr>
                                <th data-key="id">ID</th>
                                <th data-key="license_plate">License Plate</th>
                                <th data-key="brand">Brand</th>
                                <th data-key="model">Model</th>
                                <th data-key="year">Year</th>
                                <th data-key="color">Color</th>
                                
                                <th data-key="driver">Driver</th>
                                <th data-key="motor_hours">Motor Hours</th>
                                <th data-key="status">Status</th>
                                <th class="text-center" data-key="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trucks as $truck)
                                <tr>
                                    <td>{{ $truck->id }}</td>
                                    <td>{{ $truck->license_plate }}</td>
                                    <td>{{ $truck->brand }}</td>
                                    <td>{{ $truck->model }}</td>
                                    <td>{{ $truck->year }}</td>
                                    <td>{{ $truck->color }}</td>
                                    <td>{{ $truck->driver ? $truck->driver->name : 'Unassigned' }}</td>
                                    <td>{{ $truck->current_motor_hours }}</td>
                                    <td>
                                        @if($truck->status === 'active')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $truck->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="{{ route('trucks.edit', $truck->id) }}" 
                                               class="btn btn-primary btn-sm action-btn">
                                                <i class="fas fa-edit me-1"></i> <span data-key="edit">Edit</span>
                                            </a>
                                            <form action="{{ route('trucks.destroy', $truck->id) }}" method="POST" class="delete-form" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm action-btn delete-btn"
                                                        data-truck="{{ $truck->license_plate }}">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete
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
            <i class="fas fa-info-circle me-1"></i> <span data-key="no_truck_registered">No trucks registered.</span>
        </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Evita envío inmediato
            const form = this.closest('form');
            const truckName = this.dataset.truck;

            Swal.fire({
                title: `Delete ${truckName}?`,
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit(); // Envío tradicional → destroy()
                }
            });
        });
    });
});


</script>

@endsection

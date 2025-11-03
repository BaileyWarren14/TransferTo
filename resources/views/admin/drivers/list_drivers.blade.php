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

@endsection

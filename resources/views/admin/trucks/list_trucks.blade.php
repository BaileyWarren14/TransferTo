@extends('layouts.app_admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-primary"><i class="fas fa-truck me-2"></i>Truck List</h2>
        <a href="{{ route('trucks.create') }}" 
           class="btn btn-success btn-lg text-white shadow add-truck-btn mt-2 mt-md-0"
           style="text-decoration: none;">
            <i class="fas fa-plus-circle me-2"></i> Add New Truck
        </a>
    </div>
    <br>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($trucks->count() > 0)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0 truck-table">
                        <thead class="table-header">
                            <tr>
                                <th>ID</th>
                                <th>License Plate</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Color</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
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
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('trucks.destroy', $truck->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm action-btn delete-btn"
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
            <i class="fas fa-info-circle me-1"></i> No trucks registered.
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            const truckName = this.dataset.truck;

            Swal.fire({
                title: `Delete ${truckName}?`,
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });
});
</script>

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
</style>
@endsection

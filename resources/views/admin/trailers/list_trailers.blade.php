@extends('layouts.app_admin')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-primary"><i class="fas fa-truck-pickup me-2"></i>Trailer List</h2>
        <a href="{{ route('trailers.create') }}" 
           class="btn btn-success btn-lg text-white shadow add-trailer-btn mt-2 mt-md-0"
           style="text-decoration: none;">
           <i class="fas fa-plus-circle me-2"></i> Add New Trailer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($trailers->count() > 0)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0 trailer-table">
                        <thead class="table-header">
                            <tr>
                                <th>ID</th>
                                <th>Axles</th>
                                <th>Type</th>
                                <th>License Plate</th>
                                <th class="text-center">Actions</th>
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
                                           <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('trailers.destroy', $trailer->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-trailer="{{ $trailer->trailer_type }}">
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
</style>

@endsection

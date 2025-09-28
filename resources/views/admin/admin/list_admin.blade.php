@extends('layouts.app_admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-primary"><i class="fas fa-user-shield me-2"></i>Admin List</h2>
        <a href="{{ route('admin.create') }}" 
           class="btn btn-success btn-lg text-white shadow add-admin-btn mt-2 mt-md-0"
           style="text-decoration: none;">
           <i class="fas fa-plus-circle me-2"></i> Add New Admin
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($admins->count() > 0)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0 admin-table">
                        <thead class="table-header">
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->lastname }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->phone_number ?? '-' }}</td>
                                    <td>{{ $admin->department ?? '-' }}</td>
                                    <td>{{ $admin->position }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="{{ route('admin.edit', $admin->id) }}" 
                                               class="btn btn-primary btn-sm action-btn">
                                               <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm action-btn delete-btn"
                                                        data-admin-name="{{ $admin->name }}"
                                                        @if($admin->id === auth()->guard('admin')->id()) disabled @endif>
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
            <i class="fas fa-info-circle me-1"></i> No admins registered.
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            const adminName = this.dataset.adminName;

            Swal.fire({
                title: `Delete ${adminName}?`,
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
</script>

<style>
/* --- Tabla --- */
.admin-table th, .admin-table td {
    text-align: center;
    vertical-align: middle;
}
.table-header th {
    background: linear-gradient(90deg, #4e54c8, #8f94fb);
    color: white;
    font-weight: 600;
}
.action-btn { border-radius: 8px; padding: 6px 12px; }
.btn-primary:hover { background-color: #0056b3; }
.btn-success:hover { background-color: #218838; }
.btn-danger:hover { background-color: #c82333; }

/* --- Responsivo --- */
@media (max-width: 768px) {
    .add-admin-btn { width: 100%; text-align: center; }
}
</style>
@endsection

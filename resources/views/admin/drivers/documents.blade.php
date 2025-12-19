@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">
        <i class="fas fa-file-alt me-2"></i> 
        <span data-key="documents_of">Documents of</span> {{ $driver->name }} {{ $driver->lastname }}
    </h2>

    <a href="{{ route('drivers.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> <span data-key="back_to_drivers">Back to Drivers</span>
    </a>
    
    <!-- <a href="{{ route('documents.create', $driver->id) }}" 
        class="btn btn-success mb-3">
        <i class="fas fa-plus me-1"></i> Add Document
    </a> -->

    @if(count($documents) > 0 || count($fuelBOLs) > 0)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-header">
                            <tr>
                                <th>#</th>
                                <th data-key="document_name">Document Name</th>
                                <th data-key="type">Type</th>
                                <th data-key="date_uploaded">Date Uploaded</th>
                                <th data-key="actions">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp

                            {{-- Documentos normales --}}
                            @foreach($documents as $doc)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $doc->name ?? 'Unnamed' }}</td>
                                    <td>{{ $doc->type ?? 'Unknown' }}</td>
                                    <td>{{ $doc->created_at ? $doc->created_at->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <button 
                                            class="btn btn-sm btn-primary btn-view-file"
                                            data-type="document"
                                            data-id="{{ $doc->id }}"
                                        >
                                            <i class="fas fa-eye me-1"></i> 
                                            <span data-key="view">View</span>
                                        </button>

                                        <a href="{{ route('documents.downloads', $doc->id) }}" 
                                        class="btn btn-sm btn-success btn-download">
                                            <i class="fas fa-download me-1"></i> 
                                            <span data-key="download">Download</span>
                                        </a>

                                        <!-- Eliminar -->
                                        <form action="{{ route('documents.deletes', $doc->id) }}" 
                                            method="POST" 
                                            class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i> 
                                                <span data-key="delete">Delete</span>
                                            </button>
                                        </form>



                                    </td>
                                </tr>
                            @endforeach

                            {{-- BOLs de Fuel --}}
                            @foreach($fuelBOLs as $fuel)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>BOL - {{ $fuel->bol_number ?? 'N/A' }}</td>
                                    <td>Fuel BOL</td>
                                    <td>{{ $fuel->created_at ? $fuel->created_at->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        @if($fuel->bol_path)
                                            <button 
                                                class="btn btn-sm btn-primary btn-view-file"
                                                data-type="bol"
                                                data-id="{{ $fuel->id }}"
                                            >
                                                <i class="fas fa-eye me-1"></i> 
                                                <span data-key="view">View</span>
                                            </button>

                                            <!-- Descargar -->
                                            <a href="{{ route('fuelbol.download', $fuel->id) }}" 
                                            class="btn btn-sm btn-success btn-download">
                                                <i class="fas fa-download me-1"></i> 
                                                <span data-key="download">Download</span>
                                            </a>


                                            <!-- Eliminar -->
                                            <form action="{{ route('fuelbol.delete', $fuel->id) }}" 
                                                method="POST" 
                                                class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt me-1"></i> 
                                                    <span data-key="delete">Delete</span>
                                                </button>
                                            </form>





                                        @else
                                            <span class="text-muted" data-key="no_file_uploaded">No file uploaded</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i> 
            <span data-key="no_documents_uploaded_for_this_driver">No documents uploaded for this driver.</span>
        </div>
    @endif
</div>

<!-- Modal de visualización -->
<div class="modal fade" id="viewFileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
       <div class="modal-content dark-modal">
            <div class="modal-header">
                <h5 class="modal-title" data-key="document_viewer">Document Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="fileViewer" 
                    class="d-flex justify-content-center align-items-center"
                    style="height: 75vh;">
                    <p class="text-center text-muted">Loading...</p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* ---- MODO OSCURO PARA EL MODAL (SIN ROMPER NADA) ---- */

    .modal-content {
        background-color: #1a1a1a !important;
        color: #f0f0f0 !important;
        border: 1px solid #333 !important;
    }

    .modal-header,
    .modal-footer {
        border-color: #333 !important;
        background-color: #222 !important;
    }

    .modal-title {
        color: #fff !important;
    }

    /* Botón cerrar */
    .btn-close {
        filter: invert(1);
    }

    /* Texto dentro del modal */
    .modal-body p,
    .modal-body span,
    .modal-body td,
    .modal-body th {
        color: #e0e0e0 !important;
    }

    /* Fondo del visor */
    #fileViewer iframe {
        background-color: #1a1a1a;
    }

    #fileViewer {
        background-color: #1a1a1a;
    }

    /* Modo oscuro del modal */
    .dark-modal {
        background-color: #1a1a1a !important;
        color: #f0f0f0 !important;
        border: 1px solid #333 !important;
    }

    /* Header y footer */
    .dark-modal .modal-header,
    .dark-modal .modal-footer {
        background-color: #222 !important;
        border-color: #333 !important;
    }

    /* Texto */
    .dark-modal .modal-title,
    .dark-modal p,
    .dark-modal span {
        color: #fff !important;
    }

    /* Botón cerrar */
    .dark-modal .btn-close {
        filter: invert(1);
    }


</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = new bootstrap.Modal(document.getElementById('viewFileModal'));
    const viewer = document.getElementById('fileViewer');

    document.querySelectorAll('.btn-view-file').forEach(button => {

        button.addEventListener('click', function () {

            const type = this.dataset.type;
            const id = this.dataset.id;

            // Construir URL
            const url = `/admin/view-file/${type}/${id}`;

            // Mostrar loading
            viewer.innerHTML = `<p class="text-center text-muted">Loading...</p>`;

            // Mostrar modal
            modal.show();

            // Cargar documento dentro del modal
            viewer.innerHTML = `
                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                    <iframe 
                        src="${url}" 
                        style="width: 80%; height: 80%; border: none; border-radius: 10px;"
                    ></iframe>
                </div>
            `;

        });

    });

});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
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
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-download').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const url = this.getAttribute('href');

            Swal.fire({
                title: 'Download file?',
                text: 'Do you want to download this file?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, download',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

});
</script>
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

@endsection

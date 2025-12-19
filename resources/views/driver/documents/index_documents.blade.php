@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0 main-card transition-colors p-4">

        <!-- Título principal -->
        <h2 class="fw-bold mb-4" data-key="documents">Documents</h2>

        {{-- Upload Form --}}
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="mb-3">
                <label class="form-label" data-key="document_type">Document Type</label>
                <select name="type" class="form-select" required>
                    <option value="" data-key="select">Select...</option>
                    <option value="Driver License" data-key="driver_license">Driver License</option>
                   
                    <option value="Insurance" data-key="insuranse">Insurance</option>
                    <option value="Plates (Registration)" data-key="plates">Plates (Registration)</option>
                    <option value="Other" data-key="other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" data-key="file">File</label>
                <input type="file" name="file" class="form-control" required accept="image/*" capture="environment">
            </div>

            <button type="submit" class="btn btn-primary" data-key="upload_document">Upload Document</button>
        </form>

        {{-- Documents List --}}
        @if($documents->count() > 0)
        <div class="table-responsive">
            <table class="table table-borderless align-middle document-table">
                <thead>
                    <tr>
                        <th data-key="type">Type</th>
                        <th data-key="file_name">File Name</th>
                        <th style="width: 200px;" data-key="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                        <tr class="document-row">
                            <td class="document-cell">{{ $doc->type }}</td>
                            <td class="document-cell">{{ $doc->file_name }}</td>
                            <td class="document-cell">
                                <button 
                                    class="btn btn-sm btn-info document-btn view-doc-btn" 
                                    data-id="{{ $doc->id }}"
                                >
                                    <span data-key="view">View</span>
                                </button>


                                <a href="{{ route('documents.download', $doc->id) }}" 
                                class="btn btn-sm btn-success document-btn">
                                <span data-key="download">Download</span>
                                </a>

                                <button class="btn btn-sm btn-danger delete-btn document-btn" data-id="{{ $doc->id }}">
                                    <span data-key="delete">Delete</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>


            </table>
        </div>
        @else
            <p class="fst-italic text-muted">No documents uploaded yet.</p>
        @endif

    </div>
</div>

<!-- Modal para mostrar la imagen -->
<div class="modal fade" id="documentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-dark text-white">

      <div class="modal-header">
        <h5 class="modal-title">Document Preview</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex justify-content-center align-items-center" style="min-height: 60vh;">
        <img id="documentImage" 
             src="" 
             alt="Document" 
             class="img-fluid rounded shadow" 
             style="max-height: 80vh; object-fit: contain;">
      </div>

    </div>
  </div>
</div>



<style>
    /* ===== Contenedor principal ===== */
    .main-card {
        background-color: #ffffff;
        color: #212529;
        transition: background-color 0.3s, color 0.3s;
    }

    body.dark-mode .main-card {
        background-color: #1c1f26 !important;
        color: #e9ecef !important;
    }

    /* ===== Formulario ===== */
    .form-label, .form-select, .form-control {
        transition: all 0.3s;
    }

    body.dark-mode .form-label,
    body.dark-mode .form-select,
    body.dark-mode .form-control {
        background-color: #262a33;
        color: #e9ecef;
        border-color: #3a3f50;
    }

    /* Tabla base */
    .document-table th, 
    .document-table td {
        transition: background-color 0.3s, color 0.3s;
    }

    /* Encabezado */
    .document-table thead {
        background-color: #f8f9fa;
        color: #212529;
    }

    body.dark-mode .document-table thead {
        background-color: #1c1f26;
        color: #e9ecef;
    }

    /* Filas */
    .document-row {
        background-color: #ffffff;
        color: #212529;
    }

    .document-row:hover {
        background-color: #e9ecef;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.15);
    }

    body.dark-mode .document-row {
        background-color: #262a33;
        color: #e9ecef;
    }

    body.dark-mode .document-row:hover {
        background-color: #0d1621;
        box-shadow: 0 0 15px rgba(0, 123, 255, 0.3);
    }

    /* Botones dentro de la tabla */
    .document-btn {
        transition: all 0.3s;
    }

    body.dark-mode .document-btn.btn-info {
        background-color: #138496;
        border-color: #138496;
        color: #fff;
    }

    body.dark-mode .document-btn.btn-success {
        background-color: #218838;
        border-color: #218838;
        color: #fff;
    }

    body.dark-mode .document-btn.btn-danger {
        background-color: #c82333;
        border-color: #c82333;
        color: #fff;
    }
    /* ===== Filas y celdas ===== */
    .document-row {
        background-color: #ffffff;
        color: #212529;
        transition: all 0.3s;
    }

    .document-row:hover {
        background-color: #e9ecef;
        box-shadow: 0 0 10px rgba(0,123,255,0.15);
    }

    /* Afecta a todo dentro de la fila */
    .document-row .document-cell {
        background-color: inherit;
        color: inherit;
        transition: inherit;
    }

    /* Botones */
    .document-btn {
        transition: all 0.3s;
    }

    body.dark-mode .document-row {
        background-color: #262a33 !important;
        color: #e9ecef !important;
    }

    body.dark-mode .document-row:hover {
        background-color: #0d1621 !important;
        box-shadow: 0 0 15px rgba(0,123,255,0.3);
    }

    body.dark-mode .document-row .document-cell {
        background-color: inherit;
        color: inherit;
    }

    body.dark-mode .document-btn.btn-info {
        background-color: #138496;
        border-color: #138496;
        color: #fff;
    }

    body.dark-mode .document-btn.btn-success {
        background-color: #218838;
        border-color: #218838;
        color: #fff;
    }

    body.dark-mode .document-btn.btn-danger {
        background-color: #c82333;
        border-color: #c82333;
        color: #fff;
    }
    /* Encabezado de la tabla */
    .document-table thead th {
        background-color: #f8f9fa;
        color: #212529;
        transition: background-color 0.3s, color 0.3s;
    }

    body.dark-mode .document-table thead th {
        background-color: #1c1f26 !important;
        color: #e9ecef !important;
    }

</style>

<script src="{{ asset('js/translations.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    // Validation errors
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: `<ul style="text-align:left;">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>`
        });
    @endif

    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(){
            const docId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the document.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.isConfirmed){
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/driver/documents/delete/${docId}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
<script>
document.querySelectorAll(".view-doc-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        const id = this.dataset.id;

        // Llamamos al endpoint (que actualmente abre el archivo)
        fetch(`/driver/documents/view/${id}`)
            .then(response => response.blob())
            .then(blob => {
                const url = URL.createObjectURL(blob);
                
                // Colocar la imagen dentro del modal
                document.getElementById("documentImage").src = url;

                // Abrir modal (Bootstrap 5)
                let modal = new bootstrap.Modal(document.getElementById('documentModal'));
                modal.show();
            });
    });
});
</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="card shadow p-4 dark-card">

        <h2 class="mb-3" data-key="documents">Documents</h2>

        {{-- Upload Form --}}
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="mb-3">
                <label class="form-label" data-key="document_type">Document Type</label>
                <select name="type" class="form-select" required>
                    <option value="" data-key="select">Select...</option>
                    <option value="Driver License" data-key="driver_license">Driver License</option>
                    <option value="Bill of Lading" data-key="bol">Bill of Lading</option>
                    <option value="Insurance" data-key="insuranse">Insurance</option>
                    <option value="Plates (Registration)" data-key="plates">Plates (Registration)</option>
                    <option value="Other" data-key="other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" data-key="file">File</label>
                <!-- Permite tomar foto en móviles -->
                <input type="file" name="file" data-key="file" class="form-control" required accept="image/*" capture="environment">
            </div>

            <button type="submit" class="btn btn-primary" data-key="upload_document">Upload Document</button>
        </form>

        {{-- Documents List --}}
        @if($documents->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th data-key="type">Type</th>
                        <th data-key="file_name">File Name</th>
                        <th style="width: 200px;" data-key="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                        <tr>
                            <td>{{ $doc->type }}</td>
                            <td>{{ $doc->file_name }}</td>
                            <td>
                                <a href="{{ route('documents.view', $doc->id) }}"
                                    class="btn btn-sm btn-info" target="_blank" data-key="view"> 
                                    View
                                </a>

                                <a href="{{ route('documents.download', $doc->id) }}" 
                                   class="btn btn-sm btn-success" data-key="download">
                                   Download
                                </a>

                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $doc->id }}" data-key="delete">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted">No documents uploaded yet.</p>
        @endif

    </div>
   
</div>
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
@endsection

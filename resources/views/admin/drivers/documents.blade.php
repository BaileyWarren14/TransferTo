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

    @if(count($documents) > 0)
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
                            @foreach($documents as $index => $doc)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $doc->name ?? 'Unnamed' }}</td>
                                    <td>{{ $doc->type ?? 'Unknown' }}</td>
                                    <td>{{ $doc->created_at ? $doc->created_at->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('documents.view', $doc->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i> <span data-key="view">View</span>
                                        </a>
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
            <i class="fas fa-info-circle me-2"></i> <span data-key="no_documents_uploaded_for_this_driver">No documents uploaded for this driver.</span>
        </div>
    @endif
</div>
@endsection

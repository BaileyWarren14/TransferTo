@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="fw-bold mb-4">
        <i class="fas fa-upload me-2"></i>
        Upload Document for {{ $driver->name }} {{ $driver->lastname }}
    </h3>

    <a href="{{ route('drivers.documents', $driver->id) }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

           <form id="documentForm" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Document Type</label>
                    <select name="type" id="docType" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="Driver License">Driver License</option>
                        <option value="Insurance">Insurance</option>
                        <option value="Plates (Registration)">Plates (Registration)</option>
                        <option value="BOL">Bill of Lading (BOL)</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">File</label>
                    <input type="file" name="file" id="fileInput" class="form-control" required accept="image/*,application/pdf">
                </div>

                <button type="submit" class="btn btn-primary">Upload Document</button>
            </form>


        </div>
    </div>

</div>


@endsection

@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- 🔹 Botones superiores -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <a href="{{ route('workorder.index') }}" class="btn btn-info px-4 py-2 rounded-pill w-auto">
            <span data-key="return_to_work_order">Return To Work Order</span>
        </a>
        <a href="{{ route('workorder.cistern.create') }}" class="btn btn-success px-4 py-2 rounded-pill w-auto">
            <span data-key="add_new_fuel_log">Add New Fuel Log</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- 🔹 Tabla -->
    <div class="card shadow p-3">
        <h4 class="mb-3" data-key="fuel_records">Fuel Records</h4>

        <div class="table-responsive">
            <table class="table table-striped table-dark align-middle text-center mb-0">
                <thead>
                    <tr>
                        <th data-key="date">Date</th>
                        <th data-key="bol">BOL</th>
                        <th data-key="trailer">Trailer</th>
                        <th data-key="from">From</th>
                        <th data-key="destination">Destination</th>
                        <th data-key="fuel_gallons">Fuel (Gal)</th>
                        <th data-key="total_miles">Total Miles</th>
                        <th data-key="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fuels as $fuel)
                        <tr>
                            <td>{{ $fuel->date }}</td>
                            <td>{{ $fuel->bol_number ?? '—' }}</td>
                            <td>{{ $fuel->trailer }}</td>
                            <td>{{ $fuel->from }}</td>
                            <td>{{ $fuel->destination }}</td>
                            <td>{{ $fuel->fuel_dispensed }}</td>
                            <td>{{ $fuel->total_miles }}</td>
                            <td>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <!-- Edit -->
                                    <a href="{{ route('workorder.cistern.edit', $fuel) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square me-1"></i>   
                                        <span data-key="edit">Edit</span>
                                    </a>

                                    <!-- Delete -->
                                    <form id="deleteForm{{ $fuel->id }}" action="{{ route('workorder.cistern.destroy', $fuel) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $fuel->id }}">
                                            <i class="bi bi-trash me-1"></i> 
                                            <span data-key="delete">Delete</span>
                                        </button>
                                    </form>

                                    <!-- Finish Trip -->
                                    <button class="btn btn-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#finishTripModal" 
                                        data-fuel-id="{{ $fuel->id }}"
                                        data-mileage-before="{{ $fuel->mileage_before }}"
                                        data-fuel-dispensed="{{ $fuel->fuel_dispensed }}"
                                        @if($fuel->mileage_after > 0 || $fuel->fuel_dispensed > 0) disabled @endif>
                                        <i class="bi bi-flag-checkered me-1"></i> 
                                        <span data-key="finish_trip">Finish Trip</span>
                                    </button>

                                    <!-- Upload BOL -->
                                    <button class="btn btn-secondary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#uploadBolModal" 
                                        data-fuel-id="{{ $fuel->id }}">
                                        <i class="bi bi-upload me-1"></i> 
                                        <span data-key="upload_bol">Upload BOL</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $fuels->links() }}
        </div>
    </div>
</div>

<!-- 🔹 MODALS -->
<!-- Upload BOL -->
<div class="modal fade" id="uploadBolModal" tabindex="-1" aria-labelledby="uploadBolModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">
            <i class="bi bi-upload me-2"></i> Upload Bill of Lading (BOL)
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="uploadBolForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <input type="hidden" id="bol_fuel_id" name="fuel_id">
            <div class="mb-3">
                <label for="bol_file" class="form-label fw-bold">Select file or take a picture</label>
                <input class="form-control" type="file" id="bol_file" name="bol_file" accept="image/*,.pdf" capture="environment" required>
                <small class="text-muted">Supported formats: JPG, PNG, PDF</small>
            </div>
        </div>

        <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save me-1"></i> Save BOL
            </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Finish Trip -->
<div class="modal fade" id="finishTripModal" tabindex="-1" aria-labelledby="finishTripModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">
            <i class="bi bi-flag-checkered me-2"></i> Finish Trip
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="finishTripForm" method="POST">
        @csrf
        <div class="modal-body">
            <input type="hidden" id="finish_fuel_id" name="fuel_id">
            <input type="hidden" id="mileage_before" name="mileage_before">

            <div class="mb-3">
                <label for="mileage_after" class="form-label">Mileage After</label>
                <input type="number" class="form-control" id="mileage_after" name="mileage_after" required>
            </div>

            <div class="mb-3">
                <label for="total_miles" class="form-label">Total Miles</label>
                <input type="number" class="form-control" id="total_miles" name="total_miles" readonly>
            </div>

            <div class="mb-3">
                <label for="fuel_dispensed" class="form-label">Fuel Dispensed (Gallons)</label>
                <input type="number" step="0.01" class="form-control" id="fuel_dispensed" name="fuel_dispensed" required>
            </div>

            <div class="mb-3">
                <label for="performance" class="form-label">Performance (Miles/Gallon)</label>
                <input type="number" step="0.01" class="form-control" id="performance" name="performance" readonly>
            </div>
        </div>

        <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i> Save Trip
            </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🔹 Script responsivo -->
<style>
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 0.75rem;
        padding: 0.4rem;
    }
    .btn-sm {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    .btn {
        white-space: nowrap;
    }
    .modal-dialog {
        margin: 0.5rem;
    }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const uploadBolModal = document.getElementById('uploadBolModal');
    const finishTripModal = document.getElementById('finishTripModal');

    // Modal Upload BOL
    uploadBolModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const fuelId = button.getAttribute('data-fuel-id');
        document.getElementById('bol_fuel_id').value = fuelId;
        document.getElementById('uploadBolForm').action = `/driver/fuel/uploadbol/${fuelId}`;
    });

    finishTripModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const fuelId = button.getAttribute('data-fuel-id');
        const mileageBefore = parseFloat(button.getAttribute('data-mileage-before'));

        document.getElementById('finish_fuel_id').value = fuelId;
        document.getElementById('mileage_before').value = mileageBefore;

        // Poner el action correcto
        document.getElementById('finishTripForm').action = `/driver/fuel/finish/${fuelId}`;

        // Limpiar campos
        document.getElementById('mileage_after').value = '';
        document.getElementById('total_miles').value = '';
        document.getElementById('fuel_dispensed').value = '';
        document.getElementById('performance').value = '';
    });


    // Calcular total y rendimiento
    document.getElementById('mileage_after').addEventListener('input', () => {
        const mileageBefore = parseFloat(document.getElementById('mileage_before').value);
        const mileageAfter = parseFloat(document.getElementById('mileage_after').value);
        const fuelUsed = parseFloat(document.getElementById('fuel_used').value) || 6.5;
        
        if (!isNaN(mileageBefore) && !isNaN(mileageAfter)) {
            const totalMiles = mileageAfter - mileageBefore;
            document.getElementById('total_miles').value = totalMiles > 0 ? totalMiles : 0;
            document.getElementById('performance').value = (totalMiles / fuelUsed).toFixed(2);
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mileageBefore = document.getElementById('mileage_before');
    const mileageAfter = document.getElementById('mileage_after');
    const totalMiles = document.getElementById('total_miles');
    const fuelDispensed = document.getElementById('fuel_dispensed');
    const performance = document.getElementById('performance');

    function calculateValues() {
        const before = parseFloat(mileageBefore.value) || 0;
        const after = parseFloat(mileageAfter.value) || 0;
        const fuel = parseFloat(fuelDispensed.value) || 0;

        // Calcular total de millas
        const total = after - before;
        totalMiles.value = total > 0 ? total.toFixed(2) : 0;

        // Calcular rendimiento si hay datos válidos
        if (fuel > 0 && total > 0) {
            const result = total / fuel;
            performance.value = result.toFixed(2);
        } else {
            performance.value = 0;
        }
    }

    mileageAfter.addEventListener('input', calculateValues);
    fuelDispensed.addEventListener('input', calculateValues);
});

document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fuelId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario correspondiente
                    document.getElementById(`deleteForm${fuelId}`).submit();
                }
            });
        });
    });
});
</script>


@if (session('success'))
<script>
    Swal.fire({
        title: 'Sucess!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        title: 'Error',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif




@endsection

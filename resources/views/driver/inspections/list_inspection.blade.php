@extends('layouts.app')

@section('content')
<style>
    /* Modo oscuro para tabla y contenedores */
body.dark-mode {
    background-color: #121212;
    color: #f0f0f0;
}

/* Apunta a toda la tabla y sus secciones */
body.dark-mode .table,
body.dark-mode .table thead,
body.dark-mode .table tbody,
body.dark-mode .table tr,
body.dark-mode .table th,
body.dark-mode .table td {
    background-color: #1e1e1e !important;
    color: #f0f0f0 !important;
    border-color: #333 !important;
}

</style>
<div class="d-flex justify-content-between align-items-center mb-3">
    
    
    <h2 style="text-align: center;">Last 15 Inspections</h2>
    <a href="{{ route('inspections.create') }}" class="btn btn-success">Add New Inspection</a>
</div>

@if($inspections->isEmpty())
    <p>No inspections recorded.</p>
@else
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Driver</th>
            <th>Truck Number</th>
            <th>Odometer</th>
            <th>Date</th>
            <th>Time</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inspections as $inspection)
        <tr>
            <td>{{ $inspection->id }}</td>
            <td>{{ $inspection->driver ? $inspection->driver->name : 'N/A' }}</td>
            <td>{{ $inspection->truck_number }}</td>
            <td>{{ $inspection->odometer }} {{ $inspection->unit }}</td>
            <td>{{ $inspection->inspection_date }}</td>
            <td>{{ $inspection->inspection_time }}</td>
            <td>
                <a href="{{ route('driver.inspections.edit_inspection', $inspection->id) }}" class="btn btn-sm btn-primary">Editar</a>
                <a href="{{ route('driver.inspections.pdf', $inspection->id) }}" class="btn btn-sm btn-success">Download PDF</a>
            </td>
            
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection

@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Last 15 Inspections</h2>
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
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection

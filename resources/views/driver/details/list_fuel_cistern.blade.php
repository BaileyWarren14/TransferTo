@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('workorder.index') }}" class="btn btn-info px-4 py-2 rounded-pill">
            <span data-key="return_to_work_order">Return To Work Order</span>
        </a>
        <a href="{{ route('workorder.cistern.create') }}" class="btn btn-success px-4 py-2 rounded-pill">
            <span data-key="add_new_fuel_log">Add New Fuel Log</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow p-3">
        <h4 class="mb-3">Fuel Records</h4>

        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>BOL</th>
                    <th>Trailer</th>
                    <th>From</th>
                    <th>Destination</th>
                    <th>Fuel (Gal)</th>
                    <th>Total Miles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fuels as $fuel)
                    <tr>
                        <td>{{ $fuel->date }}</td>
                        <td>{{ $fuel->bol_number }}</td>
                        <td>{{ $fuel->trailer }}</td>
                        <td>{{ $fuel->from }}</td>
                        <td>{{ $fuel->destination }}</td>
                        <td>{{ $fuel->fuel_dispensed }}</td>
                        <td>{{ $fuel->total_miles }}</td>
                        <td>
                            <a href="{{ route('workorder.cistern.edit', $fuel) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('workorder.cistern.destroy', $fuel) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $fuels->links() }}
    </div>
</div>
@endsection

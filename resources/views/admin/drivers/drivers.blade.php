@extends('layouts.sidebar_admin') <!-- Tu layout de administrador -->



@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Drivers</h2>
        <a href="{{ route('drivers.new_driver') }}" class="btn btn-success">Agregar Nuevo Driver</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Lastname</th>
                <th>Social Security Number</th>
                <th>Driver License</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($drivers as $driver)
                <tr>
                    <td>{{ $driver->id }}</td>
                    <td>{{ $driver->name }}</td>
                    <td>{{ $driver->lastname }}</td>
                    <td>{{ $driver->social_security_number ?? '-' }}</td>
                    <td>{{ $driver->driver_license ?? '-' }}</td>
                    <td>
                        <a href="{{ route('drivers.edit_driver', $driver->id) }}" class="btn btn-primary btn-sm">Editar</a>

                        <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de eliminar este driver?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay drivers registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

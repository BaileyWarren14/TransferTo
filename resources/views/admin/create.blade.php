@extends('layouts.app')

@section('title', 'Crear Administrador')

@section('content')
<div class="container mt-5">
    <h2>Crear Administrador</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="lastname" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">Departamento</label>
            <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}">
        </div>

        <div class="mb-3">
            <label for="position" class="form-label">Puesto</label>
            <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear Administrador</button>
    </form>
</div>
@endsection

@extends('layouts.app')
<link href="{{ asset('css/menu.css') }}" rel="stylesheet">
@section('content')
@include('layouts.sidebar')
    <div class="menu">
        <h1>Menú Principal</h1>
        <button onclick="location.href='{{ url('/new') }}'">New</button>
        <button onclick="location.href='{{ url('/details') }}'">Details</button>
    </div>
    @if(session('success'))
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: '¡Éxito!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'Aceptar'
    });
</script>
@endif
@endsection
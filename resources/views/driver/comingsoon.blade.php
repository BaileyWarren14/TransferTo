@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center text-center vh-100 bg-dark text-light">
    <div>
        <i class="bi bi-hourglass-split display-1 text-primary mb-3"></i>
        <h1 class="fw-bold" data-key="coming_title">Coming Soon</h1>
        <p class="lead text-info mb-4" data-key="coming_message">
            We're working hard to bring you something amazing. Stay tuned!
        </p>
          <div class="d-flex gap-2 justify-content-center">
            
            <button class="btn btn-secondary" onclick="goBack()" data-key="back">Back</button>
        </div>
    </div>
</div>
<script>
    // ====== Botón Regresar ======
    function goBack() {
        window.history.back();
    }
</script>

@endsection
@extends('layouts.app')

@section('content')

<style>
.card-body {
    display: flex;
    flex-direction: column; /* Coloca imagen y texto en columna */
    justify-content: center; /* Centra verticalmente */
    align-items: center;     /* Centra horizontalmente */
    height: 100%;            /* Ocupa todo el alto disponible de la card */
}


/* ===================== Hover Cards ===================== */
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

/* ===================== Modo Claro ===================== */
.card-light-dark {
    background-color: #fff;
    border-color: #e0e0e0;
}
.card-text-dark {
    color: #212529;
}
.text-muted {
    color: #6c757d !important;
}

/* ===================== Modo Oscuro ===================== */
body.dark-mode {
    background-color: #1a1a1a;
    color: #ddd;
}
body.dark-mode .card-light-dark {
    background-color: #2a2a2a;
    border-color: #333;
}
body.dark-mode .card-text-dark {
    color: #f0f0f0;
}
body.dark-mode .text-muted {
    color: #aaa !important;
}

/* ===================== Responsive ===================== */
@media (max-width: 768px) {
    .card-body h5 {
        font-size: 1rem;
    }
    .card-body p {
        font-size: 0.75rem;
    }
    .card-img-top {
        padding: 1.5rem;
    }
}
.card {
    display: flex;
    flex-direction: column;
}

.card-img-top {
    flex: 1; /* ocupa el espacio disponible verticalmente */
    object-fit: contain; /* mantiene la proporción de la imagen */
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-body {
    min-height: 60px; /* ajusta según el tamaño del texto */
    display: flex;
    align-items: center; /* centra verticalmente el texto dentro de card-body */
    justify-content: center; /* centra horizontalmente */
}


/* ===================== Opcional: transición suave para dark mode ===================== */
body, .card-light-dark, .card-text-dark, .text-muted {
    transition: background-color 0.3s ease, color 0.3s ease;
}

</style>

<div class="container py-5" >

  <!-- Título principal -->

  <div class="text-center mb-5">
    <h1 class="fw-bold text-primary" data-key="work_order">Work Order</h1>
    <h6 class="text-muted" data-key="select_the_type_of_work_you_want_to_manage">Selecciona el tipo de trabajo que deseas gestionar</h6> 
  </div>

  <!-- Contenedor de tarjetas -->

  <div class="row g-4 justify-content-center d-flex flex-wrap" >


<!-- CISTERN -->
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
  <a href="{{ route('workorder.cistern.index') }}" class="text-decoration-none">
    <div class="card h-100 shadow-sm border-0 hover-shadow">
      <img src="{{ asset('images/tank.png') }}" class="card-img-top p-4" alt="Cistern icon" >
      <div class="card-body text-center">
        <h5 class="card-title fw-semibold text-dark">Cisterns</h5>
        <!-- <p class="text-muted small">Mantenimiento y revisión de cisternas</p> -->
      </div>
    </div>
  </a>
</div>

<!-- DRY BOX -->
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
  <a href="{{ route('comingsoon') }}" class="text-decoration-none">
    <div class="card h-100 shadow-sm border-0 hover-shadow">
      <img src="{{ asset('images/drybox.jpg') }}"  
           class="card-img-top p-4" alt="Dry Box icon">
      <div class="card-body text-center">
        <h5 class="card-title fw-semibold text-dark">Dry Box</h5>
        <!-- <p class="text-muted small">Verificación de cajas secas</p> -->
      </div>
    </div>
  </a>
</div>

<!-- PLATFORM -->
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
  <a href="{{ route('comingsoon') }}" class="text-decoration-none">
    <div class="card h-100 shadow-sm border-0 hover-shadow">
      <img src="{{ asset('images/platform.jpg') }}" 
           class="card-img-top p-4" alt="Platform icon">
      <div class="card-body text-center">
        <h5 class="card-title fw-semibold text-dark">Platform</h5>
        <!-- <p class="text-muted small">Control de plataformas y estructuras</p> -->
      </div>
    </div>
  </a>
</div>

<!-- PNEUMATIC -->
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
  <a href="{{ route('comingsoon') }}" class="text-decoration-none">
    <div class="card h-100 shadow-sm border-0 hover-shadow">
      <img src="{{ asset('images/pneumatic.jpg') }}" 
           class="card-img-top p-4" alt="Pneumatic icon">
      <div class="card-body text-center">
        <h5 class="card-title fw-semibold text-dark">Pneumatic</h5>
        <!-- <p class="text-muted small">Sistemas neumáticos y válvulas</p> -->
      </div>
    </div>
  </a>
</div>


  </div>

  <!-- Imagen decorativa inferior -->

  


<!-- Estilo adicional -->

<style>
  .hover-shadow {
    transition: all 0.3s ease;
  }
  .hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
  }
</style>

@endsection

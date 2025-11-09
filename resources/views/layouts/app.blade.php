<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome se carga dentro del sidebar -->

    <!-- Custom CSS -->
    <style>
        body {
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        .main-content {
            margin-left: 250px; /* ancho inicial del sidebar */
            padding: 20px;
            transition: margin-left 0.3s;
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        @media(max-width:768px){
            .main-content {
                margin-left: 0;
                padding-top: 60px; /* espacio para la navbar móvil */
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Mostrar el sidebar según el guard activo --}}
    @if(auth()->guard('driver')->check())
        @include('layouts.sidebar') {{-- Sidebar para Drivers --}}
    @elseif(auth()->guard('admin')->check())
        @include('layouts.sidebar_admin') {{-- Sidebar para Admins --}}
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>
        <script src="{{ asset('js/translations.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
           <script>
document.addEventListener("DOMContentLoaded", () => {
    const lang = localStorage.getItem("language") || "en";
    if (typeof applyLanguage === "function") {
        applyLanguage(lang);
    }
});
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    darkMode: 'media', // cambia a 'class' si lo controlas manualmente
    theme: {
      extend: {
        colors: {
          'dark-hover': '#0f172a', // azul oscuro elegante
          'light-hover': '#e2e8f0', // gris claro para modo claro
        }
      }
    }
  }
</script>
    @stack('scripts')
</body>
</html>

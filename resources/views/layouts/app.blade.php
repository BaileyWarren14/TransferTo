<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome already included in sidebar -->
    
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

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>

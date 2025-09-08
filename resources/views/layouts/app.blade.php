<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'TruckApp')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="margin:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

{{-- Incluir el sidebar --}}
{{-- Mostrar sidebar según el guard activo --}}
     @if(auth()->guard('driver')->check())
        @include('layouts.sidebar')
    @elseif(auth()->guard('admin')->check())
        @include('layouts.sidebar_admin')
    @endif

    <!-- Mobile navbar -->
<div class="mobile-navbar d-md-none">
    <a href="{{ url('/dashboard') }}">Dashboard</a>
    <a href="{{ url('/new') }}">New</a>
    <a href="{{ url('/details') }}">Details</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background:none; border:none; color:white;">Logout</button>
    </form>
</div>

{{-- Contenido principal --}}
<div id="content" style="margin-left: 250px; padding: 20px; transition: margin-left 0.3s;">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content');
const toggleBtn = document.getElementById("sidebarToggle");

toggleBtn.addEventListener("click", function() {
    sidebar.classList.toggle("collapsed");
    content.style.marginLeft = sidebar.classList.contains("collapsed") ? "80px" : "250px";
});
</script>

</body>
</html>

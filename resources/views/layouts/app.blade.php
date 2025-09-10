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
    <label style="display:flex; align-items:center; cursor:pointer; margin-left:10px;">
    <i class="fas fa-moon me-2"></i>
    
    <input type="checkbox" id="mobileDarkModeToggle" style="margin-left:auto;">
</label>
</div>

{{-- Contenido principal --}}
<div id="content" class="main-content">
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

const darkModeToggle = document.getElementById("darkModeToggle");
const body = document.body;
const mobileDarkModeToggle = document.getElementById("mobileDarkModeToggle");

toggleBtn.addEventListener("click", function() {
    sidebar.classList.toggle("collapsed");
    content.style.marginLeft = sidebar.classList.contains("collapsed") ? "80px" : "250px";
});

// Función para activar/desactivar dark mode
function setDarkMode(enabled) {
    if (enabled) {
        body.classList.add("dark-mode");
        localStorage.setItem("darkMode", "enabled");
        darkModeToggle.checked = true;
        if(mobileDarkModeToggle) mobileDarkModeToggle.checked = true;
    } else {
        body.classList.remove("dark-mode");
        localStorage.setItem("darkMode", "disabled");
        darkModeToggle.checked = false;
        if(mobileDarkModeToggle) mobileDarkModeToggle.checked = false;
    }
}

// Inicializar desde localStorage
if (localStorage.getItem("darkMode") === "enabled") {
    setDarkMode(true);
}

// Sidebar checkbox
darkModeToggle.addEventListener("change", function() {
    setDarkMode(this.checked);
    // Disparar evento global para otros listeners (opcional)
    const event = new CustomEvent("sidebarDarkMode", { detail: this.checked });
    window.dispatchEvent(event);
});

// Mobile navbar checkbox
if (mobileDarkModeToggle) {
    mobileDarkModeToggle.addEventListener("change", function() {
        setDarkMode(this.checked);
    });
}

// Opcional: escuchar evento global
window.addEventListener("sidebarDarkMode", function(e) {
    if(mobileDarkModeToggle) mobileDarkModeToggle.checked = e.detail;
});

</script>

</body>
</html>

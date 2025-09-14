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
        @include('layouts.sidebar') {{-- Drivers --}}
    @elseif(auth()->guard('admin')->check())
        @include('layouts.sidebar_admin') {{-- Admins --}}
    @endif

    <!-- Mobile navbar -->
<!--<div class="mobile-navbar d-md-none">
    <a href="{{ url('/driver/dashboard') }}">Dashboard</a>
    <a href="{{ url('/driver/log_book') }}">Logs</a>
    <a href="{{ url('/driver/change_duty_status') }}">Duty Status</a>
    <a href="{{ url('/driver/list') }}">Inspections</a>
    <a href="{{ url('/driver/details') }}">Details</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background:none; border:none; color:white;">Logout</button>
    </form>
    <label style="display:flex; align-items:center; cursor:pointer; margin-left:10px;">
    <i class="fas fa-moon me-2"></i>
    
    <input type="checkbox" id="mobileDarkModeToggle" style="margin-left:auto;">
</label>
</div>
-->
{{-- Contenido principal --}}
<!-- Mobile navbar hamburger -->
<div class="mobile-navbar d-md-none">
    <div class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
    </div>
    <div class="mobile-menu" id="mobileMenu">
        <!-- Botón de cerrar -->
        <div class="close-btn" id="closeMenu">
            <i class="fas fa-times"></i>
        </div>
        <a href="{{ url('/driver/dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
        <a href="{{ url('/driver/log_book') }}"><i class="fas fa-chart-line"></i> <span>Logs</span></a>
        <a href="{{ url('/driver/change_duty_status') }}"><i class="fas fa-toggle-on"></i> <span>Duty Status</span></a>
        <a href="{{ url('/driver/list') }}"><i class="fas fa-plus-circle"></i> <span>Inspections</span></a>
        <a href="{{ url('/driver/details') }}"><i class="fas fa-info-circle"></i> <span>Details</span></a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </button>
        </form>
        <label class="dark-mode-toggle-mobile">
            <i class="fas fa-moon me-2"></i>
            <input type="checkbox" id="mobileDarkModeToggle">
        </label>
    </div>
</div>

<div id="content" class="main-content">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!--
<script>
function setDarkMode(enabled) {
    if (enabled) {
        body.classList.add("dark-mode");
        localStorage.setItem("darkMode", "enabled");
        if (darkModeToggle) darkModeToggle.checked = true;
        if (mobileDarkModeToggle) mobileDarkModeToggle.checked = true;
    } else {
        body.classList.remove("dark-mode");
        localStorage.setItem("darkMode", "disabled");
        if (darkModeToggle) darkModeToggle.checked = false;
        if (mobileDarkModeToggle) mobileDarkModeToggle.checked = false;
    }
}

// Inicializar desde localStorage
if (localStorage.getItem("darkMode") === "enabled") {
    setDarkMode(true);
}

// Listener para el switch de móvil
if (mobileDarkModeToggle) {
    mobileDarkModeToggle.addEventListener("change", function () {
        setDarkMode(this.checked);
    });
}

</script>
-->
<style>
.mobile-navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #2a5298;
    z-index: 2000;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.hamburger i {
    font-size: 24px;
    color: white;
    cursor: pointer;
}

.mobile-menu {
    position: fixed;
    top: 0;
    left: -100%;
    width: 70%;
    max-width: 300px;
    height: 100%;
    background-color: #2a5298;
    padding: 60px 20px 20px 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    transition: left 0.3s ease;
    z-index: 2100;
}

.mobile-menu a,
.mobile-menu button.btn-logout-mobile {
    color: white;
    text-decoration: none;
    font-size: 18px;
    background: none;
    border: none;
    text-align: left;
    padding: 10px 0;
    width: 100%;
    cursor: pointer;
}

.mobile-menu a:hover,
.mobile-menu button.btn-logout-mobile:hover {
    background-color: #1e3c72;
    border-radius: 5px;
}

.dark-mode-toggle-mobile {
    display: flex;
    align-items: center;
    cursor: pointer;
    margin-top: 20px;
}

.mobile-menu.show {
    left: 0;
}
/* Botón de cerrar */
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    font-size: 24px;
    color: white;
}

.mobile-menu.show {
    left: 0;
}
</style>

<script>
const hamburger = document.getElementById("hamburger");
const mobileMenu = document.getElementById("mobileMenu");
const mobileDarkModeToggle = document.getElementById("mobileDarkModeToggle");
const closeMenuBtn = document.getElementById("closeMenu");

hamburger.addEventListener("click", () => {
    mobileMenu.classList.toggle("show");
});
// Cerrar menú con botón
closeMenuBtn.addEventListener("click", () => {
    mobileMenu.classList.remove("show");
});

// Cerrar menú al hacer clic fuera
document.addEventListener("click", (e) => {
    if (!mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
        mobileMenu.classList.remove("show");
    }
});

// Dark mode toggle
function setDarkMode(enabled) {
    if (enabled) {
        document.body.classList.add("dark-mode");
        localStorage.setItem("darkMode", "enabled");
        if (mobileDarkModeToggle) mobileDarkModeToggle.checked = true;
    } else {
        document.body.classList.remove("dark-mode");
        localStorage.setItem("darkMode", "disabled");
        if (mobileDarkModeToggle) mobileDarkModeToggle.checked = false;
    }
}

// Inicializar dark mode
if (localStorage.getItem("darkMode") === "enabled") {
    setDarkMode(true);
}

if (mobileDarkModeToggle) {
    mobileDarkModeToggle.addEventListener("change", function() {
        setDarkMode(this.checked);
    });
}
</script>
</body>
</html>

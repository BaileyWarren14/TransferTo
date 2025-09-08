<!-- sidebar.blade.php -->
<!-- Incluye Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

/* Sidebar por defecto */
.sidebar {
    height: 100%;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #2a5298;
    z-index: 1000;
    overflow-x: hidden;
    transition: width 0.3s, left 0.3s;
    padding-top: 60px;
}

/* Sidebar colapsado en desktop */
.sidebar.collapsed {
    width: 80px;
}

/* Sidebar activado en móvil */
.sidebar.active {
    left: 0;
}

/* Desktop: main content */
.main-content {
    margin-left: 250px;
    transition: margin-left 0.3s;
    padding: 20px;
}

/* Desktop: sidebar colapsado */
.sidebar.collapsed ~ .main-content {
    margin-left: 80px;
}


.sidebar a {
    padding: 15px;
    text-decoration: none;
    font-size: 18px;
    color: #fff;
    display: flex;
    align-items: center;
    transition: 0.3s;
}

.sidebar a i {
    margin-right: 10px;
}

.sidebar a:hover {
    background-color: #1e3c72;
}

.sidebar.collapsed {
    width: 80px; /* ancho reducido */
}

.sidebar.collapsed a span {
    display: none; /* oculta texto */
}

.sidebar.collapsed a i {
    margin: 0 auto; /* centra iconos */
}

/* Toggle dentro del sidebar */
#sidebarToggle {
    position: absolute;
    top: 10px;
    right: 15px;
    background-color: #2a5298;
    color: #fff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1100;
    transition: transform 0.3s;
}




/* Botón logout */
.btn-logout {
    background: none;
    border: none;
    color: white;
    padding: 15px;
    width: 100%;
    text-align: left;
    font-size: 18px;
}
.btn-logout i {
    margin-right: 10px;
}

/* Responsive: móvil */

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: -250px; /* oculto */
        width: 250px;
        height: 100%;
        transition: left 0.3s;
        z-index: 2000; /* encima del contenido */
    }
    .sidebar.active {
        left: 0; /* visible */
    }
    .main-content {
        margin-left: 0 !important; /* ocupar todo el ancho */
        padding-top: 70px; /* espacio para navbar */
    }
    .main-content {
        margin-left: 0;
        padding-top: 70px;
    }
    .mobile-navbar {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #2a5298;
        z-index: 1000;
        justify-content: space-around;
        padding: 10px 0;
    }
    .mobile-navbar a {
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        text-align: center;
    }
}

</style>

<div id="mySidebar" class="sidebar">
    <div id="sidebarToggle">
        <i class="fas fa-angle-left"></i>
    </div>
    <a href="{{ url('/dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
    <a href="{{ url('/new') }}"><i class="fas fa-plus-circle"></i> <span>New</span></a>
    <a href="{{ url('/details') }}"><i class="fas fa-info-circle"></i> <span>Details</span></a>
    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </button>
    </form>
</div>


<script>
const sidebar = document.getElementById("mySidebar");
const toggleBtn = document.getElementById("sidebarToggle");

toggleBtn.addEventListener("click", function() {
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("active"); // abre/cierra sidebar en móvil
    } else {
        sidebar.classList.toggle("collapsed"); // colapsa en desktop
    }

    const icon = toggleBtn.querySelector("i");
    if (window.innerWidth <= 768) {
        icon.className = sidebar.classList.contains("active") ? "fas fa-angle-right" : "fas fa-angle-left";
    } else {
        icon.className = sidebar.classList.contains("collapsed") ? "fas fa-angle-right" : "fas fa-angle-left";
    }
});
</script>

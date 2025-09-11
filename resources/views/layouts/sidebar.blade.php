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
    width: calc(100% - 250px); /* ancho dinámico según sidebar */
    transition: margin-left 0.3s, width 0.3s;
    padding: 20px;
}

/* Desktop: sidebar colapsado */
.sidebar.collapsed ~ .main-content {
    margin-left: 80px;
    width: calc(100% - 80px); /* se ajusta automáticamente */
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

    #content, .main-content, #main-content {
        margin-left: 0 !important;
        padding-top: 70px !important;   /* espacio para mobile navbar */
        padding-left: 15px !important;
        padding-right: 15px !important;
        width: 100% !important;
        position: relative !important;
        left: 0 !important;
        transform: none !important;
    }

    /* Anula cualquier selector sibling que intente empujar al content */
    #mySidebar.collapsed ~ #content,
    #mySidebar.collapsed ~ .main-content,
    #mySidebar.active ~ #content,
    #mySidebar.active ~ .main-content {
        margin-left: 0 !important;
    }

    /* Si por alguna razón hay estilos inline añadidos por JS, forzamos estos atributos CSS */
    #content[style] { margin-left: 0 !important; left: 0 !important; transform: none !important; }


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
    /* el sidebar nunca debe empujar el contenido en móvil */
    .sidebar.active ~ .main-content,
    .sidebar.collapsed ~ .main-content {
        margin: 0 !important;
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

/* Dark Mode */
body.dark-mode {
    background-color: #121212;
    color: #f0f0f0;
}

/* Sidebar */
body.dark-mode .sidebar {
    
}

body.dark-mode .sidebar a {
    color: #f0f0f0;
}

body.dark-mode .sidebar a:hover {
    background-color: #333;
}

/* Main content */
body.dark-mode .main-content {
    background-color: #121212;
    color: #f0f0f0;
}

/* Inputs, selects, textareas */
body.dark-mode input,
body.dark-mode select,
body.dark-mode textarea {
    background-color: #1e1e1e !important;
    color: #f0f0f0 !important;
    border-color: #333 !important;
}

/* Buttons */
body.dark-mode button {
   
}

/* Labels */
body.dark-mode label {
    color: #f0f0f0 !important;
}

/* Placeholders */
body.dark-mode ::placeholder {
    color: #aaa !important;
}

</style>

<div id="mySidebar" class="sidebar">
    <div id="sidebarToggle">
        <i class="fas fa-angle-left"></i>
    </div>
    <a href="{{ url('/driver/dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
    <a href="{{ url('/driver/log_book') }}"><i class="fas fa-tachometer-alt"></i> <span>Logs</span></a>
    <a href="{{ url('/driver/list') }}"><i class="fas fa-plus-circle"></i> <span>Inspections</span></a>
    <a href="{{ url('/driver/details') }}"><i class="fas fa-info-circle"></i> <span>Details</span></a>
    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </button>
    </form>

    <div class="sidebar-footer mt-auto" style="padding: 15px; color: #fff;">
        <label for="darkModeToggle" style="display: flex; align-items: center; cursor: pointer;">
            <i class="fas fa-moon me-2"></i>
            Dark Mode
            <input type="checkbox" id="darkModeToggle" style="margin-left: auto;">
        </label>
    </div>



</div>


<script>
const sidebar = document.getElementById("mySidebar");
const toggleBtn = document.getElementById("sidebarToggle");
const darkModeToggle = document.getElementById("darkModeToggle");
const body = document.body;

// Toggle sidebar
toggleBtn.addEventListener("click", function() {
    const icon = toggleBtn.querySelector("i");
    
    if (window.innerWidth <= 768) {
        // Móvil
        sidebar.classList.toggle("active");
        icon.className = sidebar.classList.contains("active") ? "fas fa-angle-right" : "fas fa-angle-left";
    } else {
        // Desktop
        sidebar.classList.toggle("collapsed");
        icon.className = sidebar.classList.contains("collapsed") ? "fas fa-angle-right" : "fas fa-angle-left";
    }
});

  // Inicializar dark mode desde localStorage
    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark-mode");
        darkModeToggle.checked = true;
    }

    // Cambiar modo oscuro
    darkModeToggle.addEventListener("change", function() {
        if (this.checked) {
            body.classList.add("dark-mode");
            localStorage.setItem("darkMode", "enabled");
        } else {
            body.classList.remove("dark-mode");
            localStorage.setItem("darkMode", "disabled");
        }
    });


    // Dentro de sidebar.blade.php
darkModeToggle.addEventListener("change", function() {
    const event = new CustomEvent("sidebarDarkMode", { detail: this.checked });
    window.dispatchEvent(event);
});
</script>


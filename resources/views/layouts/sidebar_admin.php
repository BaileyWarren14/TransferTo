<!-- Incluye Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

.sidebar {
    height: 100%;
    width: 250px; /* ancho por defecto */
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    background-color: #2a5298;
    overflow-x: hidden;
    transition: width 0.3s;
    padding-top: 60px;
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

.hamburger {
    font-size: 24px;
    cursor: pointer;
    color: #2a5298;
    position: fixed;
    top: 15px;
    left: 260px; /* ajusta según sidebar */
    z-index: 1100;
    transition: left 0.3s;
}

.sidebar.collapsed ~ .hamburger {
    left: 90px;
}

.main-content {
    margin-left: 250px;
    transition: margin-left 0.3s;
    padding: 20px;
}

.sidebar.collapsed ~ .main-content {
    margin-left: 80px;
}
/* Botón toggle dentro del sidebar */
#sidebarToggle {
    position: absolute;
    top: 5px; /* más arriba */
    right: 15px; /* pegado al borde derecho */
    background-color: #2a5298;
    color: #fff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.3s;
    z-index: 1100;
}
</style>
</head>
<body>

<div id="mySidebar" class="sidebar">
    <div id="sidebarToggle">
        <i class="fas fa-angle-left"></i>
    </div>
    <a href="{{ url('/dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
    <a href="{{ url('/new') }}"><i class="fas fa-plus-circle"></i> <span>Register User</span></a>
    <a href="{{ url('/details') }}"><i class="fas fa-info-circle"></i> <span>Details</span></a>
    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button  type="submit" class="btn btn-logout" style="background:none; border:none; color:white; padding:15px; width:100%; text-align:left;">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </button>
    </form>
</div>


<div class="main-content">
    
    
</div>

<script>
const sidebar = document.getElementById("mySidebar");
const toggleBtn = document.getElementById("sidebarToggle");

toggleBtn.addEventListener("click", function() {
    sidebar.classList.toggle("collapsed");
    const icon = toggleBtn.querySelector("i");
    icon.className = sidebar.classList.contains("collapsed") ? "fas fa-angle-right" : "fas fa-angle-left";
});
</script>
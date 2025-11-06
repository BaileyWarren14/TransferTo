<!-- sidebar.blade.php -->

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* =========================== General =========================== */
body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

/* =========================== Sidebar Desktop =========================== */
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

.sidebar.collapsed {
    width: 80px;
}

.sidebar a {
    padding: 10px;
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

.sidebar.collapsed a span {
    display: none;
}

.sidebar.collapsed a i {
    margin: 0 auto;
}
/* Sidebar colapsado: ocultar textos de logout y footer */
.sidebar.collapsed .btn-logout span,
.sidebar.collapsed .sidebar-footer span {
    display: none;
}

/* Centrar iconos de logout y footer cuando esté colapsado */
.sidebar.collapsed .btn-logout i,
.sidebar.collapsed .sidebar-footer i {
    margin: 0 auto;
}


/* Toggle sidebar desktop */
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
}

/* Footer desktop */
.sidebar-footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 15px;
    color: #fff;
}

/* =========================== Mobile =========================== */
.mobile-navbar {
    display: none;
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
    overflow-y: auto;   /* ✅ Permite desplazamiento vertical */
    -webkit-overflow-scrolling: touch; /* ✅ Scroll suave en iOS */
}
.mobile-menu a, .mobile-menu button {
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
.mobile-menu a:hover, .mobile-menu button:hover {
    background-color: #1e3c72;
    border-radius: 5px;
}
.mobile-menu.show { left: 0; }

/* Hamburger */
.hamburger i {
    font-size: 24px;
    color: white;
    cursor: pointer;
}

/* Close button */
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    color: white;
    cursor: pointer;
}

/* Toggles mobile */
.dark-mode-toggle-mobile, .language-toggle-mobile {
    display: flex;
    align-items: center;
    cursor: pointer;
    margin-top: 20px;
}

/* =========================== Responsive =========================== */
@media(max-width:768px){
    .sidebar {
        display: none;
    }
    .mobile-navbar {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #2a5298;
        z-index: 2000;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
    }
    .main-content {
        margin-left: 0 !important;
        padding-top: 60px !important;
    }
}
.btn-logout {
    background: none;
    border: none;
    color: white;
    padding: 15px;
    width: 100%;
    text-align: left;
    font-size: 18px;
    display: flex;
    align-items: center;
    cursor: pointer;
}

.btn-logout i {
    margin-right: 10px;
}

.btn-logout:hover {
    background-color: #1e3c72;
    border-radius: 5px;
}
.sidebar form {
    margin-bottom: 20px; /* ajusta el valor según necesites */
}
</style>

<!-- =========================== Sidebar Desktop =========================== -->
<div id="mySidebar" class="sidebar d-none d-md-block">
    <div id="sidebarToggle"><i class="fas fa-angle-left"></i></div>

    <a href="{{ url('/driver/dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span data-key="dashboard">Dashboard</span></a>
    <a href="{{ url('/driver/log_book') }}"><i class="fas fa-chart-line"></i> <span data-key="logs">Logs</span></a>
    <a href="{{ url('/driver/change_duty_status') }}"><i class="fas fa-toggle-on"></i> <span data-key="duty_status">Duty Status</span></a>
    <a href="{{ url('/driver/list') }}"><i class="fas fa-plus-circle"></i> <span data-key="dot">DOT Inspection Mode</span></a>
    <a href="{{ url('/driver/menu') }}"><i class="fas fa-info-circle"></i> <span data-key="work_order">Work Order</span></a>
    <a href="{{ url('/driver/messages') }}"><i class="fas fa-envelope"></i> <span data-key="messages">Messages</span></a>
    <a href="{{ url('/driver/safety') }}"><i class="fas fa-shield-alt"></i> <span data-key="safety">Safety</span></a>
    <a href="{{ url('/driver/about') }}"><i class="fas fa-info-circle"></i> <span data-key="truck_information">Truck information</span></a>
    <a href="{{ url('/driver/notifications') }}"><i class="fas fa-bell"></i> <span data-key="notifications">Notifications</span></a>
    <a href="{{ url('/driver/documents') }}"><i class="fas fa-file-alt"></i> <span data-key="documents">Documents</span></a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> <span data-key="logout">Logout</span></button>
    </form>
    
    <div class="sidebar-footer">
        <label for="darkModeToggle" style="display:flex;align-items:center;cursor:pointer;">
            <i class="fas fa-moon me-2"></i>
            <span data-key="dark_mode">Dark Mode</span>
            <input type="checkbox" id="darkModeToggle" style="margin-left:auto;">
        </label>
        <label for="languageToggle" style="display:flex;align-items:center;cursor:pointer;margin-top:10px;">
            <i class="fas fa-language me-2"></i>
            <span id="languageLabel">ES / EN</span>
            <input type="checkbox" id="languageToggle" style="margin-left:auto;">
        </label>
    </div>
</div>

<!-- =========================== Mobile Navbar =========================== -->
<div class="mobile-navbar d-md-none">
    <div class="hamburger" id="hamburger"><i class="fas fa-bars"></i></div>
</div>

<div class="mobile-menu" id="mobileMenu">
    <div class="close-btn" id="closeMenu"><i class="fas fa-times"></i></div>

    <a href="{{ url('/driver/dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span data-key="dashboard">Dashboard</span></a>
    <a href="{{ url('/driver/log_book') }}"><i class="fas fa-chart-line"></i> <span data-key="logs">Logs</span></a>
    <a href="{{ url('/driver/change_duty_status') }}"><i class="fas fa-toggle-on"></i> <span data-key="duty_status">Duty Status</span></a>
    <a href="{{ url('/driver/list') }}"><i class="fas fa-plus-circle"></i> <span data-key="dot">DOT Inspection Mode</span></a>
    <a href="{{ url('/driver/menu') }}"><i class="fas fa-info-circle"></i> <span data-key="work_order">Work Order</span></a>
    <a href="{{ url('/driver/messages') }}"><i class="fas fa-envelope"></i> <span data-key="messages">Messages</span></a>
    <a href="{{ url('/driver/safety') }}"><i class="fas fa-shield-alt"></i> <span data-key="safety">Safety</span></a>
    <a href="{{ url('/driver/about') }}"><i class="fas fa-info-circle"></i> <span data-key="truck_information">Truck information</span></a>
    <a href="{{ url('/driver/notifications') }}"><i class="fas fa-bell"></i> <span data-key="notifications">Notifications</span></a>
    <a href="{{ url('/driver/documents') }}"><i class="fas fa-file-alt"></i> <span data-key="documents">Documents</span></a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> <span data-key="logout">Logout</span></button>
    </form>

    <label class="dark-mode-toggle-mobile">
        <i class="fas fa-moon me-2"></i>
            <span data-key="dark_mode">Dark Mode</span>
            
        <input type="checkbox" id="mobileDarkModeToggle">
    </label>
    <label class="language-toggle-mobile">
        <i class="fas fa-language me-2"></i>
        <input type="checkbox" id="mobileLanguageToggle">
        <span id="mobileLanguageLabel">ES / EN</span>
    </label>
</div>
<script src="{{ asset('js/translations.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- =========================== JS =========================== -->
<script>
const sidebar = document.getElementById("mySidebar");
const toggleBtn = document.getElementById("sidebarToggle");
const darkModeToggle = document.getElementById("darkModeToggle");
const languageToggle = document.getElementById("languageToggle");

const hamburger = document.getElementById("hamburger");
const mobileMenu = document.getElementById("mobileMenu");
const closeMenuBtn = document.getElementById("closeMenu");
const mobileDarkModeToggle = document.getElementById("mobileDarkModeToggle");
const mobileLanguageToggle = document.getElementById("mobileLanguageToggle");

// ================= Desktop Sidebar Toggle =================
toggleBtn?.addEventListener("click", ()=>{
    sidebar.classList.toggle("collapsed");
    toggleBtn.querySelector("i").className = sidebar.classList.contains("collapsed") ? "fas fa-angle-right" : "fas fa-angle-left";
});

// ================= Mobile Menu =================
hamburger?.addEventListener("click", ()=> mobileMenu.classList.add("show"));
closeMenuBtn?.addEventListener("click", ()=> mobileMenu.classList.remove("show"));
document.addEventListener("click", (e)=>{
    if(!mobileMenu.contains(e.target) && !hamburger.contains(e.target)){
        mobileMenu.classList.remove("show");
    }
});

// ================= Dark Mode =================
function setDarkMode(enabled){
    if(enabled){
        document.body.classList.add("dark-mode");
        localStorage.setItem("darkMode","enabled");
        darkModeToggle.checked = true;
        mobileDarkModeToggle.checked = true;
    } else {
        document.body.classList.remove("dark-mode");
        localStorage.setItem("darkMode","disabled");
        darkModeToggle.checked = false;
        mobileDarkModeToggle.checked = false;
    }
}
if(localStorage.getItem("darkMode")==="enabled") setDarkMode(true);

darkModeToggle?.addEventListener("change", ()=> setDarkMode(darkModeToggle.checked));
mobileDarkModeToggle?.addEventListener("change", ()=> setDarkMode(mobileDarkModeToggle.checked));

// ================= Language =================
// Traduce todos los elementos con data-key=""
function applyLanguage(lang) {
    const elements = document.querySelectorAll("[data-key]");
    elements.forEach(el => {
        const key = el.getAttribute("data-key");
        if (translations[lang] && translations[lang][key]) {

            if (el.tagName === "INPUT" || el.tagName === "TEXTAREA") {
                // Cambia el valor del input/textarea
                el.value = translations[lang][key];

                // Si quieres traducir también el placeholder
                if (el.hasAttribute('placeholder')) {
                    el.placeholder = translations[lang][key];
                }

            } else if (el.tagName === "SELECT") {
                // Si quieres traducir opciones de select (opcional)
                Array.from(el.options).forEach(option => {
                    const optionKey = option.getAttribute("data-key");
                    if(optionKey && translations[lang][optionKey]){
                        option.textContent = translations[lang][optionKey];
                    }
                });

            } else {
                // Para todos los demás elementos (span, h1, p, etc.)
                el.textContent = translations[lang][key];
            }

        }
    });
}

// Cambia el idioma y actualiza UI + LocalStorage
function setLanguage(isEnglish){
    const lang = isEnglish ? "en" : "es";
    document.documentElement.lang = lang;
    localStorage.setItem("language", lang);

    applyLanguage(lang);

    // Checkbox sincronizado en desktop y móvil
    languageToggle.checked = isEnglish;
    mobileLanguageToggle.checked = isEnglish;

    // Texto de la etiqueta del toggle
    const labelText = isEnglish ? "EN / ES" : "ES / EN";
    document.getElementById("languageLabel").textContent = labelText;
    document.getElementById("mobileLanguageLabel").textContent = labelText;
}

// ================= Al cargar la página =================
document.addEventListener("DOMContentLoaded", () => {
    const savedLang = localStorage.getItem("language") || "es";
    applyLanguage(savedLang);

    const isEnglish = savedLang === "en";
    setLanguage(isEnglish);
});

languageToggle?.addEventListener("change", ()=> setLanguage(languageToggle.checked));
mobileLanguageToggle?.addEventListener("change", ()=> setLanguage(mobileLanguageToggle.checked));
</script>

// Actualiza la hora cada segundo
function updateTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2,'0');
    const minutes = now.getMinutes().toString().padStart(2,'0');
    const seconds = now.getSeconds().toString().padStart(2,'0');
    document.getElementById('time').textContent = `🕒 Local Time: ${hours}:${minutes}:${seconds}`;

    // Obtiene la zona horaria
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.getElementById('timezone').textContent = `⏰ Timezone: ${timezone}`;
}

// Función para obtener la ubicación del usuario
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            // Mostrar lat/lon como fallback
            let locationText = `Lat: ${lat.toFixed(2)}, Lon: ${lon.toFixed(2)}`;

            // Intentar obtener ciudad y país usando API de geocoding gratuita
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                const data = await res.json();
                const city = data.address.city || data.address.town || data.address.village || data.address.county;
                const state = data.address.state || '';
                locationText = `${city}, ${state}`;
            } catch(err) {
                console.log('No se pudo obtener ciudad: ', err);
            }

            document.getElementById('location').textContent = `📍 Location: ${locationText}`;

            // Ejemplo de clima ficticio (reemplazar con OpenWeatherMap si quieres)
           
        });
    } else {
        document.getElementById('location').textContent = '📍 Location: Not available';
    }
}

// Inicializa
updateTime();
getLocation();
setInterval(updateTime, 1000); // actualiza la hora cada segundo

document.addEventListener("DOMContentLoaded", function () {
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    // Evita mandar la misma zona si ya está guardada en sesión
    if (!sessionStorage.getItem("timezoneSent")) {
        fetch("{{ route('set.timezone') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ timezone })
        })
        .then(res => res.json())
        .then(data => {
            console.log("✅ Zona horaria guardada:", data.timezone);
            sessionStorage.setItem("timezoneSent", "true");
        })
        .catch(err => console.error("❌ Error al guardar zona horaria:", err));
    }
});


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
const languageToggle = document.getElementById("languageToggle");
const languageLabel = document.getElementById("languageLabel");

// Inicializar idioma desde localStorage
if (localStorage.getItem("language") === "en") {
    languageToggle.checked = true; // inglés activo
    document.documentElement.lang = "en";
} else {
    languageToggle.checked = false; // español activo por defecto
    document.documentElement.lang = "es";
}

// Cambiar idioma
languageToggle.addEventListener("change", function() {
    if (this.checked) {
        localStorage.setItem("language", "en");
        document.documentElement.lang = "en";
        languageLabel.textContent = "EN / ES";
    } else {
        localStorage.setItem("language", "es");
        document.documentElement.lang = "es";
        languageLabel.textContent = "ES / EN";
    }
    
    // Opcional: disparar un evento global para que otras partes de JS reaccionen
    const event = new CustomEvent("languageChanged", { detail: this.checked ? "en" : "es" });
    window.dispatchEvent(event);
});

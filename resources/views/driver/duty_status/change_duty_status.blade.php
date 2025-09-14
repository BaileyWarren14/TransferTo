@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4 rounded-4">
        <!-- Duty Status -->
        <h5 class="fw-bold mb-3 text-center">DUTY STATUS*</h5>
        <div class="grid grid-cols-5 gap-2 mb-4 text-center">
            <button type="button" class="btn duty-btn" data-status="ON">ON</button>
            <button type="button" class="btn duty-btn" data-status="OFF">OFF</button>
            <button type="button" class="btn duty-btn" data-status="SD">SD</button>
            <button type="button" class="btn duty-btn" data-status="D">D</button>
            <button type="button" class="btn duty-btn" data-status="WT">WT</button>
        </div>

        <!-- Personal / Yard -->
        <div class="d-flex justify-content-between mb-4 flex-wrap gap-2">
            <button type="button" class="btn btn-outline-dark flex-fill py-2 more-btn">PERSONAL CONVEYANCE</button>
            <button type="button" class="btn btn-outline-dark flex-fill py-2 more-btn">YARD MOVE</button>
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label for="location" class="form-label fw-bold">LOCATION*</label>
            <div class="input-group">
                <input type="text" id="location" class="form-control rounded-start-pill" placeholder="Current location...">
                <button type="button" id="getLocation" class="btn btn-primary rounded-end-pill">
                    <i class="fas fa-location-arrow"></i>
                </button>
            </div>
        </div>

        <!-- Notes -->
        <div class="mb-3">
            <label for="notes" class="form-label fw-bold">NOTES</label>
            <input type="text" id="notes" class="form-control rounded-pill" placeholder="Add notes...">
        </div>

        <!-- Save -->
        <div class="text-center mt-4">
            <button type="button" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow-sm">SAVE</button>
        </div>
    </div>
</div>

<!-- FontAwesome para el ícono -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

<script>
    // Toggle Duty Status exclusivo
    document.querySelectorAll('.duty-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('active')) {
            // Si ya está activo y se vuelve a dar clic → se desactiva
            btn.classList.remove('active');
            } else {
                // Si no está activo → se activa este y se desactivan los demás
                document.querySelectorAll('.duty-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }
        });
    });

    // Toggle demase
    document.querySelectorAll('.more-btn').forEach(btn => {
        btn.addEventListener('click', () => {
             if (btn.classList.contains('active')) {
            // Si ya está activo y se vuelve a dar clic → se desactiva
            btn.classList.remove('active');
        } else {
            // Si no está activo → se activa este y se desactivan los demás
            document.querySelectorAll('.more-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
        });
    });

    // Obtener ubicación GPS
    document.getElementById('getLocation').addEventListener('click', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Usamos la API gratuita de Nominatim
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('location').value = data.display_name;
                    } else {
                        document.getElementById('location').value = `${lat}, ${lng}`;
                    }
                })
                .catch(err => {
                    console.error("Error obteniendo dirección:", err);
                    document.getElementById('location').value = `${lat}, ${lng}`;
                });
        }, err => {
            alert("Error obteniendo la ubicación: " + err.message);
        });
    } else {
        alert("Tu navegador no soporta geolocalización.");
    }
});
</script>

<style>
    .duty-btn {
        border: 2px solid #6c757d;
        border-radius: 50px;
        padding: 10px;
        font-weight: bold;
        background: #fff;
        color: #000;
        transition: all 0.2s ease-in-out;
        width: 100%;
    }

    .duty-btn.active {
        background-color: #007bff;
        border-color: #0056b3;
        color: #fff;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 576px) {
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }
    }

    @media (min-width: 577px) {
        .grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }
    }
</style>
@endsection

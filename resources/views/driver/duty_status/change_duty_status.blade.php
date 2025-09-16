@extends('layouts.app')

@section('content')
<style>
    /* Contenedor principal y body */
    body.dark-mode {
        background-color: #121212; /* fondo general oscuro */
        color: #f0f0f0; /* texto por defecto */
    }

    /* Tarjetas y contenedores */
    body.dark-mode .card {
        background-color: #1e1e1e;
        color: #f0f0f0;
        border: 1px solid #333;
    }

    /* Inputs, textareas y selects */
    body.dark-mode input,
    body.dark-mode textarea,
    body.dark-mode select {
        background-color: #2a2a2a;
        color: #f0f0f0;
        border: 1px solid #444;
    }

    /* Botones */
    body.dark-mode .btn {
        background-color: #333;
        color: #f0f0f0;
        border: 1px solid #555;
    }

    body.dark-mode .btn-primary {
        background-color: #007bff;
        color: #fff;
        border-color: #0056b3;
    }

    body.dark-mode .btn-success {
        background-color: #28a745;
        border-color: #1e7e34;
        color: #fff;
    }

    /* Duty buttons */
    body.dark-mode .duty-btn {
        background-color: #2a2a2a;
        color: #f0f0f0;
        border: 2px solid #555;
    }

    body.dark-mode .duty-btn.active {
        background-color: #007bff;
        border-color: #0056b3;
        color: #fff;
    }

    /* Radios / checkboxes */
    body.dark-mode .form-check-input {
        background-color: #2a2a2a;
        border-color: #555;
        accent-color: #007bff;
    }

    body.dark-mode .form-check-label {
        color: #f0f0f0;
    }

    /* Input group text */
    body.dark-mode .input-group-text {
        background-color: #2a2a2a;
        border-color: #555;
        color: #f0f0f0;
    }

    /*Para la animacion de la ubicacion */
    /* Spinner animado */
    .spinner {
    border: 4px solid #f3f3f3; /* gris claro */
    border-top: 4px solid #007bff; /* azul */
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    display: inline-block;
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }


    </style>
<div class="container mt-4">
    <div class="text-center mb-3">
        <a href="{{ route('driver.logs.today') }}" class="btn btn-info px-4 py-2 rounded-pill">
            View Today's Logs
        </a>
    </div>
    <form id="dutyStatusForm" action="{{ route('driver.duty_status.store') }}" method="POST">
        @csrf
        <div class="card shadow-lg p-4 rounded-4">
            <!-- Duty Status -->
            <h5 class="fw-bold mb-3 text-center">DUTY STATUS*</h5>
            <div class="grid grid-cols-5 gap-2 mb-4 text-center">
                <button type="button" class="btn duty-btn" data-status="ON">ON</button>
                <button type="button" class="btn duty-btn" data-status="OFF">OFF</button>
                <button type="button" class="btn duty-btn" data-status="SB">SB</button>
                <button type="button" class="btn duty-btn" data-status="D">D</button>
                <button type="button" class="btn duty-btn" data-status="WT">WT</button>
            </div>

            <!-- Personal / Yard -->
            <div class="row mb-4">
                <div class="col-6 d-flex justify-content-center">
                    <button type="button" class="btn btn-outline-dark w-100 py-2 duty-btn" data-type="PERSONAL" name="PC">
                        PERSONAL CONVEYANCE
                    </button>
                </div>
                <div class="col-6 d-flex justify-content-center">
                    <button type="button" class="btn btn-outline-dark w-100 py-2 duty-btn" data-type="YARD" name="YD">
                        YARD MOVE
                    </button>
                </div>
            </div>

            <!-- Location -->
            <div class="mb-3">
                <!-- Loader mientras se obtiene la ubicación -->
                <div id="locationLoader" style="display:none; text-align:center; margin-top:5px;">
                    <div class="spinner"></div>
                    <p>Obteniendo ubicación...</p>
                </div>

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
                <button type="button" id="saveBtn" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow-sm">SAVE</button>
            </div>
        </div>

        <!-- Hidden inputs -->
        <input type="hidden" name="status" id="status_input">
        <input type="hidden" name="location" id="location_input">
        <input type="hidden" name="notes" id="notes_input">
    </form>
</div>


<!-- FontAwesome para el ícono -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    const locationInput = document.getElementById('location');

    if (navigator.geolocation) {
        // Mostrar SweetAlert de carga
        Swal.fire({
            title: 'Getting location...',
            html: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
/*  Para ubicacion completa
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        locationInput.value = data.display_name;
                    } else {
                        locationInput.value = `${lat}, ${lng}`;
                    }
                    Swal.close(); // Cerrar SweetAlert de carga
                })
                .catch(err => {
                    console.error("Error getting address:", err);
                    locationInput.value = `${lat}, ${lng}`;
                    Swal.close(); // Cerrar SweetAlert de carga
                    Swal.fire('Error', 'Could not retrieve full address.', 'error');
                });

                */
               navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const city = data.address.city || data.address.town || data.address.village || '';
                        const state = data.address.state || '';
                        const country = data.address.country || '';
                        locationInput.value = [city, state, country].filter(Boolean).join(', ');
                    } else {
                        locationInput.value = `${lat}, ${lng}`;
                    }
                    Swal.close(); // Cerrar SweetAlert de carga
                })
                .catch(err => {
                    console.error("Error getting address:", err);
                    locationInput.value = `${lat}, ${lng}`;
                    Swal.close(); // Cerrar SweetAlert de carga
                    Swal.fire('Error', 'Could not retrieve address.', 'error');
                });
        }, err => {
            Swal.close(); // Cerrar SweetAlert de carga
            Swal.fire('Error', 'Error getting location: ' + err.message, 'error');
        });
    } else {
        Swal.fire('Error', 'Your browser does not support geolocation.', 'error');
    }
});


const saveBtn = document.getElementById('saveBtn');

saveBtn.addEventListener('click', () => {
    const activeDuty = document.querySelector('.duty-btn.active');
    const locationValue = document.getElementById('location').value.trim();
    const notesValue = document.getElementById('notes').value.trim();

    // Validaciones
    if (!activeDuty && !locationValue) {
        Swal.fire('Error', 'Please select a Duty Status and enter a location before saving.', 'error');
        return;
    } else if (!activeDuty) {
        Swal.fire('Error', 'Please select a Duty Status before saving.', 'error');
        return;
    } else if (!locationValue) {
        Swal.fire('Error', 'Please enter a location before saving.', 'error');
        return;
    }

    // Asignar valores a los hidden inputs
    document.getElementById('status_input').value = activeDuty.dataset.status;
    document.getElementById('location_input').value = locationValue;
    document.getElementById('notes_input').value = notesValue;

    // Confirmación
    Swal.fire({
        title: 'Confirm Duty Status change?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, save',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('dutyStatusForm').submit(); // envío tradicional
        }
    });
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

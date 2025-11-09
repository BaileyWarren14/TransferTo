@extends('layouts.app')

@section('content')
<div class="container py-4 hos-container">
    <h2 class="fw-bold mb-4">HOS Violations</h2>

    @if(count($violations) > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($violations as $v)
                <div class="hos-item p-3 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-danger">HOS</span>
                        <span class="hos-date small">{{ $v['date'] }}</span>
                    </div>
                    <div>
                        <span>{{ $v['message'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="fst-italic text-muted">No HOS violations found.</p>
    @endif
</div>

{{-- Estilos personalizados --}}
<style>
    /* Fondo general modo claro */
    .hos-container {
        background-color: #f8f9fa;
        color: #212529;
        border-radius: 8px;
        min-height: 100vh;
        transition: background-color 0.4s, color 0.4s;
    }

    /* Cada elemento */
    .hos-item {
        background-color: #ffffff;
        border: none;
        transition: background-color 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    /* Hover modo claro */
    .hos-item:hover {
        background-color: #e9ecef;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
    }

    /* ===== MODO OSCURO ===== */
    body.dark-mode .hos-container {
        background-color: #0d1117;
        color: #e9ecef;
    }

    body.dark-mode .hos-item {
        background-color: #1c1f26;
        color: #e9ecef;
    }

    body.dark-mode .hos-item:hover {
        background-color: #10141a;
        box-shadow: 0 0 15px rgba(0, 128, 255, 0.4);
    }

    /* Fecha visible en ambos modos */
    .hos-date {
        color: #6c757d; /* gris medio (modo claro) */
        transition: color 0.3s;
    }

    body.dark-mode .hos-date {
        color: #adb5bd; /* gris claro (modo oscuro, legible) */
    }

    body.dark-mode h2 {
        color: #f1f3f5;
    }
</style>
@endsection

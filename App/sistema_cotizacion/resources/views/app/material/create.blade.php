@extends('layouts.app')

@section('title', 'Registrar Material')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Registrar Nuevo Material</h4>
            <a href="{{ route('materiales.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('materiales.store') }}" method="POST">
                @csrf

                {{-- Nombre del material --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre del Material</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Vidrio Templado, Espejo, Laminado" required>
                </div>

                {{-- Espesor --}}
                <div class="mb-3">
                    <label for="espesor" class="form-label fw-bold">Espesor (mm)</label>
                    <input type="number" step="0.1" class="form-control" id="espesor" name="espesor" placeholder="Ej: 4.0" required>
                </div>

                {{-- Valor por metro cuadrado --}}
                <div class="mb-3">
                    <label for="valor_m2" class="form-label fw-bold">Valor por m² ($)</label>
                    <input type="number" step="0.01" class="form-control" id="valor_m2" name="valor_m2" placeholder="Ej: 18500" required>
                    <small class="text-muted">Este valor se usa para calcular el precio según las dimensiones del vidrio.</small>
                </div>

                {{-- Descripción opcional --}}
                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-bold">Descripción (opcional)</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Detalles sobre el tipo de material o uso recomendado..."></textarea>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
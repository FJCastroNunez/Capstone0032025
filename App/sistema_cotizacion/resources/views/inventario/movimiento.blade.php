@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white text-center p-3" style="background:#0B52A0; border-radius:8px;">
        Registrar Movimiento: {{ $material->nombre }}
    </h2>

    <div class="card shadow-lg mt-4 mb-5">
        <div class="card-body">

            <form action="{{ route('inventario.movimiento.guardar', $material->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Tipo de Movimiento</label>
                    <select name="tipo_movimiento" class="form-select" required>
                        <option value="">-- Seleccione --</option>
                        <option value="ENTRADA">Entrada</option>
                        <option value="SALIDA">Salida</option>
                        <option value="AJUSTE">Ajuste</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" required min="1">
                </div>

                <div class="mb-3">
                    <label>Motivo (opcional)</label>
                    <input type="text" name="motivo" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Referencia (opcional)</label>
                    <input type="text" name="referencia" class="form-control" placeholder="Ej: Compra #10">
                </div>

                <button class="btn btn-primary">Guardar Movimiento</button>
                <a href="{{ route('inventario.detalle', $material->id) }}"
                    class="btn btn-secondary">
                    Volver
                </a>

            </form>

        </div>
    </div>

</div>

@endsection
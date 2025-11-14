@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white text-center p-3" style="background:#0B52A0; border-radius:8px;">
        Detalle de Material: {{ $material->nombre }}
    </h2>

    {{-- ALERTAS --}}
    @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <!-- INFORMACIÓN GENERAL -->
    <div class="card shadow-lg mt-4">
        <div class="card-body">

            <h4>Información de Stock</h4>

            <table class="table table-bordered mt-3">
                <tr>
                    <th>Stock Actual</th>
                    <td>{{ $material->stock->stock_actual ?? 0 }}</td>
                </tr>
                <tr>
                    <th>Stock Mínimo</th>
                    <td>{{ $material->stock->stock_minimo ?? 0 }}</td>
                </tr>
                <tr>
                    <th>Ubicación</th>
                    <td>{{ $material->stock->ubicacion ?? 'N/A' }}</td>
                </tr>
            </table>

            <!-- BOTÓN REGISTRAR MOVIMIENTO -->
            <a href="{{ route('inventario.movimiento.form', $material->id) }}"
                class="btn btn-primary mt-3">
                Registrar Movimiento
            </a>

        </div>
    </div>

    <!-- CONFIGURACIÓN -->
    <div class="card shadow-lg mt-4">
        <div class="card-body">

            <h4>Configurar Stock</h4>

            <form action="{{ route('inventario.config.guardar', $material->id) }}" method="POST">
                @csrf

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Stock Mínimo</label>
                        <input type="number" name="stock_minimo"
                            class="form-control"
                            value="{{ $material->stock->stock_minimo ?? 0 }}">
                    </div>

                    <div class="col-md-4">
                        <label>Ubicación</label>
                        <input type="text" name="ubicacion"
                            class="form-control"
                            value="{{ $material->stock->ubicacion ?? '' }}">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-success">Guardar Cambios</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- MOVIMIENTOS -->
    <div class="card shadow-lg mt-4 mb-5">
        <div class="card-body">

            <h4>Movimientos de Stock</h4>

            <table class="table table-striped mt-3">
                <thead style="background:#DCEBFA;">
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Referencia</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($material->movimientos as $mov)
                    <tr>
                        <td>{{ $mov->fecha_movimiento }}</td>
                        <td>
                            @if ($mov->tipo_movimiento === 'ENTRADA')
                            <span class="badge bg-success">Entrada</span>
                            @elseif ($mov->tipo_movimiento === 'SALIDA')
                            <span class="badge bg-danger">Salida</span>
                            @else
                            <span class="badge bg-warning">Ajuste</span>
                            @endif
                        </td>

                        <td>{{ $mov->cantidad }}</td>
                        <td>{{ $mov->motivo ?? '-' }}</td>
                        <td>{{ $mov->referencia ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection
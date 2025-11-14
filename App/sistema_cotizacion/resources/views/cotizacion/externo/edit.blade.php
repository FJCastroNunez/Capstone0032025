@extends('layouts.app')

@section('title', 'Editar Cotización')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Editar Cotización #{{ $cotizacion->id_cotizacion }}</h4>
            <a href="{{ route('cotizaciones.visitas') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card-body">

            {{-- Mensajes de error --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Formulario --}}
            <form action="{{ route('cotizaciones.update', $cotizacion->id_cotizacion) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Cliente Externo</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre completo</label>
                                <input type="text" class="form-control"
                                    name="cliente_json[nombre]"
                                    value="{{ $cotizacion->InvitadoCliente['nombre'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Correo electrónico</label>
                                <input type="email" class="form-control"
                                    name="cliente_json[email]"
                                    value="{{ $cotizacion->InvitadoCliente['email'] ?? '' }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Teléfono</label>
                                <input type="text" class="form-control"
                                    name="cliente_json[telefono]"
                                    value="{{ $cotizacion->InvitadoCliente['telefono'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Dirección</label>
                                <input type="text" class="form-control"
                                    name="cliente_json[direccion]"
                                    value="{{ $cotizacion->InvitadoCliente['direccion'] ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Información general --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-bold">Estado de la Cotización</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="1" {{ $cotizacion->estado == 1  ? 'selected' : '' }}>Pendiente</option>
                            <option value="2" {{ $cotizacion->estado == 2 ? 'selected' : '' }}>Aprobada</option>
                            <option value="3" {{ $cotizacion->estado == 3 ? 'selected' : '' }}>Rechazada</option>
                            <option value="4" {{ $cotizacion->estado == 4 ? 'selected' : '' }}>Facturada</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" class="form-control" value="{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="col-md-4 d-flex align-items-end justify-content-end">
                        <button type="submit" class="btn btn-primary w-100 w-md-auto">
                            <i class="bi bi-save"></i> Guardar cambios
                        </button>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Subtotal (CLP)</label>
                        <input type="text" class="form-control"
                            value="{{ number_format($cotizacion->subtotal ?? 0, 0, ',', '.') }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">IVA (19%)</label>
                        @php
                        $iva = isset($cotizacion->subtotal) ? $cotizacion->subtotal * 0.19 : 0;
                        @endphp
                        <input type="text" class="form-control"
                            value="{{ number_format($iva, 0, ',', '.') }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Final (CLP)</label>
                        <input type="text" class="form-control fw-bold text-success"
                            value="{{ number_format($cotizacion->total ?? 0, 0, ',', '.') }}" readonly>
                    </div>
                </div>
        </div>



        {{-- 🔹 Detalle de productos / materiales --}}
        <div class="mb-4">
            <h5 class="fw-bold mb-3">Detalle de la Cotización</h5>
            @if ($cotizacion->detalles->isEmpty())
            <p class="text-muted">No hay detalles registrados en esta cotización.</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Material</th>
                            <th>Ancho (m)</th>
                            <th>Alto (m)</th>
                            <th>Espesor (mm)</th>
                            <th>Valor m² ($)</th>
                            <th>Subtotal ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotizacion->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre ?? '—' }}</td>
                            <td>{{ $detalle->material->nombre ?? '—' }}</td>
                            <td>{{ number_format($detalle->ancho, 2, ',', '.') }}</td>
                            <td>{{ number_format($detalle->alto, 2, ',', '.') }}</td>
                            <td>{{ number_format($detalle->espesor, 2, ',', '.') }}</td>
                            <td>${{ number_format($detalle->valor_m2, 0, ',', '.') }}</td>
                            <td>${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Archivos adjuntos --}}
        <div class="mb-4">
            <h5 class="fw-bold">Archivos Adjuntos</h5>
            @if ($archivos->isEmpty())
            <p class="text-muted">No hay archivos adjuntos para esta cotización.</p>
            @else
            <ul class="list-group">
                @foreach ($archivos as $archivo)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-paperclip me-2 text-secondary"></i>
                        {{ $archivo->nombre_archivo }}
                    </span>
                    <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-box-arrow-up-right"></i> Ver
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-end">

        </div>
        </form>
        {{-- 🔹 Enviar cotización por correo --}}
        <hr class="my-4">

        <div class="card mt-3 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-envelope-fill me-1"></i> Enviar cotización por correo
            </div>
            <div class="card-body">
                <form action="{{ route('cotizaciones.enviar', $cotizacion->id_cotizacion) }}" method="POST" class="row g-2 align-items-center">
                    @csrf
                    <div class="col-md-8">
                        <input type="email" name="email" class="form-control"
                            placeholder="correo@cliente.cl"
                            value="{{ old('email', $cotizacion->cliente->email ?? '') }}" required>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-send"></i> Enviar correo
                        </button>
                    </div>
                </form>
                @if($cotizacion->estado != 4)
                <form action="{{ route('cotizacion.facturar', $cotizacion->id) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    <button class="btn btn-success"
                        onclick="return confirm('¿Deseas facturar esta cotización? Se descontará stock de los materiales.')">
                        Facturar Cotización
                    </button>

                </form>
                @endif
                {{-- Mensajes de éxito o error --}}
                @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
</div>
<script>
    fetch('{{ route("cotizacion.marcarVista", $cotizacion->id_cotizacion) }}')
        .then(res => console.log('Cotización marcada como vista'));
</script>
@endsection
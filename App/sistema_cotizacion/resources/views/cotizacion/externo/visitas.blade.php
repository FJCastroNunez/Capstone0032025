@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Cotizaciones Externas</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaCotizaciones" class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotizaciones as $cotizacion)
                        @php
                        $visto = $cotizacion->datosCliente['visto'] ?? 0;

                        // 🎨 Color de fondo según valor "visto"
                        // 1 = azul (sin abrir), 2 = verde (abierta), otro = blanco
                        $colorFila = match($visto) {
                        1 => '#CFE2FF', // azul suave
                        2 => '#D1E7DD', // verde suave
                        default => '#FFFFFF', // blanco
                        };
                        @endphp

                        <tr style="background-color: {{ $colorFila }};">
                            <td>{{ $cotizacion->id_cotizacion }}</td>
                            <td>{{ $cotizacion->InvitadoCliente['nombre'] ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</td>
                            <td>${{ number_format($cotizacion->total, 0, ',', '.') }}</td>
                            <td>
                                @if ($cotizacion->estado == 1)
                                <span class="badge bg-success">Pendiente</span>
                                @elseif ($cotizacion->estado == 2)
                                <span class="badge bg-success">Aprobado</span>
                                @elseif ($cotizacion->estado == 3)
                                <span class="badge bg-danger">Rechazada</span>
                                @elseif ($cotizacion->estado == 4)
                                <span class="badge bg-secondary">Facturada</span>
                                @else
                                <span class="badge bg-danger">Anulada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('cotizaciones.descargar.externo', $cotizacion->id_cotizacion) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm" title="Descargar PDF">
                                    <i class="bi bi-download"></i>
                                </a>

                                <a href="{{ route('cotizaciones.edit.externo', $cotizacion->id_cotizacion) }}"
                                    class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- JS de DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaCotizaciones').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            pageLength: 10,
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    orderable: false,
                    targets: 5
                } // desactiva el orden en la columna "Acciones"
            ]
        });
    });
</script>
@endsection
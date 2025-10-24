@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Listado de Cotizaciones</h4>
            <a href="{{ route('cotizaciones.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Nueva Cotización
            </a>
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
                        <tr>
                            <td>{{ $cotizacion->id_cotizacion }}</td>
                            <td>{{ $cotizacion->cliente->nombre ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</td>
                            <td>${{ number_format($cotizacion->total, 0, ',', '.') }}</td>
                            <td>
                                @if ($cotizacion->estado == 'Aprobada')
                                <span class="badge bg-success">Aprobada</span>
                                @elseif ($cotizacion->estado == 'Rechazada')
                                <span class="badge bg-danger">Rechazada</span>
                                @else
                                <span class="badge bg-secondary">Pendiente</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('cotizaciones.show', $cotizacion->id_cotizacion) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('cotizaciones.edit', $cotizacion->id_cotizacion) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('cotizaciones.destroy', $cotizacion->id_cotizacion) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta cotización?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
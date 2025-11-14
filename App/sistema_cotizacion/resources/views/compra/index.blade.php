@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white p-3 mb-4 text-center" style="background:#0B52A0; border-radius:8px;">
        Compras Registradas
    </h2>

    {{-- ALERTAS --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- BOTÓN CREAR COMPRA --}}
    <div class="mb-3 text-end">
        <a href="{{ route('compras.create') }}" class="btn btn-primary">
            + Registrar Nueva Compra
        </a>
    </div>


    <div class="card shadow-lg">
        <div class="card-body">

            @if ($compras->count() === 0)
            <div class="alert alert-info text-center">
                No hay compras registradas todavía.
            </div>
            @else

            <table class="table table-striped table-bordered align-middle">
                <thead style="background:#DCEBFA;">
                    <tr>
                        <th width="10%">ID</th>
                        <th width="30%">Proveedor</th>
                        <th width="15%">Fecha</th>
                        <th width="15%">Documento</th>
                        <th width="15%">Total</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($compras as $compra)
                    <tr>
                        <td>{{ $compra->id_compra }}</td>

                        <td>{{ $compra->proveedor->nombre }}</td>

                        <td>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</td>

                        <td>{{ $compra->numero_documento ?? '—' }}</td>

                        <td>${{ number_format($compra->total, 0, ',', '.') }}</td>

                        <td class="text-center">
                            <a href="{{ route('compras.show', $compra->id_compra) }}"
                                class="btn btn-sm btn-info">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            {{-- PAGINACIÓN (si la agregas en el controller) --}}
            <div class="mt-3">
                {{ $compras->links() }}
            </div>

            @endif

        </div>
    </div>

</div>

@endsection
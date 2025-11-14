@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white p-3 mb-4 text-center" style="background:#0B52A0; border-radius:8px;">
        Detalle de Compra #{{ $compra->id_compra }}
    </h2>

    {{-- BOTÓN VOLVER --}}
    <div class="mb-3">
        <a href="{{ route('compras.index') }}" class="btn btn-secondary">
            ← Volver al listado
        </a>
    </div>

    <div class="card shadow-lg">
        <div class="card-body">

            {{-- INFORMACIÓN DE LA COMPRA --}}
            <h4 class="mb-3">Información de la Compra</h4>

            <table class="table table-bordered">
                <tr>
                    <th width="25%">Proveedor</th>
                    <td>{{ $compra->proveedor->nombre }}</td>
                </tr>

                <tr>
                    <th>Fecha</th>
                    <td>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</td>
                </tr>

                <tr>
                    <th>Documento</th>
                    <td>{{ $compra->numero_documento ?? '—' }}</td>
                </tr>

                <tr>
                    <th>Total</th>
                    <td><strong>${{ number_format($compra->total, 0, ',', '.') }}</strong></td>
                </tr>
            </table>


            {{-- DETALLE DE MATERIALES --}}
            <h4 class="mt-4">Materiales Comprados</h4>

            <table class="table table-striped table-bordered mt-3">
                <thead style="background:#DCEBFA;">
                    <tr>
                        <th>Material</th>
                        <th width="10%">Cantidad</th>
                        <th width="15%">P. Unitario</th>
                        <th width="15%">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($compra->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->material->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                        <td><strong>${{ number_format($detalle->subtotal, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total:</th>
                        <th class="text-end">
                            ${{ number_format($compra->total, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>


            {{-- OPCIONAL: INFO DE STOCK --}}
            <h5 class="mt-4">Movimiento de Inventario</h5>
            <p class="text-muted">
                Todos los materiales listados aquí ya fueron ingresados automáticamente al inventario.
            </p>

        </div>
    </div>

</div>

@endsection
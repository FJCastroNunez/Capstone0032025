@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="text-white text-center p-3" style="background:#0B52A0; border-radius:8px;">
        Inventario de Materiales
    </h1>

    <div class="card shadow-lg mt-4">
        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">
                <thead style="background:#DCEBFA;">
                    <tr>
                        <th>Material</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($materiales as $mat)
                    @php
                    $actual = $mat->stock->stock_actual ?? 0;
                    $min = $mat->stock->stock_minimo ?? 0;
                    $estado = $actual <= $min ? 'Bajo' : 'OK' ;
                        @endphp

                        <tr>
                        <td>{{ $mat->nombre }}</td>

                        <td>{{ $actual }}</td>
                        <td>{{ $min }}</td>

                        <td>
                            @if ($estado === 'Bajo')
                            <span class="badge bg-danger">Stock Bajo</span>
                            @else
                            <span class="badge bg-success">OK</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('inventario.detalle', $mat->id) }}"
                                class="btn btn-primary btn-sm">
                                Ver Detalles
                            </a>
                        </td>
                        </tr>

                        @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection
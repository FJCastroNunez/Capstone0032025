@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white p-3 mb-4 text-center" style="background:#0B52A0; border-radius:8px;">
        Proveedores Registrados
    </h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="text-end mb-3">
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
            + Nuevo Proveedor
        </a>
    </div>

    <div class="card shadow-lg">
        <div class="card-body">

            @if($proveedores->count() == 0)
            <div class="alert alert-info text-center">No hay proveedores registrados.</div>
            @else

            <table class="table table-striped table-bordered">
                <thead style="background:#DCEBFA;">
                    <tr>
                        <th>Nombre</th>
                        <th>RUT</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($proveedores as $p)
                    <tr>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->rut ?? '—' }}</td>
                        <td>{{ $p->telefono ?? '—' }}</td>
                        <td>{{ $p->email ?? '—' }}</td>
                        <td>{{ $p->direccion ?? '—' }}</td>

                        <td class="text-center">
                            <a href="{{ route('proveedores.edit', $p->id_proveedor) }}" class="btn btn-sm btn-info">
                                Editar
                            </a>

                            <form action="{{ route('proveedores.destroy', $p->id_proveedor) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('¿Eliminar proveedor?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">X</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @endif

        </div>
    </div>

</div>

@endsection
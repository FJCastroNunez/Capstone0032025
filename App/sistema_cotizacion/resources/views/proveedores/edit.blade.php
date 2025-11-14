@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white p-3 mb-4 text-center" style="background:#0B52A0; border-radius:8px;">
        Editar Proveedor
    </h2>

    <div class="card shadow-lg">
        <div class="card-body">

            <form action="{{ route('proveedores.update', $proveedor->id_proveedor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label><strong>Nombre</strong></label>
                    <input type="text" name="nombre" class="form-control" value="{{ $proveedor->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label><strong>RUT</strong></label>
                    <input type="text" name="rut" class="form-control" value="{{ $proveedor->rut }}">
                </div>

                <div class="mb-3">
                    <label><strong>Teléfono</strong></label>
                    <input type="text" name="telefono" class="form-control" value="{{ $proveedor->telefono }}">
                </div>

                <div class="mb-3">
                    <label><strong>Email</strong></label>
                    <input type="email" name="email" class="form-control" value="{{ $proveedor->email }}">
                </div>

                <div class="mb-3">
                    <label><strong>Dirección</strong></label>
                    <input type="text" name="direccion" class="form-control" value="{{ $proveedor->direccion }}">
                </div>

                <button class="btn btn-success w-100">Actualizar</button>
            </form>

        </div>
    </div>

</div>

@endsection
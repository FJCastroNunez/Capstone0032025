@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white p-3 mb-4 text-center" style="background:#0B52A0; border-radius:8px;">
        Registrar Nuevo Proveedor
    </h2>

    <div class="card shadow-lg">
        <div class="card-body">

            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label><strong>Nombre</strong></label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label><strong>RUT</strong></label>
                    <input type="text" name="rut" class="form-control">
                </div>

                <div class="mb-3">
                    <label><strong>Teléfono</strong></label>
                    <input type="text" name="telefono" class="form-control">
                </div>

                <div class="mb-3">
                    <label><strong>Email</strong></label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label><strong>Dirección</strong></label>
                    <input type="text" name="direccion" class="form-control">
                </div>

                <button class="btn btn-success w-100">Guardar</button>
            </form>

        </div>
    </div>

</div>

@endsection
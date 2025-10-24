@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Registrar nuevo cliente</h1>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST">
                @csrf
                @method('PUT')
                <h2 class="mb-3">Datos del Cliente</h2>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ $cliente->nombre }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="rut" class="form-label">RUT Empresa</label>
                    <input type="text" name="empresa" id="empresa" value="{{ $cliente->empresa }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" name="correo" id="correo" value="{{ $cliente->correo}}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ $cliente->telefono }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ $cliente->direccion }}" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        ← Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        Guardar cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
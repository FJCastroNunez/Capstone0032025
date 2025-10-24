@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Registrar nuevo producto</h1>

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
            <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST">
                @csrf
                @method('PUT')
                <h2 class="mb-3">Datos del producto</h2>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ $producto->nombre }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion</label>
                    <input type="text" name="descripcion" id="descripcion" value="{{ $producto->descripcion }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="email" name="precio" id="precio" value="{{ $producto->precio }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="unidad" class="form-label">Unidad</label>
                    <input type="text" name="unidad" id="unidad" value="{{ $producto->unidad }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="text" name="stock" id="stock" value="{{ $producto->stock }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="activo" class="form-label">Activo</label>
                    <select name="activo" id="activo" class="form-select" required>
                        <option value="1" {{ $producto->activo == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ $producto->activo == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        ← Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        Guardar producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
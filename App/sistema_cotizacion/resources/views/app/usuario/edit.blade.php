@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Editar usuario</h4>
        </div>
        <div class="card-body">

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

            <form action="{{ route('usuarios.update', $usuario->id_usuario) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="{{ $usuario->nombre }}" class="form-control" required>
                </div>

                {{-- Correo --}}
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico:</label>
                    <input type="email" name="correo" id="correo" value="{{ $usuario->email }}" class="form-control" required>
                </div>

                {{-- Contraseña --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Dejar en blanco si no se cambia">
                </div>

                {{-- Rol --}}
                <div class="mb-3">
                    <label for="rol" class="form-label">Perfil:</label>
                    <select name="rol" id="rol" class="form-select" required>
                        <option value="">-- Selecciona un perfil --</option>
                        <option value="1" {{ $usuario->rol == 1 ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ $usuario->rol == 2 ? 'selected' : '' }}>Vendedor</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
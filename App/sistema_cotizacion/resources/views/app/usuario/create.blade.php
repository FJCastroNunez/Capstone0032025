@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear nuevo usuario</h1>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" class="form-control" required>
        </div>

        <div>
            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo') }}" required>
        </div>

        <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div>
            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <div>
            <label for="rol">Perfil:</label>
            <select name="rol" id="rol" class="form-select" required>
                <option value="">-- Selecciona un perfil --</option>
                <option value="1">Administrador</option>
                <option value="2">Vendedor</option>
            </select>
        </div>
        <br>
        <div id="vista-previa-contrato" style="margin-top: 10px;"></div>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            ← Volver
        </a>
        <button type="submit" class="btn btn-primary">Guardar usuario</button>
    </form>
</div>
@endsection
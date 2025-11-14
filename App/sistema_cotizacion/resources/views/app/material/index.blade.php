@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de materiales</h1>

    {{-- Botón para registrar nuevo material --}}
    <a href="{{ route('materiales.create') }}" class="btn btn-success mb-3">
        + Registrar material
    </a>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabla dinámica --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabla-materiales" class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Espesor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($materiales as $material)
                    <tr>
                        <td>{{ $material->id }}</td>
                        <td>{{ $material->nombre }}</td>
                        <td>{{ $material->espesor }}</td>
                        <td>
                            <a href="{{ route('materiales.edit', $material->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay materiales registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#tabla-materiales').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            },
            responsive: true,
            autoWidth: false
        });
    });
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Nueva Cotización</h4>
        </div>
        <div class="card-body">
            {{-- Mostrar errores --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Formulario principal --}}
            {{-- ✅ Se agrega enctype para permitir subir archivos --}}
            <form action="{{ route('cotizaciones.store') }}" method="POST" id="formCotizacion" enctype="multipart/form-data">
                @csrf

                {{-- Cliente --}}
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">Cliente:</label>
                    <select name="id_cliente" id="id_cliente" class="form-select" required>
                        <option value="">-- Selecciona un cliente --</option>
                        @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha --}}
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- Sección de productos --}}
                <h5 class="mt-4 mb-2">Productos</h5>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th style="width:120px;">Cantidad</th>
                            <th style="width:150px;">Precio Unitario</th>
                            <th style="width:150px;">Subtotal</th>
                            <th style="width:60px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProductos">
                        <tr>
                            <td>
                                <select name="productos[0][id_producto]" class="form-select producto" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach ($productos as $producto)
                                    <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio }}">
                                        {{ $producto->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="productos[0][cantidad]" class="form-control cantidad" min="1" value="1" required></td>
                            <td><input type="number" name="productos[0][precio_unitario]" class="form-control precio" step="0.01" required></td>
                            <td><input type="text" class="form-control subtotal" readonly></td>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm eliminarFila"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Botón para agregar producto --}}
                <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="agregarProducto">
                    <i class="bi bi-plus-circle"></i> Agregar producto
                </button>

                {{-- Total --}}
                <div class="d-flex justify-content-end">
                    <h5>Total: $<span id="totalCotizacion">0</span></h5>
                </div>

                {{-- ✅ Sección para adjuntar archivos --}}
                <div class="mt-4 mb-3">
                    <h5>Archivos Adjuntos</h5>
                    <small class="text-muted d-block mb-2">Puedes subir imágenes, documentos PDF o archivos Excel (máx. 10 MB cada uno).</small>
                    <input type="file" name="adjuntos[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.pdf,.docx,.xlsx">
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Cotización
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script para manejar dinámicamente los productos --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let contador = 1;

        const tabla = document.getElementById('tablaProductos');
        const btnAgregar = document.getElementById('agregarProducto');
        const totalSpan = document.getElementById('totalCotizacion');

        // Función para actualizar totales
        function actualizarTotales() {
            let total = 0;
            document.querySelectorAll('#tablaProductos tr').forEach((fila) => {
                const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
                const precio = parseFloat(fila.querySelector('.precio').value) || 0;
                const subtotal = cantidad * precio;
                fila.querySelector('.subtotal').value = subtotal.toFixed(2);
                total += subtotal;
            });
            totalSpan.textContent = total.toFixed(2);
        }

        // Agregar nueva fila de producto
        btnAgregar.addEventListener('click', () => {
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
            <td>
                <select name="productos[${contador}][id_producto]" class="form-select producto" required>
                    <option value="">-- Selecciona --</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio }}">
                            {{ $producto->nombre }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="productos[${contador}][cantidad]" class="form-control cantidad" min="1" value="1" required></td>
            <td><input type="number" name="productos[${contador}][precio_unitario]" class="form-control precio" step="0.01" required></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm eliminarFila"><i class="bi bi-trash"></i></button></td>
        `;
            tabla.appendChild(nuevaFila);
            contador++;
        });

        // Escuchar cambios en cantidad o precio
        tabla.addEventListener('input', (e) => {
            if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
                actualizarTotales();
            }
        });

        // Eliminar fila
        tabla.addEventListener('click', (e) => {
            if (e.target.closest('.eliminarFila')) {
                e.target.closest('tr').remove();
                actualizarTotales();
            }
        });

        // Auto-cargar precio del producto seleccionado
        tabla.addEventListener('change', (e) => {
            if (e.target.classList.contains('producto')) {
                const precio = e.target.selectedOptions[0].getAttribute('data-precio');
                const fila = e.target.closest('tr');
                fila.querySelector('.precio').value = precio;
                actualizarTotales();
            }
        });
    });
</script>
@endsection
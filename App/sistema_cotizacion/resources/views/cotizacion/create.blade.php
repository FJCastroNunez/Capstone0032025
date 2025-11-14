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
                <h5 class="mt-4 mb-2">Productos / Materiales</h5>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Material</th>
                            <th>Ancho (cm)</th>
                            <th>Alto (cm)</th>
                            <th>Espesor (mm)</th>
                            <th>Valor m² ($)</th>
                            <th>Subtotal ($)</th>
                            <th style="width:60px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProductos">
                        <tr>
                            <td>
                                <select name="productos[0][id_producto]" class="form-select producto" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach ($productos as $producto)
                                    <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="productos[0][id_material]" class="form-select material" required>
                                    <option value="">-- Selecciona material --</option>
                                    @foreach ($materiales as $mat)
                                    <option value="{{ $mat->id }}"
                                        data-espesor="{{ $mat->espesor }}"
                                        data-valor="{{ $mat->valor_m2 }}">
                                        {{ $mat->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="productos[0][ancho]" class="form-control ancho" step="0.01" min="1" required></td>
                            <td><input type="number" name="productos[0][alto]" class="form-control alto" step="0.01" min="1" required></td>
                            <td><input type="number" name="productos[0][espesor]" class="form-control espesor" step="0.1" readonly></td>
                            <td><input type="number" name="productos[0][valor_m2]" class="form-control valor_m2" readonly></td>
                            <td><input type="text" name="productos[0][subtotal]" class="form-control subtotal" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm eliminarFila">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Botón para agregar producto --}}
                <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="agregarProducto">
                    <i class="bi bi-plus-circle"></i> Agregar línea
                </button>

                {{-- Totales --}}
                <div class="d-flex flex-column align-items-end mt-3">
                    <h6>Subtotal: <span id="subtotalCotizacion">0</span></h6>
                    <h6>IVA (19%): <span id="ivaCotizacion">0</span></h6>
                    <h5>Total Final: <span id="totalCotizacion">0</span></h5>
                </div>

                {{-- Inputs ocultos para totales --}}
                <input type="hidden" name="subtotal" id="subtotal">
                <input type="hidden" name="impuestos" id="impuestos">
                <input type="hidden" name="total" id="total">

                {{-- Archivos Adjuntos --}}
                <div class="mt-4 mb-3">
                    <h5>Archivos Adjuntos</h5>
                    <small class="text-muted d-block mb-2">Puedes subir imágenes, PDF o Excel (máx. 10 MB cada uno).</small>
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

{{-- Script dinámico --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let contador = 0;

        const tabla = document.getElementById('tablaProductos');
        const btnAgregar = document.getElementById('agregarProducto');

        // 🔹 Función para actualizar totales
        function actualizarTotales() {
            let subtotal = 0;
            document.querySelectorAll('#tablaProductos tr').forEach(fila => {
                const ancho = parseFloat(fila.querySelector('.ancho').value) || 0;
                const alto = parseFloat(fila.querySelector('.alto').value) || 0;
                const valor = parseFloat(fila.querySelector('.valor_m2').value) || 0;
                const subtotalFila = ancho * alto * valor;
                fila.querySelector('.subtotal').value = subtotalFila.toFixed(2);
                subtotal += subtotalFila;
            });

            const iva = subtotal * 0.19;
            const total = subtotal + iva;

            document.getElementById('subtotalCotizacion').textContent = subtotal.toLocaleString('es-CL');
            document.getElementById('ivaCotizacion').textContent = iva.toLocaleString('es-CL');
            document.getElementById('totalCotizacion').textContent = total.toLocaleString('es-CL');

            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('impuestos').value = iva.toFixed(2);
            document.getElementById('total').value = total.toFixed(2);
        }

        // 🔹 Agregar nueva fila
        btnAgregar.addEventListener('click', () => {
            contador++;
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
            <td>
                <select name="productos[${contador}][id_producto]" class="form-select producto" required>
                    <option value="">-- Selecciona producto --</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="productos[${contador}][id_material]" class="form-select material" required>
                    <option value="">-- Selecciona material --</option>
                    @foreach ($materiales as $mat)
                        <option value="{{ $mat->id }}" data-espesor="{{ $mat->espesor }}" data-valor="{{ $mat->valor_m2 }}">
                            {{ $mat->nombre }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" step="0.01" name="productos[${contador}][ancho]" class="form-control ancho" placeholder="Ancho (m)" required></td>
            <td><input type="number" step="0.01" name="productos[${contador}][alto]" class="form-control alto" placeholder="Alto (m)" required></td>
            <td><input type="number" step="0.01" name="productos[${contador}][espesor]" class="form-control espesor" placeholder="Espesor (mm)" readonly></td>
            <td><input type="number" step="0.01" name="productos[${contador}][valor_m2]" class="form-control valor_m2" placeholder="Valor m² ($)" readonly></td>
            <td><input type="text" class="form-control subtotal" name="productos[${contador}][subtotal]" readonly></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm eliminarFila"><i class="bi bi-trash"></i></button></td>
        `;
            tabla.appendChild(nuevaFila);
        });

        // 🔹 Actualizar valores cuando se selecciona material
        tabla.addEventListener('change', e => {
            if (e.target.classList.contains('material')) {
                const option = e.target.selectedOptions[0];
                const fila = e.target.closest('tr');
                fila.querySelector('.espesor').value = option.getAttribute('data-espesor');
                fila.querySelector('.valor_m2').value = option.getAttribute('data-valor');
                actualizarTotales();
            }
        });

        // 🔹 Actualizar totales al cambiar medidas
        tabla.addEventListener('input', e => {
            if (e.target.classList.contains('ancho') || e.target.classList.contains('alto')) {
                actualizarTotales();
            }
        });

        // 🔹 Eliminar fila
        tabla.addEventListener('click', e => {
            if (e.target.closest('.eliminarFila')) {
                e.target.closest('tr').remove();
                actualizarTotales();
            }
        });


    });
</script>

@endsection
@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white p-3 mb-4 text-center" style="background:#0B52A0; border-radius:8px;">
        Registrar Compra a Proveedor
    </h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Error:</strong> Revisa los campos.
        <ul class="mt-2 mb-0">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <div class="card shadow-lg">
        <div class="card-body">

            <form action="{{ route('compras.store') }}" method="POST">
                @csrf

                {{-- DATOS DE LA COMPRA --}}
                <h4 class="mb-3">Datos de la Compra</h4>

                <div class="row mb-3">

                    {{-- PROVEEDOR --}}
                    <div class="col-md-6">
                        <label class="form-label"><strong>Proveedor</strong></label>
                        <select name="id_proveedor" class="form-select" required>
                            <option value="">-- Selecciona un proveedor --</option>
                            @foreach($proveedores as $prov)
                            <option value="{{ $prov->id_proveedor }}">
                                {{ $prov->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- FECHA --}}
                    <div class="col-md-3">
                        <label class="form-label"><strong>Fecha</strong></label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>

                    {{-- DOCUMENTO --}}
                    <div class="col-md-3">
                        <label class="form-label"><strong>N° Documento</strong></label>
                        <input type="text" name="numero_documento" class="form-control">
                    </div>
                </div>


                {{-- TABLA DE MATERIALES --}}
                <h4 class="mt-4">Materiales Comprados</h4>

                <table class="table table-bordered mt-3" id="tabla-materiales">
                    <thead style="background:#DCEBFA;">
                        <tr>
                            <th width="35%">Material</th>
                            <th width="15%">Cantidad</th>
                            <th width="20%">Precio Unitario</th>
                            <th width="20%">Subtotal</th>
                            <th width="10%">Acción</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>

                <button type="button" class="btn btn-primary mt-2" id="btnAgregar">
                    + Agregar Material
                </button>


                {{-- TOTAL --}}

                <div class="mt-4 text-end">
                    <h4>Total Compra: <span id="totalCompra">$0</span></h4>
                </div>

                <button type="submit" class="btn btn-success mt-4 w-100">
                    Guardar Compra
                </button>

            </form>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    let materiales = @json($materiales);
    let fila = 0;

    document.getElementById('btnAgregar').addEventListener('click', agregarFila);

    function agregarFila() {
        const tabla = document.querySelector('#tabla-materiales tbody');

        let opciones = materiales.map(m =>
            `<option value="${m.id}">${m.nombre}</option>`
        ).join('');

        let html = `
            <tr id="fila_${fila}">
                <td>
                    <select name="materiales[${fila}][id_material]" class="form-select" required>
                        <option value="">-- Selecciona material --</option>
                        ${opciones}
                    </select>
                </td>

                <td>
                    <input type="number" class="form-control cantidad" 
                           name="materiales[${fila}][cantidad]" 
                           min="1" value="1" required>
                </td>

                <td>
                    <input type="number" class="form-control precio" 
                           name="materiales[${fila}][precio_unitario]" 
                           min="0" step="0.01" value="0" required>
                </td>

                <td>
                    <input type="text" class="form-control subtotal" 
                           name="materiales[${fila}][subtotal]" 
                           value="0" readonly>
                </td>

                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(${fila})">
                        X
                    </button>
                </td>
            </tr>
        `;

        tabla.insertAdjacentHTML('beforeend', html);

        fila++;
        actualizarTotales();
    }

    function eliminarFila(num) {
        document.getElementById('fila_' + num).remove();
        actualizarTotales();
    }

    // Actualiza subtotales y total general
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cantidad') ||
            e.target.classList.contains('precio')) {
            actualizarTotales();
        }
    });

    function actualizarTotales() {
        let total = 0;

        document.querySelectorAll('#tabla-materiales tbody tr').forEach(tr => {
            let cantidad = tr.querySelector('.cantidad').value;
            let precio = tr.querySelector('.precio').value;
            let subtotal = cantidad * precio;

            tr.querySelector('.subtotal').value = subtotal.toFixed(2);

            total += subtotal;
        });

        document.getElementById('totalCompra').innerText = '$' + total.toFixed(0);
    }
</script>

@endsection
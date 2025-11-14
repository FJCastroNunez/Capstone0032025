<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización N° {{ $cotizacion->id_cotizacion }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        h1 {
            background-color: #0B52A0;
            color: white;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
        }

        h3 {
            color: #0B52A0;
            border-bottom: 1px solid #0B52A0;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #e8f0fe;
        }

        .totales td {
            border: none;
            text-align: right;
            padding: 5px;
        }

        .footer {
            font-size: 11px;
            margin-top: 25px;
            text-align: center;
            color: #666;
        }

        .cliente-info {
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: #f8faff;
        }

        .cliente-info p {
            margin: 3px 0;
        }

        .cliente-info strong {
            color: #0B52A0;
        }
    </style>
</head>

<body>
    <h1>Cotización N° {{ $cotizacion->id_cotizacion }}</h1>

    {{-- 🔹 Datos del Cliente Visitante --}}
    @php
    $cliente = json_decode($cotizacion->cliente, true);
    @endphp

    <div class="cliente-info">
        <h3>Datos del Cliente</h3>
        <p><strong>Nombre:</strong> {{ $cotizacion->InvitadoCliente['nombre'] ?? 'No especificado' }}</p>
        <p><strong>Correo electrónico:</strong> {{ $cotizacion->InvitadoCliente['email'] ?? 'No especificado' }}</p>
        <p><strong>Teléfono:</strong> {{ $cotizacion->InvitadoCliente['telefono'] ?? 'No especificado' }}</p>
        <p><strong>Dirección:</strong> {{ $cotizacion->InvitadoCliente['direccion'] ?? 'No especificada' }}</p>
        <p><strong>Fecha de cotización:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</p>
    </div>

    {{-- 🔹 Detalle de Cotización --}}
    <h3>Detalle de Cotización</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Material</th>
                <th>Ancho (m)</th>
                <th>Alto (m)</th>
                <th>Espesor (mm)</th>
                <th>Valor m² ($)</th>
                <th>Subtotal ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizacion->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre ?? '—' }}</td>
                <td>{{ $detalle->material->nombre ?? '—' }}</td>
                <td>{{ number_format($detalle->ancho, 2, ',', '.') }}</td>
                <td>{{ number_format($detalle->alto, 2, ',', '.') }}</td>
                <td>{{ number_format($detalle->espesor, 2, ',', '.') }}</td>
                <td>${{ number_format($detalle->valor_m2, 0, ',', '.') }}</td>
                <td>${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 🔹 Observaciones --}}
    @if (!empty($cotizacion->detalle))
    <h3>Observaciones</h3>
    <p>{{ $cotizacion->detalle }}</p>
    @endif

    {{-- 🔹 Adjuntos --}}
    @if ($cotizacion->adjuntos && $cotizacion->adjuntos->count() > 0)
    <p><strong>Nota:</strong> Esta cotización cuenta con archivos adjuntos, los cuales deben descargarse por separado.</p>
    @endif

    {{-- 🔹 Totales --}}
    <table class="totales" style="margin-top: 15px;">
        <tr>
            <td><strong>Subtotal:</strong></td>
            <td>${{ number_format($cotizacion->subtotal ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Impuestos (IVA 19%):</strong></td>
            <td>${{ number_format($cotizacion->impuestos ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total:</strong></td>
            <td><strong>${{ number_format($cotizacion->total ?? 0, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        Este documento tiene una validez de 30 días desde su creación.<br>
        © Vidriería Verónica - Todos los derechos reservados
    </div>
</body>

</html>
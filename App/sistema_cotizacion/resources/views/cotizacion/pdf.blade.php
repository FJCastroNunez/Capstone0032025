<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización N° {{ $cotizacion->id }}</title>
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
    </style>
</head>

<body>
    <h1>Cotización N° {{ $cotizacion->id_cotizacion }}</h1>

    <p><strong>Cliente:</strong> {{ $cotizacion->datosCliente->nombre }}</p>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</p>

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


    {{-- 🔹 Observaciones o detalle adicional --}}
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
            <td>${{ number_format($cotizacion->impuestos, 0, ',', '.') }}</td>
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
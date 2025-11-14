@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-white p-3 mb-4 text-center"
        style="background:#0B52A0; border-radius:8px;">
        📊 Reporte de Cotizaciones por Vendedor
    </h2>
    <div class="mb-3 text-end">
        <a href="{{ route('reportes.cotizaciones_vendedor_pdf', [
        'vendedor' => $vendedor,
        'desde' => $desde,
        'hasta' => $hasta
    ]) }}"
            class="btn btn-danger" target="_blank">
            📄 Exportar a PDF
        </a>
    </div>
    {{-- FILTROS --}}
    <div class="card shadow-lg mb-4">
        <div class="card-body">

            <form method="GET" class="row g-3">

                {{-- VENDEDOR --}}
                <div class="col-md-4">
                    <label><strong>Vendedor</strong></label>
                    <select name="vendedor" class="form-select">
                        <option value="">-- Todos --</option>
                        @foreach($vendedores as $v)
                        <option value="{{ $v->id }}"
                            {{ $vendedor == $v->id ? 'selected' : '' }}>
                            {{ $v->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- DESDE --}}
                <div class="col-md-3">
                    <label><strong>Desde</strong></label>
                    <input type="date" class="form-control"
                        name="desde" value="{{ $desde }}">
                </div>

                {{-- HASTA --}}
                <div class="col-md-3">
                    <label><strong>Hasta</strong></label>
                    <input type="date" class="form-control"
                        name="hasta" value="{{ $hasta }}">
                </div>

                {{-- BOTÓN --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filtrar</button>
                </div>

            </form>

        </div>
    </div>

    {{-- KPIs ESTILO POWER BI TEMA C --}}
    <div class="row text-white mb-4">

        {{-- Total Cotizaciones --}}
        <div class="col-md-3 mb-3">
            <div class="p-4 text-center"
                style="background:#0B52A0; border-radius:15px; box-shadow:0 3px 8px rgba(0,0,0,0.2);">
                <h5>Total Cotizaciones</h5>
                <h2 style="font-size:40px; font-weight:bold;">
                    {{ $totalCotizaciones }}
                </h2>
            </div>
        </div>

        {{-- Total Facturadas --}}
        <div class="col-md-3 mb-3">
            <div class="p-4 text-center"
                style="background:#0A3E7A; border-radius:15px; box-shadow:0 3px 8px rgba(0,0,0,0.2);">
                <h5>Facturadas</h5>
                <h2 style="font-size:40px; font-weight:bold;">
                    {{ $totalFacturadas }}
                </h2>
            </div>
        </div>

        {{-- Monto Cotizado --}}
        <div class="col-md-3 mb-3">
            <div class="p-4 text-center"
                style="background:#09406E; border-radius:15px; box-shadow:0 3px 8px rgba(0,0,0,0.2);">
                <h5>Monto Cotizado</h5>
                <h2 style="font-size:35px; font-weight:bold;">
                    ${{ number_format($montoCotizado,0,',','.') }}
                </h2>
            </div>
        </div>

        {{-- Monto Facturado --}}
        <div class="col-md-3 mb-3">
            <div class="p-4 text-center"
                style="background:#082F57; border-radius:15px; box-shadow:0 3px 8px rgba(0,0,0,0.2);">
                <h5>Monto Facturado</h5>
                <h2 style="font-size:35px; font-weight:bold;">
                    ${{ number_format($montoFacturado,0,',','.') }}
                </h2>
            </div>
        </div>
    </div>


    {{-- TASA DE CONVERSIÓN --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="p-4 text-center text-white"
                style="background:#06304A; border-radius:15px; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
                <h4>Tasa de Conversión</h4>
                <h1 style="font-size:55px; font-weight:bold;">
                    {{ $conversion }}%
                </h1>
            </div>
        </div>
    </div>


    {{-- GRÁFICOS --}}
    <div class="row">

        {{-- Grafico por mes --}}
        <div class="col-md-8 mb-4">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="mb-3">Cotizaciones por Mes</h5>
                    <div id="graficoMeses"></div>
                </div>
            </div>
        </div>

        {{-- Donut por estado --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="mb-3">Estado de Cotizaciones</h5>
                    <div id="graficoEstado"></div>
                </div>
            </div>
        </div>

    </div>


    {{-- TABLA DETALLE --}}
    <div class="card shadow-lg mt-4">
        <div class="card-body">

            <h4 class="mb-3">Detalle de Cotizaciones</h4>

            <table class="table table-bordered table-striped">
                <thead style="background:#DCEBFA;">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($cotizaciones as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $c->cliente->nombre ?? '—' }}</td>
                        <td>${{ number_format($c->total,0,',','.') }}</td>

                        <td>
                            @switch($c->estado)
                            @case(1) Pendiente @break
                            @case(2) Aprobada @break
                            @case(3) Rechazada @break
                            @case(4) Facturada @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>


</div>

{{-- GRÁFICOS APEXCHARTS --}}
<script>
    // Grafico de barras por mes
    var meses = Object.keys(@json($porMes));
    var valores = Object.values(@json($porMes));

    var optionsMes = {
        chart: {
            type: 'bar',
            height: 300
        },
        series: [{
            name: 'Cotizaciones',
            data: valores
        }],
        xaxis: {
            categories: meses
        },
        colors: ['#0B52A0'],
    };

    new ApexCharts(document.querySelector("#graficoMeses"), optionsMes).render();


    // Grafico donut por estado
    var optionsEstado = {
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Pendientes', 'Aprobadas', 'Rechazadas', 'Facturadas'],
        series: [{
                {
                    $porEstado['pendientes']
                }
            },
            {
                {
                    $porEstado['aprobadas']
                }
            },
            {
                {
                    $porEstado['rechazadas']
                }
            },
            {
                {
                    $porEstado['facturadas']
                }
            },
        ],
        colors: ['#FFC107', '#17A2B8', '#DC3545', '#28A745'],
    };

    new ApexCharts(document.querySelector("#graficoEstado"), optionsEstado).render();
</script>

@endsection
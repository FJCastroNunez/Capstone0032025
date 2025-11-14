<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteCotizacionController extends Controller
{
    public function index(Request $request)
    {
        // FILTROS
        $vendedor = $request->vendedor ?? null;
        $desde = $request->desde ?? Carbon::now()->startOfMonth()->toDateString();
        $hasta = $request->hasta ?? Carbon::now()->endOfMonth()->toDateString();

        // Lista de vendedores
        $vendedores = Usuario::orderBy('nombre')->get();

        // Consulta base
        $query = Cotizacion::whereBetween('fecha', [$desde, $hasta]);

        if ($vendedor) {
            $query->where('id_usuario', $vendedor);
        }

        $cotizaciones = $query->get();

        // KPIs
        $totalCotizaciones = $cotizaciones->count();
        $totalFacturadas = $cotizaciones->where('estado', 4)->count();
        $montoCotizado = $cotizaciones->sum('total');
        $montoFacturado = $cotizaciones->where('estado', 4)->sum('total');

        $conversion = ($totalCotizaciones > 0)
            ? round(($totalFacturadas / $totalCotizaciones) * 100, 1)
            : 0;

        // Gráfico por mes
        $porMes = $cotizaciones
            ->groupBy(fn($c) => Carbon::parse($c->fecha)->format('m'))
            ->map->count();

        // Gráfico donut por estado
        $porEstado = [
            'pendientes' => $cotizaciones->where('estado', 1)->count(),
            'aprobadas'  => $cotizaciones->where('estado', 2)->count(),
            'rechazadas' => $cotizaciones->where('estado', 3)->count(),
            'facturadas' => $cotizaciones->where('estado', 4)->count(),
        ];

        return view('reportes.cotizaciones_vendedor', compact(
            'cotizaciones',
            'vendedores',
            'vendedor',
            'desde',
            'hasta',
            'totalCotizaciones',
            'totalFacturadas',
            'montoCotizado',
            'montoFacturado',
            'conversion',
            'porMes',
            'porEstado'
        ));
    }



    // =============================================
    // 🔵 EXPORTAR A PDF
    // =============================================
    public function exportarPDF(Request $request)
    {
        $vendedor = $request->vendedor ?? null;
        $desde = $request->desde;
        $hasta = $request->hasta;

        $query = Cotizacion::whereBetween('fecha', [$desde, $hasta]);

        if ($vendedor) {
            $query->where('id_usuario', $vendedor);
        }

        $cotizaciones = $query->get();

        $totalCotizaciones = $cotizaciones->count();
        $totalFacturadas = $cotizaciones->where('estado', 4)->count();
        $montoCotizado = $cotizaciones->sum('total');
        $montoFacturado = $cotizaciones->where('estado', 4)->sum('total');

        $conversion = ($totalCotizaciones > 0)
            ? round(($totalFacturadas / $totalCotizaciones) * 100, 1)
            : 0;

        $pdf = PDF::loadView('reportes.pdf.cotizaciones_vendedor', [
            'cotizaciones' => $cotizaciones,
            'totalCotizaciones' => $totalCotizaciones,
            'totalFacturadas' => $totalFacturadas,
            'montoCotizado' => $montoCotizado,
            'montoFacturado' => $montoFacturado,
            'conversion' => $conversion,
            'desde' => $desde,
            'hasta' => $hasta,
            'vendedor' => $vendedor,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('reporte_cotizaciones_vendedor.pdf');
    }
}

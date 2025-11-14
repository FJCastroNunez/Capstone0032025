<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\StockMaterial;
use App\Models\MovimientoStockMaterial;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function index()
    {
        $materiales = Material::with('stock')->get();

        return view('inventario.index', compact('materiales'));
    }

    public function detalle($id_material)
    {
        $material = Material::with(['stock', 'movimientos' => function ($q) {
            $q->orderBy('fecha_movimiento', 'desc');
        }])->findOrFail($id_material);

        return view('inventario.detalle', compact('material'));
    }

    public function movimientoForm($id_material)
    {
        $material = Material::findOrFail($id_material);
        return view('inventario.movimiento', compact('material'));
    }

    public function guardarMovimiento(Request $request, $id_material)
    {
        $request->validate([
            'tipo_movimiento' => 'required|in:ENTRADA,SALIDA,AJUSTE',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255',
            'referencia' => 'nullable|string|max:100',
        ]);

        $material = Material::findOrFail($id_material);

        // Obtener o crear stock
        $stock = StockMaterial::firstOrCreate(
            ['id_material' => $material->id],
            ['stock_actual' => 0, 'stock_minimo' => 0]
        );

        // Aplicar el movimiento al stock actual
        if ($request->tipo_movimiento === 'ENTRADA') {
            $stock->stock_actual += $request->cantidad;
        } elseif ($request->tipo_movimiento === 'SALIDA') {

            if ($stock->stock_actual < $request->cantidad) {
                return back()->with('error', 'No hay suficiente stock disponible.');
            }

            $stock->stock_actual -= $request->cantidad;
        } elseif ($request->tipo_movimiento === 'AJUSTE') {
            // Ajuste exacto: stock_actual = cantidad
            $stock->stock_actual = $request->cantidad;
        }

        $stock->save();

        // Registrar movimiento
        MovimientoStockMaterial::create([
            'id_material' => $material->id,
            'tipo_movimiento' => $request->tipo_movimiento,
            'cantidad' => $request->cantidad,
            'motivo' => $request->motivo,
            'referencia' => $request->referencia,
            'id_usuario' => Auth::id() ?? null,
            'fecha_movimiento' => now(),
        ]);

        return redirect()
            ->route('inventario.detalle', $material->id)
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function actualizarConfig(Request $request, $id_material)
    {
        $request->validate([
            'stock_minimo' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:100'
        ]);

        $material = Material::findOrFail($id_material);

        $stock = StockMaterial::firstOrCreate(
            ['id_material' => $material->id],
            ['stock_actual' => 0]
        );

        $stock->stock_minimo = $request->stock_minimo;
        $stock->ubicacion = $request->ubicacion;
        $stock->save();

        return redirect()
            ->route('inventario.detalle', $material->id)
            ->with('success', 'Configuración actualizada correctamente.');
    }
}

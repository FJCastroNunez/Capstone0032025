<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\MovimientoStockMaterial;
use App\Models\Proveedor;
use App\Models\Material;
use App\Models\StockMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedor::all();
        $materiales = Material::all();

        return view('compras.create', compact('proveedores', 'materiales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedor,id_proveedor',
            'fecha' => 'required|date',
            'numero_documento' => 'nullable|string|max:50',
            'materiales' => 'required|array|min:1',
            'materiales.*.id_material' => 'required|exists:material,id',
            'materiales.*.cantidad' => 'required|integer|min:1',
            'materiales.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        // Crear compra
        $compra = Compra::create([
            'id_proveedor' => $request->id_proveedor,
            'fecha' => $request->fecha,
            'numero_documento' => $request->numero_documento,
            'total' => 0
        ]);

        $totalCompra = 0;

        // Guardar detalles
        foreach ($request->materiales as $m) {

            $subtotal = $m['cantidad'] * $m['precio_unitario'];
            $totalCompra += $subtotal;

            DetalleCompra::create([
                'id_compra' => $compra->id_compra,
                'id_material' => $m['id_material'],
                'cantidad' => $m['cantidad'],
                'precio_unitario' => $m['precio_unitario'],
                'subtotal' => $subtotal
            ]);

            // ENTRADA AUTOMÁTICA DE STOCK
            $stock = StockMaterial::firstOrCreate(
                ['id_material' => $m['id_material']],
                ['stock_actual' => 0, 'stock_minimo' => 0]
            );

            $stock->stock_actual += $m['cantidad'];
            $stock->save();

            // Registrar movimiento
            MovimientoStockMaterial::create([
                'id_material' => $m['id_material'],
                'tipo_movimiento' => 'ENTRADA',
                'cantidad' => $m['cantidad'],
                'motivo' => 'Compra a proveedor',
                'referencia' => 'Compra #' . $compra->id_compra,
                'id_usuario' => Auth::id(),
                'fecha_movimiento' => now()
            ]);
        }

        // Actualizar total
        $compra->total = $totalCompra;
        $compra->save();

        return redirect()->route('compras.index')->with('success', 'Compra registrada correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        //
    }
}

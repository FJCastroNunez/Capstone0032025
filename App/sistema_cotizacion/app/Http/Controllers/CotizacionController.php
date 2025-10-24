<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\CotizacionAdjunta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function __construct()
    {
        // 🔒 Requiere usuario autenticado
        $this->middleware('auth');

        // 🔹 Solo el VENDEDOR (rol = 2) puede acceder
        if (Auth::check() && Auth::user()->rol != 2) {
            abort(403, 'Acceso denegado');
        }
    }

    public function index()
    {
        $cotizaciones = Cotizacion::with('id_cliente')->get();
        return view('cotizacion.index', compact('cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('cotizacion.create', compact('clientes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 🔹 Validar los datos del formulario
            $request->validate([
                'id_cliente' => 'required|exists:cliente,id_cliente',
                'fecha' => 'required|date',
                'productos' => 'required|array|min:1',
                'productos.*.id_producto' => 'required|exists:producto,id_producto',
                'productos.*.cantidad' => 'required|numeric|min:1',
                'productos.*.precio_unitario' => 'required|numeric|min:0',
                'adjuntos.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,docx,xlsx'
            ]);

            // 🔹 Crear la cotización base
            $cotizacion = new \App\Models\Cotizacion();
            $cotizacion->id_cliente = $request->id_cliente;
            $cotizacion->fecha = $request->fecha;
            $cotizacion->total = 0; // se recalcula más abajo
            $cotizacion->save();

            // 🔹 Guardar detalles de productos
            $totalGeneral = 0;

            foreach ($request->productos as $producto) {
                $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
                $totalGeneral += $subtotal;

                \App\Models\DetalleCotizacion::create([
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                    'id_producto' => $producto['id_producto'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'total' => $subtotal, // ✅ se guarda correctamente el total
                ]);
            }

            // 🔹 Actualizar total de la cotización
            $cotizacion->update(['total' => $totalGeneral]);

            // 🔹 Guardar archivos adjuntos (si existen)
            if ($request->hasFile('adjuntos')) {
                foreach ($request->file('adjuntos') as $archivo) {
                    $ruta = $archivo->store('public/adjuntos_cotizacion');
                    \App\Models\CotizacionAdjunta::create([
                        'id_cotizacion' => $cotizacion->id_cotizacion,
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'ruta' => $ruta,
                        'tipo' => $archivo->getClientMimeType(),
                    ]);
                }
            }

            return redirect()
                ->route('cotizaciones.index')
                ->with('success', 'Cotización creada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar la cotización: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // 🔹 Cargar la cotización junto con sus detalles y adjuntos
        $cotizacion = Cotizacion::with(['detalles', 'adjuntos'])->findOrFail($id);

        // 🔹 Obtener los clientes y productos disponibles
        $clientes = Cliente::all();
        $productos = Producto::all();

        // 🔹 Retornar la vista con toda la información
        return view('cotizaciones.edit', compact('cotizacion', 'clientes', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1️⃣ Validar los datos recibidos
        $request->validate([
            'id_cliente' => 'required|exists:cliente,id_cliente',
            'fecha' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:producto,id_producto',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            // ✅ Validar los archivos adjuntos (opcionales)
            'adjuntos.*' => 'file|mimes:jpg,jpeg,png,pdf,docx,xlsx|max:10240'
        ]);

        try {
            // 2️⃣ Buscar la cotización existente
            $cotizacion = \App\Models\Cotizacion::findOrFail($id);

            // 3️⃣ Actualizar los campos principales
            $cotizacion->update([
                'id_cliente' => $request->id_cliente,
                'fecha' => $request->fecha,
            ]);

            // 4️⃣ Eliminar los detalles anteriores (productos) y recalcular el total
            $cotizacion->detalles()->delete();

            $total = 0;
            foreach ($request->productos as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $total += $subtotal;

                \App\Models\DetalleCotizacion::create([
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                    'id_producto' => $item['id_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);
            }

            // 5️⃣ Actualizar el total general
            $cotizacion->update(['total' => $total]);

            // 6️⃣ Guardar nuevos archivos adjuntos (si se suben)
            if ($request->hasFile('adjuntos')) {
                foreach ($request->file('adjuntos') as $file) {
                    $nombreOriginal = $file->getClientOriginalName();
                    $formato = $file->getClientOriginalExtension();
                    $ruta = $file->store('public/adjuntos_cotizacion'); // Se guarda en storage/app/public/adjuntos_cotizacion

                    \App\Models\CotizacionAdjunta::create([
                        'id_cotizacion' => $cotizacion->id_cotizacion,
                        'ruta' => $ruta,
                        'nombre_original' => $nombreOriginal,
                        'formato' => $formato,
                        'fecha_subida' => now(),
                    ]);
                }
            }

            // 7️⃣ Redirigir con mensaje de éxito
            return redirect()
                ->route('cotizaciones.index')
                ->with('success', 'Cotización actualizada correctamente junto con los nuevos archivos adjuntos.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar la cotización: ' . $e->getMessage()]);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cotizacion $cotizacion)
    {
        //
    }
}

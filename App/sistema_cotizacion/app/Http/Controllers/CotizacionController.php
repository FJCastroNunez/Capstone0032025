<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\CotizacionAdjunta;
use App\Models\DetalleCotizacion;
use App\Models\Material;
use App\Models\StockMaterial;
use App\Models\MovimientoStockMaterial;
use Illuminate\Support\Facades\File;
use App\Mail\CotizacionEnviada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CotizacionController extends Controller
{
    public function __construct()
    {
        // 🔒 Requiere usuario autenticado
        $this->middleware('auth')->except(['createVisita', 'storeVisita']);

        // 🔹 Solo el VENDEDOR (rol = 2) puede acceder
        if (Auth::check() && Auth::user()->rol != 2) {
            abort(403, 'Acceso denegado');
        }
    }

    public function index()
    {
        $cotizaciones = Cotizacion::with('datosCliente')
            ->where('id_cliente', '!=', 23)
            ->get();
        return view('cotizacion.index', compact('cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::where('id_cliente', '!=', 23)->get();
        $productos = Producto::all();
        $materiales = material::all();
        return view('cotizacion.create', compact('clientes', 'productos', 'materiales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validación básica
        $request->validate([
            'id_cliente' => 'required|exists:cliente,id_cliente',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
            'impuestos' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'productos' => 'required|array|min:1'
        ]);

        foreach ($request->productos as $p) {

            $idMaterial = $p['id_material'];
            $cantidadNecesaria = $p['cantidad'];

            $stock = StockMaterial::where('id_material', $idMaterial)->first();

            if (!$stock || $stock->stock_actual < $cantidadNecesaria) {
                return back()->with('error', 'No hay suficiente stock para el material: ' . ($p['nombre_material'] ?? 'Material ID ' . $idMaterial));
            }
        }
        // ✅ Crear cotización principal
        $cotizacion = Cotizacion::create([
            'id_cliente' => $request->id_cliente,
            'id_usuario' => Auth::id(),
            'fecha' => $request->fecha,
            'subtotal' => $request->subtotal,
            'impuestos' => $request->impuestos,
            'total' => $request->total,
            'estado' => 1
        ]);

        // ✅ Guardar detalles de productos/materiales
        foreach ($request->productos as $p) {
            DetalleCotizacion::create([
                'id_cotizacion' => $cotizacion->id_cotizacion,
                'id_producto' => $p['id_producto'],
                'id_material' => $p['id_material'] ?? null,
                'ancho' => $p['ancho'] ?? null,
                'alto' => $p['alto'] ?? null,
                'espesor' => $p['espesor'] ?? null,
                'valor_m2' => $p['valor_m2'] ?? null,
                'subtotal' => $p['subtotal'] ?? 0,
            ]);
        }

        // ✅ Guardar archivos adjuntos si se subieron
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $archivo) {
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $ruta = $archivo->storeAs("cotizaciones/{$cotizacion->id}", $nombreArchivo, 'public');

                CotizacionAdjunta::create([
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                    'nombre_archivo' => $nombreArchivo,
                    'ruta' => $ruta,
                ]);
            }
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización ' . $cotizacion->id_cotizacion . ' creada correctamente.');
    }


    public function facturar($id)
    {
        $cotizacion = Cotizacion::with(['detalles.material'])->findOrFail($id);

        // Si ya estaba facturada → no volver a descontar stock
        if ($cotizacion->estado == 4) {
            return back()->with('error', 'Esta cotización ya está facturada.');
        }

        foreach ($cotizacion->detalles as $detalle) {

            $material = $detalle->material;
            $cantidad = $detalle->cantidad;

            // Obtener registro de stock del material
            $stock = StockMaterial::firstOrCreate(
                ['id_material' => $material->id],
                ['stock_actual' => 0]
            );

            // Validar stock disponible
            if ($stock->stock_actual < $cantidad) {
                return back()->with('error', "Stock insuficiente para {$material->nombre}. Stock actual: {$stock->stock_actual}");
            }

            // DESCONTAR STOCK
            $stock->stock_actual -= $cantidad;
            $stock->save();

            // REGISTRAR MOVIMIENTO DE SALIDA
            MovimientoStockMaterial::create([
                'id_material' => $material->id,
                'tipo_movimiento' => 'SALIDA',
                'cantidad' => $cantidad,
                'motivo' => 'Uso en cotización facturada',
                'referencia' => 'Cotización #' . $cotizacion->id,
                'id_usuario' => Auth::id(),
                'fecha_movimiento' => now(),
            ]);
        }

        // MARCAR COTIZACIÓN COMO FACTURADA (4)
        $cotizacion->estado = 4;
        $cotizacion->save();

        return back()->with('success', 'Cotización facturada y stock descontado correctamente.');
    }

    public function descargarPdf($id)
    {
        $cotizacion = Cotizacion::with([
            'cliente',
            'detalles.producto',
            'detalles.material',
            'adjuntos'
        ])->findOrFail($id);

        // usa el subtotal de cada detalle (como en tu vista edit)
        $subtotal = $cotizacion->detalles->sum(fn($d) => $d->subtotal ?? 0);
        $iva      = round($subtotal * 0.19, 0);
        $total    = $subtotal + $iva;

        $pdf = PDF::loadView('cotizacion.pdf', [
            'cotizacion' => $cotizacion,
            'subtotal'   => $subtotal,
            'iva'        => $iva,
            'total'      => $total,
        ]);

        return $pdf->stream('cotizacion_' . $cotizacion->id_cotizacion . '.pdf');
    }

    public function pdfExterno($id)
    {
        // 🔹 Cargar cotización con sus relaciones necesarias (sin cliente porque es JSON)
        $cotizacion = Cotizacion::with([
            'detalles.producto',
            'detalles.material',
            'adjuntos'
        ])->findOrFail($id);

        // 🔹 Decodificar los datos del cliente visitante desde el JSON
        $clienteJson = json_decode($cotizacion->cliente, true);

        // Extraer datos básicos del JSON (maneja valores vacíos con 'N/A')
        $nombreCliente   = $clienteJson['nombre']   ?? 'Visitante';
        $correoCliente   = $clienteJson['email']    ?? 'Sin correo';
        $telefonoCliente = $clienteJson['telefono'] ?? 'Sin teléfono';
        $direccion       = $clienteJson['direccion'] ?? 'No especificada';

        // 🔹 Calcular subtotal, IVA y total (como en tu vista edit)
        $subtotal = $cotizacion->detalles->sum(fn($d) => $d->subtotal ?? 0);
        $iva      = round($subtotal * 0.19, 0);
        $total    = $subtotal + $iva;

        // 🔹 Crear el PDF con la vista 'cotizacion.pdf'
        $pdf = PDF::loadView('cotizacion.externo.pdfvisita', [
            'cotizacion'      => $cotizacion,
            'cliente'         => [
                'nombre'   => $nombreCliente,
                'email'    => $correoCliente,
                'telefono' => $telefonoCliente,
                'direccion' => $direccion,
            ],
            'subtotal'        => $subtotal,
            'iva'             => $iva,
            'total'           => $total,
        ]);

        // 🔹 Retornar el PDF directamente al navegador
        return $pdf->stream('cotizacion_visitante_' . $cotizacion->id_cotizacion . '.pdf');
    }
    public function editExterno($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);

        // Decodificar datos del cliente si es una cotización de visitante
        $jsonCliente = json_decode($cotizacion->cliente, true);
        $productos = \App\Models\Producto::all();
        $materiales = \App\Models\Material::all();
        $archivos = \App\Models\CotizacionAdjunta::where('id_cotizacion', $cotizacion->id)->get();

        return view('cotizacion.externo.edit', compact('cotizacion', 'jsonCliente', 'productos', 'materiales', 'archivos'));
    }

    public function show(Cotizacion $cotizacion) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cotizacion = Cotizacion::with(['datosCliente', 'detalles', 'adjuntos'])->findOrFail($id);

        $cliente = Cliente::where('id_cliente', $cotizacion->id_cliente)->get();
        $productos = \App\Models\Producto::all();
        $materiales = \App\Models\Material::all();
        $archivos = \App\Models\CotizacionAdjunta::where('id_cotizacion', $cotizacion->id)->get();

        return view('cotizacion.edit', compact('cotizacion', 'cliente', 'productos', 'materiales', 'archivos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cotizacion = Cotizacion::findOrFail($id);

        // ✅ Actualiza datos principales
        $cotizacion->update([
            'estado' => $request->estado ?? $cotizacion->estado,
        ]);


        return redirect()->route('cotizaciones.index')->with('success', 'Cotización ' . $cotizacion->id_cotizacion . ' actualizada correctamente.');
    }

    public function eliminarAdjunto($id)
    {
        $adjunto = CotizacionAdjunta::findOrFail($id);
        $idCotizacion = $adjunto->id_cotizacion; // ✅ Guardamos el ID antes de eliminar

        // Eliminar archivo físico si existe
        if (Storage::disk('public')->exists($adjunto->ruta)) {
            Storage::disk('public')->delete($adjunto->ruta);
        }

        // Eliminar registro
        $adjunto->delete();

        // ✅ Redirigir al formulario de edición de esa misma cotización
        return redirect()
            ->route('cotizaciones.edit', $idCotizacion)
            ->with('success', 'Archivo eliminado correctamente.');
    }


    public function enviarPorCorreo($id)
    {
        // 🔹 1. Obtener la cotización con sus relaciones
        $cotizacion = Cotizacion::with(['datosCliente', 'detalles.producto', 'adjuntos'])
            ->findOrFail($id);

        // 🔹 2. Definir el correo de destino
        $destino = request('email') ?: ($cotizacion->cliente->email ?? null);
        if (!$destino) {
            return back()->with('error', 'No hay correo del cliente. Ingresa uno.');
        }

        // 🔹 3. Generar el PDF
        $pdf = Pdf::loadView('cotizacion.pdf', [
            'cotizacion' => $cotizacion
        ])->setPaper('letter');

        // 🔹 4. Crear carpeta específica por ID dentro de storage/app
        $folder = storage_path("app/cotizaciones/{$cotizacion->id_cotizacion}");
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0777, true, true);
        }

        // 🔹 5. Guardar el PDF en esa carpeta
        $pdfPath = "{$folder}/Cotizacion_{$cotizacion->id_cotizacion}.pdf";
        $pdf->save($pdfPath);

        // 🔹 6. Buscar archivos adjuntos (si existen)
        $adjuntos = [];
        foreach ($cotizacion->adjuntos as $adj) {
            $path = storage_path("app/" . ltrim($adj->ruta, '/'));
            if (file_exists($path)) {
                $adjuntos[] = $path;
            }
        }

        try {
            Mail::to($destino)
                ->send(new CotizacionEnviada($cotizacion, $pdfPath, $adjuntos));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar correo: ' . $e->getMessage());
        }

        // 🔹 8. Mensaje de éxito
        return back()->with('success', "La cotización #{$cotizacion->id_cotizacion} fue enviada correctamente a {$destino}.");
    }

    public function listarVisitas()
    {
        // 🔹 Selecciona solo cotizaciones sin cliente registrado (visitantes)
        $cotizaciones = Cotizacion::with('datosCliente')
            ->where('id_cliente', '=', 23)
            ->get();

        // 🔹 Decodificar el JSON de cliente para mostrar su info
        foreach ($cotizaciones as $c) {
            $c->cliente_json = json_decode($c->cliente, true);
        }

        return view('cotizacion.externo.visitas', compact('cotizaciones'));
    }


    public function createVisita()
    {
        $productos = Producto::all();
        $materiales = Material::all();

        return view('cotizacion.externo.visita', compact('productos', 'materiales'));
    }

    public function storeVisita(Request $request)
    {
        // ✅ Validación básica
        $request->validate([
            'cliente_json' => 'required|array|min:1',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
            'impuestos' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'productos' => 'required|array|min:1',
        ]);

        try {
            // ✅ Agregar campo "visto" = 1 al cliente
            $cliente = $request->cliente_json;
            $cliente['visto'] = 1; // 1 = sin abrir, 2 = abierta

            // ✅ Crear cotización principal con datos del cliente JSON
            $cotizacion = Cotizacion::create([
                'cliente'     => json_encode($request->cliente_json, JSON_UNESCAPED_UNICODE),
                'id_cliente' => 23,
                'id_usuario' => 8,
                'fecha'       => $request->fecha,
                'subtotal'    => $request->subtotal,
                'impuestos'   => $request->impuestos,
                'total'       => $request->total,
                'estado'      => 1, // estado inicial
                'created_at' => now()
            ]);

            // ✅ Guardar detalles de productos/materiales
            foreach ($request->productos as $p) {
                DetalleCotizacion::create([
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                    'id_producto'   => $p['id_producto'],
                    'id_material'   => $p['id_material'] ?? null,
                    'ancho'         => $p['ancho'] ?? null,
                    'alto'          => $p['alto'] ?? null,
                    'espesor'       => $p['espesor'] ?? null,
                    'valor_m2'      => $p['valor_m2'] ?? null,
                    'subtotal'      => $p['subtotal'] ?? 0,
                ]);
            }

            // ✅ Guardar archivos adjuntos (si existen)
            if ($request->hasFile('adjuntos')) {
                foreach ($request->file('adjuntos') as $archivo) {
                    $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                    $ruta = $archivo->storeAs("cotizaciones/{$cotizacion->id_cotizacion}", $nombreArchivo, 'public');

                    CotizacionAdjunta::create([
                        'id_cotizacion'  => $cotizacion->id_cotizacion,
                        'nombre_archivo' => $nombreArchivo,
                        'ruta'           => $ruta,
                    ]);
                }
            }

            // ✅ Enviar respuesta JSON (para AJAX)
            return response()->json([
                'success' => true,
                'message' => '¡Tu cotización fue creada correctamente!',
                'id' => $cotizacion->id_cotizacion
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar la cotización.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function marcarVista($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cliente = json_decode($cotizacion->cliente, true);

        if (($cliente['visto'] ?? 0) == 1) {
            $cliente['visto'] = 2;
            $cotizacion->cliente = json_encode($cliente, JSON_UNESCAPED_UNICODE);
            $cotizacion->save();
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Cotizacion $cotizacion)
    {
        //
    }
}
